<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleName = $user->role->name;

        return match ($roleName) {
            'admin' => view('dashboard.admin', [
                'usersCount' => User::whereHas('role', fn($q) => $q->where('name', '!=', 'admin'))->count(),
            ]),
            'worker' => view('dashboard.worker', [
                'productsCount' => Product::count(),
                'ordersCount' => Order::count(),
                'orders' => Order::with(['client', 'status'])->latest()->take(5)->get(),
            ]),
            'client' => view('dashboard.client', [
                'myOrdersCount' => $user->orders()->count(),
                'pendingOrdersCount' => $user->orders()
                    ->whereHas('status', fn($q) => $q->where('name', 'w trakcie realizacji'))
                    ->count(),
                'products' => Product::orderBy('id', 'desc')->paginate(5),
            ]),
            default => abort(403),
        };
    }
}
