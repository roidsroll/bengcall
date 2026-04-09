<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    public function __invoke(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $quantity = (int) $validated['quantity'];

        $order = DB::transaction(function () use ($product, $request, $validated, $quantity) {
            /** @var Product $lockedProduct */
            $lockedProduct = Product::query()
                ->whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $lockedProduct->stock < $quantity) {
                return null;
            }

            $price = (float) $lockedProduct->sell_price;
            $subtotal = $price * $quantity;

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => (int) $request->user()->id,
                'customer_notes' => $validated['customer_notes'] ?? null,
                'total_price' => $subtotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            $order->details()->create([
                'product_id' => $lockedProduct->id,
                'service_id' => null,
                'item_name' => $lockedProduct->name,
                'type' => 'part',
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ]);

            $lockedProduct->update([
                'stock' => (int) $lockedProduct->stock - $quantity,
            ]);

            StockTransaction::create([
                'product_id' => $lockedProduct->id,
                'type' => 'out',
                'quantity' => $quantity,
                'reference' => $order->order_number,
                'note' => 'Order produk dari halaman user.',
                'user_id' => (int) $request->user()->id,
            ]);

            return $order;
        });

        if (! $order) {
            return redirect()
                ->route('products')
                ->withErrors([
                    'order' => 'Stock produk tidak mencukupi untuk jumlah yang dipilih.',
                ]);
        }

        return redirect()
            ->route('products')
            ->with('order_success_message', "Pesanan {$order->order_number} berhasil dibuat.");
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
}
