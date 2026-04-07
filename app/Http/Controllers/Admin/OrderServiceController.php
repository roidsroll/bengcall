<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $orders = OrderService::query()
            ->with([
                'customer:id,name,email',
                'technician:id,name,email',
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

        return view('admin.order_services.index', [
            'orders' => $orders,
            'search' => $search,
        ]);
    }

    public function show(OrderService $orderService)
    {
        $orderService->load([
            'customer:id,name,email',
            'technician:id,name,email',
            'details',
        ]);

        return view('admin.order_services.show', [
            'orderService' => $orderService,
        ]);
    }
}

