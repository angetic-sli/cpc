<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SiteAppearanceController;
use App\Models\Order;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/boutique', [StoreController::class, 'index'])->name('shop');
Route::get('/categorie/{category:slug}', [StoreController::class, 'category'])->name('category.show');
Route::get('/produit/{product:slug}', [StoreController::class, 'show'])->name('product.show');
Route::get('/recherche', [StoreController::class, 'search'])->name('search');

Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/commande', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/commande', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/mon-compte', [AccountController::class, 'index'])->name('account');
    Route::get('/mes-commandes/{order}', [AccountController::class, 'order'])->name('account.order');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['auth', 'permission:access-admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('produits', ProductController::class)->parameters(['produits' => 'product'])->middleware(['index'=>'permission:products.view','create'=>'permission:products.create','store'=>'permission:products.create','edit'=>'permission:products.update','update'=>'permission:products.update','destroy'=>'permission:products.delete']);
    Route::resource('categories', CategoryController::class)->parameters(['categories' => 'category'])->middleware(['index'=>'permission:categories.view','create'=>'permission:categories.create','store'=>'permission:categories.create','edit'=>'permission:categories.update','update'=>'permission:categories.update','destroy'=>'permission:categories.delete']);
    Route::get('/commandes', [OrderController::class, 'index'])->middleware('permission:orders.view')->name('orders.index');
    Route::get('/commandes/{order}', [OrderController::class, 'show'])->middleware('permission:orders.view')->name('orders.show');
    Route::patch('/commandes/{order}', [OrderController::class, 'update'])->middleware('permission:orders.update')->name('orders.update');
    Route::get('/utilisateurs', [UserController::class, 'index'])->middleware('permission:users.view')->name('users.index');
    Route::get('/apparence/hero', [SiteAppearanceController::class, 'hero'])->name('site.hero');
    Route::put('/apparence/hero', [SiteAppearanceController::class, 'saveHero'])->name('site.hero.save');
    Route::get('/apparence/couleurs', [SiteAppearanceController::class, 'theme'])->name('site.theme');
    Route::put('/apparence/couleurs', [SiteAppearanceController::class, 'saveTheme'])->name('site.theme.save');
    Route::get('/publicites', [SiteAppearanceController::class, 'ads'])->name('site.ads');
    Route::put('/publicites', [SiteAppearanceController::class, 'saveAds'])->name('site.ads.save');
    Route::get('/parametres', [SettingsController::class, 'edit'])->middleware('permission:settings.view')->name('settings.edit');
    Route::put('/parametres', [SettingsController::class, 'update'])->middleware('permission:settings.update')->name('settings.update');
    Route::patch('/utilisateurs/{user}/role', [UserController::class, 'updateRole'])->middleware('permission:users.roles.update')->name('users.role');
});
