<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayOrders = Order::with('customer')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $todayOrdersCount = $todayOrders->count();
        $yesterdayOrdersCount = Order::whereDate('created_at', $yesterday)->count();

        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'rolesCount' => Role::count(),
            'menusCount' => DB::table('menus')->count(),
            'todayOrders' => $todayOrders,
            'todayOrdersCount' => $todayOrdersCount,
            'yesterdayOrdersCount' => $yesterdayOrdersCount,
        ]);
    }
}

