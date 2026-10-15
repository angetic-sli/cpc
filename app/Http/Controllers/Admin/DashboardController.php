<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\Product;use App\Models\Order;use App\Models\User;use App\Models\Category;
class DashboardController extends Controller {
 public function index(){return view('admin.dashboard',['products'=>Product::count(),'orders'=>Order::count(),'users'=>User::count(),'categories'=>Category::count(),'revenue'=>Order::where('payment_status','paid')->sum('total'),'recentOrders'=>Order::with('user')->latest()->take(8)->get()]);}
}
