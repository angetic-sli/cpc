<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,failed,refunded',
            'carrier' => 'nullable|string|max:120',
            'tracking_number' => 'nullable|string|max:190',
        ]);
        // Do not let the admin UI downgrade a payment confirmed by the provider.
        if ($order->payment_status === 'paid' && $data['payment_status'] !== 'paid' && !$request->user()->hasPermission('payments.refund')) {
            $data['payment_status'] = 'paid';
        }
        $order->update($data);
        return back()->with('success', 'Commande et informations de livraison mises à jour.');
    }
}
