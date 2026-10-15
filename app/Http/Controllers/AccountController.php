<?php
namespace App\Http\Controllers;
use App\Models\Order;
class AccountController extends Controller {
 public function index(){ $orders=request()->user()->orders()->latest()->paginate(10);return view('account.index',compact('orders')); }
 public function order(Order $order){abort_unless($order->user_id===request()->user()->id,404);$order->load('items');return view('account.order',compact('order'));}
}
