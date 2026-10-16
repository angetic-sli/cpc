<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;use App\Models\User;use App\Models\Role;use App\Models\Permission;use App\Models\Category;use App\Models\Product;use App\Models\StoreSetting;use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  $perms=[];foreach(['access-admin'=>'Accéder à l’administration','manage-products'=>'Gérer les produits','manage-orders'=>'Gérer les commandes','manage-users'=>'Gérer les utilisateurs'] as $name=>$label)$perms[$name]=Permission::firstOrCreate(['name'=>$name],['label'=>$label]);
  StoreSetting::putValue('shipping_zones', StoreSetting::getValue('shipping_zones', [['name'=>'Abidjan','price'=>1500,'active'=>true],['name'=>'Intérieur du pays','price'=>3000,'active'=>true]]));
  StoreSetting::putValue('shipping_carriers', StoreSetting::getValue('shipping_carriers', [['name'=>'Livraison SA’DJA','active'=>true]]));
  StoreSetting::putValue('payment_options', StoreSetting::getValue('payment_options', ['payment_pro_enabled'=>false]));
  $admin=Role::firstOrCreate(['name'=>'admin'],['label'=>'Administrateur']);$manager=Role::firstOrCreate(['name'=>'manager'],['label'=>'Gestionnaire']);$client=Role::firstOrCreate(['name'=>'client'],['label'=>'Client']);
  $admin->permissions()->sync(collect($perms)->pluck('id'));
  $manager->permissions()->sync([$perms['access-admin']->id,$perms['manage-products']->id,$perms['manage-orders']->id]);
  $u=User::firstOrCreate(['email'=>'admin@CPC.local'],['name'=>'Administrateur CPC','password'=>Hash::make('ChangeMe123!')]);$u->roles()->syncWithoutDetaching([$admin->id]);
  foreach(['Nouveautés','Homme','Femme','Accessoires'] as $name)Category::firstOrCreate(['slug'=>\Illuminate\Support\Str::slug($name)],['name'=>$name,'is_active'=>true]);
  $finePermissions = [
   'products.view'=>'Voir les produits','products.create'=>'Créer les produits','products.update'=>'Modifier les produits','products.delete'=>'Supprimer les produits',
   'categories.view'=>'Voir les catégories','categories.create'=>'Créer les catégories','categories.update'=>'Modifier les catégories','categories.delete'=>'Supprimer les catégories',
   'orders.view'=>'Voir les commandes','orders.update'=>'Modifier les commandes','users.view'=>'Voir les utilisateurs','users.roles.update'=>'Modifier les rôles',
   'settings.view'=>'Voir les paramètres','settings.update'=>'Modifier les paramètres',
  ];
  foreach ($finePermissions as $name=>$label) { $perms[$name]=Permission::firstOrCreate(['name'=>$name],['label'=>$label]); }
  $admin->permissions()->syncWithoutDetaching(collect($perms)->pluck('id'));
  $manager->permissions()->syncWithoutDetaching([$perms['access-admin']->id,$perms['products.view']->id,$perms['products.create']->id,$perms['products.update']->id,$perms['categories.view']->id,$perms['orders.view']->id]);
  if(Product::count()===0){$cat=Category::where('slug','nouveautes')->first();foreach([
   ['Boubou Signature','Tenue élégante pour les grandes occasions.',35000,8],
   ['Ensemble Tradition','Une silhouette contemporaine inspirée des traditions.',28000,12],
   ['Robe Élégance','Une pièce raffinée pour vos événements.',32000,6],
   ['Accessoire Signature','La touche finale de votre tenue.',8500,20]
  ] as [$name,$desc,$price,$stock])Product::create(['category_id'=>$cat?->id,'name'=>$name,'slug'=>\Illuminate\Support\Str::slug($name),'short_description'=>$desc,'description'=>$desc,'price'=>$price,'stock'=>$stock,'is_active'=>true,'is_featured'=>true]);}
 }
}
