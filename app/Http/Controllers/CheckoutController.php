<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function create()
    {
        if (empty(session('cart', []))) return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        $shipping=StoreSetting::getValue('shipping_options', ['enabled'=>true]);
        return view('store.checkout', ['shippingOptions'=>$shipping]);
    }

    public function store(Request $request)
    {
        $shippingOptions=StoreSetting::getValue('shipping_options',['enabled'=>true,'free_from'=>0,'handling_fee'=>0]);
        $data=$request->validate([
            'customer_name'=>'required|string|max:150','customer_email'=>'required|email|max:190','customer_phone'=>'required|string|max:40',
            'shipping_address'=>'required|string|max:2000','notes'=>'nullable|string|max:2000',
            'payment_method'=>'required|in:cash_on_delivery,bank_transfer',
            'shipping_zone'=>'required|string|max:120','carrier'=>'nullable|string|max:120',
        ]);
        $cart=session('cart',[]); abort_if(empty($cart),422,'Votre panier est vide.');
        if (empty($shippingOptions['enabled'])) return back()->withErrors(['shipping_zone'=>'La livraison est momentanément indisponible.'])->withInput();
        $zones=StoreSetting::getValue('shipping_zones',[]);
        $zone=collect($zones)->first(fn($z)=>($z['name']??null)===$data['shipping_zone']&&!empty($z['active']));
        if(!$zone||!isset($zone['price'])) return back()->withErrors(['shipping_zone'=>'Zone de livraison invalide.'])->withInput();
        $carriers=StoreSetting::getValue('shipping_carriers',[]);
        $carrier=null;
        if(!empty($data['carrier'])) {
            $carrier=collect($carriers)->first(fn($c)=>($c['name']??null)===$data['carrier']&&!empty($c['active']));
            if(!$carrier) return back()->withErrors(['carrier'=>'Transporteur indisponible.'])->withInput();
        }
        $order=DB::transaction(function() use($cart,$data,$request,$zone,$carrier,$shippingOptions) {
            $lines=[];$subtotal=0;
            foreach($cart as $id=>$row){$product=Product::lockForUpdate()->findOrFail($id);$qty=(int)($row['quantity']??0);if($qty<1||!$product->is_active||$product->stock<$qty)throw ValidationException::withMessages(['cart'=>'Stock insuffisant pour '.$product->name]);$price=(float)($product->sale_price??$product->price);$lines[]=[$product,$qty,$price];$subtotal+=$price*$qty;}
            $freeFrom=(int)($shippingOptions['free_from']??0);
            $shipping=$freeFrom>0&&$subtotal >= $freeFrom ? 0 : (int)$zone['price']+(int)($carrier['extra_fee']??0)+(int)($shippingOptions['handling_fee']??0);
            $order=Order::create([
                'user_id'=>$request->user()->id,'number'=>'SDJ-'.strtoupper(\Illuminate\Support\Str::random(8)),
                'status'=>'pending','payment_status'=>'unpaid','payment_method'=>$data['payment_method'],
                'subtotal'=>$subtotal,'shipping_cost'=>$shipping,'total'=>$subtotal+$shipping,
                'shipping_zone'=>$data['shipping_zone'],'carrier'=>$data['carrier']??null,
                'customer_name'=>$data['customer_name'],'customer_email'=>$data['customer_email'],'customer_phone'=>$data['customer_phone'],
                'shipping_address'=>$data['shipping_address'],'notes'=>$data['notes']??null,
            ]);
            foreach($lines as [$product,$qty,$price]){$order->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'quantity'=>$qty,'unit_price'=>$price,'line_total'=>$price*$qty]);$product->decrement('stock',$qty);}
            return $order;
        });
        session()->forget('cart');
        return redirect()->route('account.order',$order)->with('success','Commande enregistrée.');
    }
}
