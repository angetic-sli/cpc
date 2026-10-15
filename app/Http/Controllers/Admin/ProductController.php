<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Product; use App\Models\Category; use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Support\Facades\Storage;
class ProductController extends Controller {
 public function index(){ $products=Product::with('category')->latest()->paginate(15);return view('admin.products.index',compact('products')); }
 public function create(){ $categories=Category::orderBy('name')->get();return view('admin.products.form',compact('categories')); }
 public function store(Request $r){$data=$this->validated($r);if($r->hasFile('image'))$data['image']=$r->file('image')->store('products','public');$data['slug']=$this->uniqueSlug($data['name']);Product::create($data);return redirect()->route('admin.produits.index')->with('success','Produit créé.');}
 public function edit(Product $product){$categories=Category::orderBy('name')->get();return view('admin.products.form',compact('product','categories')); }
 public function update(Request $r,Product $product){$data=$this->validated($r,$product);if($r->hasFile('image')){if($product->image)Storage::disk('public')->delete($product->image);$data['image']=$r->file('image')->store('products','public');}$data['slug']=$this->uniqueSlug($data['name'],$product->id);$product->update($data);return redirect()->route('admin.produits.index')->with('success','Produit modifié.');}
 public function destroy(Product $product){$product->delete();return back()->with('success','Produit supprimé.');}
 private function validated(Request $r,?Product $p=null):array{
  $data=$r->validate(['name'=>'required|string|max:190','category_id'=>'nullable|exists:categories,id','sku'=>'nullable|string|max:100|unique:products,sku,'.($p?->id??'NULL').',id','short_description'=>'nullable|string|max:500','description'=>'nullable|string','price'=>'required|numeric|min:0','sale_price'=>'nullable|numeric|min:0|lte:price','stock'=>'required|integer|min:0','colors_text'=>'nullable|string|max:2000','sizes_text'=>'nullable|string|max:2000','image'=>'nullable|image|max:4096','is_active'=>'nullable|boolean','is_featured'=>'nullable|boolean']);
  $data['colors']=$this->parseOptions($data['colors_text']??'');$data['sizes']=$this->parseOptions($data['sizes_text']??'');unset($data['colors_text'],$data['sizes_text']);$data['is_active']=$r->boolean('is_active');$data['is_featured']=$r->boolean('is_featured');return $data;
 }
 private function parseOptions(string $value):array{return array_values(array_unique(array_filter(array_map(fn($item)=>trim($item),preg_split('/[,;\n]+/',$value)))));}
 private function uniqueSlug(string $name,?int $ignore=null):string{$slug=Str::slug($name);$base=$slug;$n=1;while(Product::where('slug',$slug)->when($ignore,fn($q)=>$q->where('id','!=',$ignore))->exists())$slug=$base.'-'.$n++;return $slug;}
}
