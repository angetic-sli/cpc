<?php
namespace App\Http\Controllers;
use App\Models\Product; use Illuminate\Http\Request;
class CartController extends Controller {
 public function index() {
  $cart=session('cart',[]); $ids=array_keys($cart); $products=Product::whereIn('id',$ids)->get()->keyBy('id'); $total=0;
  foreach($cart as $id=>$item){if(isset($products[$id])) $total += (float)($products[$id]->sale_price ?? $products[$id]->price)*(int)$item['quantity'];}
  return view('store.cart',compact('cart','products','total'));
 }
 public function add(Request $request, Product $product) {
  abort_unless($product->is_active,404);
  $data=$request->validate(['quantity'=>'nullable|integer|min:1|max:20','color'=>'nullable|string|max:80','size'=>'nullable|string|max:80']);
  if(!empty($product->colors)&&!in_array($data['color']??'', $product->colors, true)) return back()->withErrors(['color'=>'Veuillez choisir une couleur disponible.'])->withInput();
  if(!empty($product->sizes)&&!in_array($data['size']??'', $product->sizes, true)) return back()->withErrors(['size'=>'Veuillez choisir une taille disponible.'])->withInput();
  $qty=(int)($data['quantity']??1); abort_if($qty>$product->stock,422,'Stock insuffisant.');
  $cart=session('cart',[]); $new=($cart[$product->id]['quantity']??0)+$qty; abort_if($new>$product->stock,422,'Stock insuffisant.');
  $cart[$product->id]=['quantity'=>$new,'color'=>$data['color']??null,'size'=>$data['size']??null]; session(['cart'=>$cart]);
  return back()->with('success','Produit ajouté au panier.');
 }
 public function update(Request $request, Product $product) {
  $data=$request->validate(['quantity'=>'required|integer|min:1|max:20']);
  abort_if($data['quantity']>$product->stock,422,'Stock insuffisant.');
  $cart=session('cart',[]); if(isset($cart[$product->id])){$cart[$product->id]['quantity']=$data['quantity'];session(['cart'=>$cart]);}
  return back()->with('success','Panier mis à jour.');
 }
 public function remove(Product $product) { $cart=session('cart',[]);unset($cart[$product->id]);session(['cart'=>$cart]);return back()->with('success','Produit retiré.'); }
}
