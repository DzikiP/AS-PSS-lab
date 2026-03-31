<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');

        $products = Product::when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%"))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('orders.create', compact('products', 'search', 'sort', 'direction'));
    }

    public function myOrders(Request $request)
    {
        $user = $request->user();
        $query = $user->orders()->with(['status', 'products']);

        if ($request->filled('search')) {
            $query->where('id', $request->search);
        }

        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        $orders = $query->simplePaginate(5)->withQueryString();
        $statuses = OrderStatus::all();

        return view('orders.my', compact('orders', 'statuses'));
    }

    public function index(Request $request)
    {
        $query = Order::with(['client', 'status']);

        if ($request->filled('search')) {
            $query->where('id', $request->search)
                ->orWhereHas('client', fn($q) => $q->where('username', 'like', "%{$request->search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        if ($sort === 'client') {
            $query->join('users', 'orders.client_id', '=', 'users.id')
                ->orderBy('users.username', $direction)
                ->select('orders.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        $orders = $query->paginate(10);
        $statuses = OrderStatus::all();

        return view('orders.index', compact('orders', 'statuses'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', [
            'order' => $order,
            'statuses' => OrderStatus::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'integer|min:0',
        ]);

        $status = OrderStatus::where('name', 'nowy')->firstOrFail();
        $productsData = [];
        $totalPrice = 0;

        foreach ($request->products as $productId => $quantity) {
            if ($quantity > 0) {
                $product = Product::findOrFail($productId);
                $productsData[$productId] = [
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
                $totalPrice += $product->price * $quantity;
            }
        }

        if ($request->has('confirm')) {
            $order = Order::create([
                'client_id' => $request->user()->id,
                'status_id' => $status->id,
                'total_price' => $totalPrice,
            ]);

            foreach ($productsData as $productId => $data) {
                $order->products()->attach($productId, ['quantity' => $data['quantity'], 'price' => $data['price']]);
            }

            return redirect()->route('orders.my')->with('success', 'Zamówienie zostało złożone.');
        }

        $products = Product::whereIn('id', array_keys($productsData))->get();

        return view('orders.confirm', compact('products', 'productsData', 'totalPrice'));
    }


    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status_id' => 'exists:order_statuses,id']);
        $order->update(['status_id' => $request->status_id]);

        return redirect()->route('orders.index')->with('success', 'Status zamówienia został zaktualizowany.');
    }
}
