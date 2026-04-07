<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private const WALK_IN_USER_EMAIL = 'walkin@bengcall.test';
    private const WALK_IN_NOTES_PREFIX = '__walk_in__=';

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $orders = Order::query()
            ->with([
                'customer:id,name,email',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('vehicle_name', 'like', "%{$search}%")
                        ->orWhere('license_plate', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $customers = User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'user'))
            ->where('email', '!=', self::WALK_IN_USER_EMAIL)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $technicians = User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'teknisi'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name', 'sell_price', 'unit']);

        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'base_price']);

        return view('admin.orders.create', [
            'customers' => $customers,
            'technicians' => $technicians,
            'products' => $products,
            'services' => $services,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customerType = (string) $request->input('customer_type', 'customer');
        if (! in_array($customerType, ['customer', 'walk_in'], true)) {
            $customerType = 'customer';
        }
        $request->merge([
            'customer_type' => $customerType,
        ]);

        $spareparts = $this->normalizeLines($request->input('spareparts', []), 'product_id');
        $serviceItems = $this->normalizeLines($request->input('services', []), 'service_id');

        $request->merge([
            'spareparts' => $spareparts,
            'services' => $serviceItems,
        ]);

        $rules = [
            'customer_type' => ['required', 'in:customer,walk_in'],
            'technician_id' => ['nullable', 'integer', 'exists:users,id'],
            'vehicle_name' => ['required', 'string', 'max:255'],
            'license_plate' => ['required', 'string', 'max:255'],
            'customer_notes' => ['nullable', 'string', 'max:5000'],
            'spareparts' => ['nullable', 'array'],
            'spareparts.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'spareparts.*.quantity' => ['required', 'integer', 'min:1'],
            'spareparts.*.price' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'array'],
            'services.*.service_id' => ['required', 'integer', 'exists:services,id'],
            'services.*.quantity' => ['required', 'integer', 'min:1'],
            'services.*.price' => ['required', 'numeric', 'min:0'],
        ];

        if ($customerType === 'customer') {
            $rules['user_id'] = ['required', 'integer', 'exists:users,id'];
        } else {
            $rules['walk_in_name'] = ['required', 'string', 'max:255'];
            $rules['walk_in_call_number'] = ['required', 'string', 'max:20'];
            $rules['walk_in_address'] = ['required', 'string', 'max:5000'];
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($customerType, $request, $spareparts, $serviceItems) {
            if (count($spareparts) < 1 && count($serviceItems) < 1) {
                $validator->errors()->add('items', 'Minimal pilih 1 item di tab Sparepart atau Services.');
            }

            if ($customerType === 'walk_in') {
                $notes = $this->composeWalkInCustomerNotes(
                    (string) $request->input('walk_in_name', ''),
                    (string) $request->input('walk_in_call_number', ''),
                    (string) $request->input('walk_in_address', ''),
                    $request->input('customer_notes')
                );

                if (mb_strlen($notes) > 5000) {
                    $validator->errors()->add('customer_notes', 'Catatan terlalu panjang. Mohon ringkas agar total tetap di bawah 5000 karakter.');
                }
            }
        });

        $validated = $validator->validate();
        $spareparts = $validated['spareparts'] ?? [];
        $serviceItems = $validated['services'] ?? [];

        $userId = null;
        $customerNotes = $validated['customer_notes'] ?? null;

        if ($customerType === 'walk_in') {
            $userId = $this->ensureWalkInUser()->id;
            $customerNotes = $this->composeWalkInCustomerNotes(
                $validated['walk_in_name'],
                $validated['walk_in_call_number'],
                $validated['walk_in_address'],
                $validated['customer_notes'] ?? null
            );
        } else {
            $userId = (int) $validated['user_id'];
        }

        $result = DB::transaction(function () use ($customerNotes, $userId, $validated, $spareparts, $serviceItems) {
            $order = null;
            $orderService = null;

            $productIds = collect($spareparts)
                ->pluck('product_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $serviceIds = collect($serviceItems)
                ->pluck('service_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $productNames = Product::query()
                ->whereIn('id', $productIds->all())
                ->pluck('name', 'id');

            $serviceNames = Service::query()
                ->whereIn('id', $serviceIds->all())
                ->pluck('name', 'id');

            if (count($spareparts) > 0) {
                $orderNumber = $this->generateOrderNumber();

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => (int) $userId,
                    'vehicle_name' => $validated['vehicle_name'],
                    'license_plate' => $validated['license_plate'],
                    'customer_notes' => $customerNotes,
                    'total_price' => 0,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                $total = 0;

                foreach ($spareparts as $item) {
                    $quantity = (int) $item['quantity'];
                    $price = (float) $item['price'];
                    $subtotal = $quantity * $price;
                    $productId = (int) $item['product_id'];

                    $itemName = (string) ($productNames[$productId] ?? 'Part');

                    $order->details()->create([
                        'product_id' => $productId,
                        'service_id' => null,
                        'item_name' => $itemName,
                        'type' => 'part',
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    $total += $subtotal;
                }

                $order->update([
                    'total_price' => $total,
                ]);
            }

            if (count($serviceItems) > 0) {
                $orderNumber = $this->generateOrderServiceNumber();

                $orderService = OrderService::create([
                    'order_number' => $orderNumber,
                    'user_id' => (int) $userId,
                    'technician_id' => isset($validated['technician_id']) ? (int) $validated['technician_id'] : null,
                    'vehicle_name' => $validated['vehicle_name'],
                    'license_plate' => $validated['license_plate'],
                    'customer_notes' => $customerNotes,
                    'total_price' => 0,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                $total = 0;

                foreach ($serviceItems as $item) {
                    $quantity = (int) $item['quantity'];
                    $price = (float) $item['price'];
                    $subtotal = $quantity * $price;
                    $serviceId = (int) $item['service_id'];

                    $itemName = (string) ($serviceNames[$serviceId] ?? 'Service');

                    $orderService->details()->create([
                        'product_id' => null,
                        'service_id' => $serviceId,
                        'item_name' => $itemName,
                        'type' => 'service',
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    $total += $subtotal;
                }

                $orderService->update([
                    'total_price' => $total,
                ]);
            }

            return [
                'order' => $order,
                'orderService' => $orderService,
            ];
        });

        /** @var \App\Models\Order|null $order */
        $order = $result['order'] ?? null;
        /** @var \App\Models\OrderService|null $orderService */
        $orderService = $result['orderService'] ?? null;

        if ($order && ! $orderService) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('status', 'Order sparepart berhasil dibuat.');
        }

        if (! $order && $orderService) {
            return redirect()
                ->route('admin.order-services.show', $orderService)
                ->with('status', 'Order service berhasil dibuat.');
        }

        $orderNumber = $order?->order_number;
        $orderServiceNumber = $orderService?->order_number;
        $status = 'Order berhasil dibuat.';
        if ($orderNumber && $orderServiceNumber) {
            $status = "Order sparepart ({$orderNumber}) & service ({$orderServiceNumber}) berhasil dibuat.";
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('status', $status);
    }

    public function show(Order $order)
    {
        $order->load([
            'customer:id,name,email',
            'details',
        ]);

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    private function ensureWalkInUser(): User
    {
        $roleId = (int) Role::query()
            ->where('name', 'user')
            ->value('id');

        if ($roleId < 1) {
            $roleId = (int) Role::query()->value('id');
        }

        return User::query()->firstOrCreate(
            ['email' => self::WALK_IN_USER_EMAIL],
            [
                'name' => 'Walk In',
                'role_id' => $roleId,
                'password' => Hash::make(Str::random(40)),
                'gender' => 'Laki-laki',
                'address' => null,
                'is_active' => false,
            ]
        );
    }

    private function composeWalkInCustomerNotes(string $name, string $callNumber, string $address, mixed $notes): string
    {
        $payload = json_encode([
            'name' => trim($name),
            'call_number' => trim($callNumber),
            'address' => trim($address),
        ], JSON_UNESCAPED_UNICODE);

        $prefixLine = self::WALK_IN_NOTES_PREFIX.($payload ?: '{}');

        $notesText = is_string($notes) ? trim($notes) : '';
        if ($notesText === '') {
            return $prefixLine;
        }

        return $prefixLine."\n\n".$notesText;
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "BNC-{$date}-";

        $last = Order::query()
            ->where('order_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('order_number');

        $next = 1;

        if (is_string($last) && str_starts_with($last, $prefix)) {
            $suffix = substr($last, strlen($prefix));
            $next = max(1, (int) $suffix + 1);
        }

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    private function generateOrderServiceNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "BNC-SVC-{$date}-";

        $last = OrderService::query()
            ->where('order_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('order_number');

        $next = 1;

        if (is_string($last) && str_starts_with($last, $prefix)) {
            $suffix = substr($last, strlen($prefix));
            $next = max(1, (int) $suffix + 1);
        }

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    private function normalizeLines(mixed $lines, string $requiredKey): array
    {
        if (! is_array($lines)) {
            return [];
        }

        $filtered = array_filter($lines, function ($line) use ($requiredKey) {
            if (! is_array($line)) {
                return false;
            }

            $value = $line[$requiredKey] ?? null;

            return $value !== null && $value !== '';
        });

        return array_values($filtered);
    }
}
