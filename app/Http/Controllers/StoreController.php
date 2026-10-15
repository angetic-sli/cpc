<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\Category; use App\Models\StoreSetting; use Illuminate\Http\Request;
class StoreController extends Controller {
 public function home() {
  $featured = Product::with('category')->where('is_active',true)->where('is_featured',true)->latest()->take(8)->get();
  $latest = Product::with('category')->where('is_active',true)->latest()->take(8)->get();
  $categories = Category::where('is_active',true)->withCount('products')->take(6)->get();
  $slides = StoreSetting::getValue('hero_slides', [
   ['kicker'=>'SA’DJA COMPAGNIE · COLLECTION','title'=>'Le style est une','highlight'=>'signature.','description'=>'Des pièces élégantes, des détails soignés et une allure qui vous ressemble.','button'=>'Explorer la boutique','url'=>'/boutique','footnote'=>'01 / MODE & STYLE','image'=>'https://images.unsplash.com/photo-1590736969955-71cc94901144?auto=format&fit=crop&w=2200&q=90','active'=>true],
   ['kicker'=>'CRÉATION SUR MESURE','title'=>'Votre idée.','highlight'=>'Notre savoir-faire.','description'=>'Une coupe précise, une finition remarquable et une création pensée autour de vous.','button'=>'Découvrir nos créations','url'=>'/boutique','footnote'=>'02 / COUTURE & CRÉATION','image'=>'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=2200&q=85','active'=>true],
   ['kicker'=>'PERSONNALISATION & DÉTAILS','title'=>'Faites de chaque','highlight'=>'détail une marque.','description'=>'Couture, broderie et impression : donnez vie à vos projets avec SA’DJA.','button'=>'Parlons de votre projet','url'=>'/boutique','footnote'=>'03 / BRODERIE & IMPRESSION','image'=>'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=2200&q=85','active'=>true],
  ]);
  $slides = array_values(array_filter($slides, fn($slide) => !empty($slide['active'])));
  $theme = StoreSetting::getValue('site_theme', ['primary'=>'#C89424','secondary'=>'#171717','background'=>'#FAF8F4','text'=>'#20231F','surface'=>'#FFFFFF']);
  $ads = array_values(array_filter(StoreSetting::getValue('site_ads', []), function($ad) { $now=now(); return !empty($ad['active']) && (empty($ad['starts_at']) || $now->gte(\Carbon\Carbon::parse($ad['starts_at']))) && (empty($ad['ends_at']) || $now->lte(\Carbon\Carbon::parse($ad['ends_at']))); }));
  return view('store.home', compact('featured','latest','categories','slides','theme','ads'));
 }
 public function index(Request $request) {
  $products = Product::with('category')->where('is_active',true)
   ->when($request->filled('q'),fn($q)=>$q->where('name','like','%'.$request->q.'%'))
   ->when($request->filled('category'),fn($q)=>$q->whereHas('category',fn($c)=>$c->where('slug',$request->category)))
   ->latest()->paginate(12)->withQueryString();
  $categories = Category::where('is_active',true)->get();
  return view('store.shop',compact('products','categories'));
 }
 public function category(Category $category) {
  abort_unless($category->is_active,404);
  $products = $category->products()->where('is_active',true)->latest()->paginate(12);
  $categories = Category::where('is_active',true)->get();
  return view('store.shop',compact('products','categories','category'));
 }
 public function show(Product $product) { abort_unless($product->is_active,404); $product->load('category'); $related=Product::where('is_active',true)->where('id','!=',$product->id)->take(4)->get(); return view('store.product',compact('product','related')); }
 public function search(Request $request) { return redirect()->route('shop',['q'=>$request->input('q')]); }
}
