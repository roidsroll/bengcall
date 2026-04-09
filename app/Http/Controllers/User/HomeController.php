<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $orders = Order::query()
            ->with([
                'details.product:id,name,products_images,code_parts',
            ])
            ->where('user_id', $request->user()->id)
            ->whereHas('details', fn ($query) => $query->where('type', 'part'))
            ->latest('id')
            ->get();

        return view('user.home', [
            'orders' => $orders,
        ]);
    }
}
