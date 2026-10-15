<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\Category;use Illuminate\Http\Request;use Illuminate\Support\Str;
class CategoryController extends Controller {
 public function index(){ $categories=Category::withCount('products')->orderBy('name')->paginate(20);return view('admin.categories.index',compact('categories')); }
 public function create(){return view('admin.categories.form');}
 public function store(Request $r){$d=$r->validate(['name'=>'required|string|max:150','description'=>'nullable|string','is_active'=>'nullable|boolean']);$d['slug']=Str::slug($d['name']);$d['is_active']=$r->boolean('is_active');Category::create($d);return redirect()->route('admin.categories.index')->with('success','Catégorie créée.');}
 public function edit(Category $category){return view('admin.categories.form',compact('category'));}
 public function update(Request $r,Category $category){$d=$r->validate(['name'=>'required|string|max:150','description'=>'nullable|string','is_active'=>'nullable|boolean']);$d['slug']=Str::slug($d['name']);$d['is_active']=$r->boolean('is_active');$category->update($d);return redirect()->route('admin.categories.index')->with('success','Catégorie modifiée.');}
 public function destroy(Category $category){$category->delete();return back()->with('success','Catégorie supprimée.');}
}
