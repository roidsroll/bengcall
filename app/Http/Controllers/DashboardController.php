<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = auth()->user();

        if (in_array($user?->role?->name, ['admin', 'teknisi'], true)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.home');
    }
}
