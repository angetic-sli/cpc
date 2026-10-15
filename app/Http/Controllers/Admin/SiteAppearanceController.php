<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteAppearanceController extends Controller
{
    private function defaults(): array { return [
      ['kicker'=>'SA’DJA COMPAGNIE · COLLECTION','title'=>'Le style est une','highlight'=>'signature.','description'=>'Des pièces élégantes, des détails soignés et une allure qui vous ressemble.','button'=>'Explorer la boutique','url'=>'/boutique','footnote'=>'01 / MODE & STYLE','image'=>'https://images.unsplash.com/photo-1590736969955-71cc94901144?auto=format&fit=crop&w=2200&q=90','active'=>true],
      ['kicker'=>'CRÉATION SUR MESURE','title'=>'Votre idée.','highlight'=>'Notre savoir-faire.','description'=>'Une coupe précise, une finition remarquable et une création pensée autour de vous.','button'=>'Découvrir nos créations','url'=>'/boutique','footnote'=>'02 / COUTURE & CRÉATION','image'=>'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=2200&q=85','active'=>true],
      ['kicker'=>'PERSONNALISATION & DÉTAILS','title'=>'Faites de chaque','highlight'=>'détail une marque.','description'=>'Couture, broderie et impression : donnez vie à vos projets avec SA’DJA.','button'=>'Parlons de votre projet','url'=>'/boutique','footnote'=>'03 / BRODERIE & IMPRESSION','image'=>'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=2200&q=85','active'=>true],
    ]; }
    public function hero() { return view('admin.site.hero',['slides'=>StoreSetting::getValue('hero_slides',$this->defaults())]); }
    public function saveHero(Request $request) {
      $data=$request->validate(['slides'=>'required|array|min:1|max:6','slides.*.kicker'=>'nullable|string|max:120','slides.*.title'=>'required|string|max:100','slides.*.highlight'=>'required|string|max:100','slides.*.description'=>'nullable|string|max:300','slides.*.button'=>'required|string|max:70','slides.*.url'=>'required|string|max:500','slides.*.footnote'=>'nullable|string|max:100','slides.*.active'=>'nullable|boolean','slides.*.image_file'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120','slides.*.image'=>'nullable|string|max:1000']);
      $old=StoreSetting::getValue('hero_slides',$this->defaults());$slides=[];
      foreach($data['slides'] as $i=>$s){$image=$s['image']??($old[$i]['image']??'');if($request->hasFile("slides.$i.image_file"))$image=Storage::disk('public')->putFile('sadja/hero',$request->file("slides.$i.image_file"));$slides[]=['kicker'=>$s['kicker']??'','title'=>trim($s['title']),'highlight'=>trim($s['highlight']),'description'=>$s['description']??'','button'=>$s['button'],'url'=>$s['url'],'footnote'=>$s['footnote']??'','image'=>$image,'active'=>isset($s['active'])];}
      StoreSetting::putValue('hero_slides',$slides);return back()->with('success','Les textes et images du hero ont été enregistrés.');
    }
    public function theme() { return view('admin.site.theme',['theme'=>StoreSetting::getValue('site_theme',['primary'=>'#C89424','secondary'=>'#171717','background'=>'#FAF8F4','text'=>'#20231F','surface'=>'#FFFFFF'])]); }
    public function saveTheme(Request $request) { $d=$request->validate(['primary'=>'required|regex:/^#[0-9A-Fa-f]{6}$/','secondary'=>'required|regex:/^#[0-9A-Fa-f]{6}$/','background'=>'required|regex:/^#[0-9A-Fa-f]{6}$/','text'=>'required|regex:/^#[0-9A-Fa-f]{6}$/','surface'=>'required|regex:/^#[0-9A-Fa-f]{6}$/']);StoreSetting::putValue('site_theme',$d);return back()->with('success','Les couleurs globales du site ont été mises à jour.'); }
    public function ads() { return view('admin.site.ads',['ads'=>StoreSetting::getValue('site_ads',[])]); }
    public function saveAds(Request $request) { $d=$request->validate(['ads'=>'nullable|array|max:20','ads.*.title'=>'required|string|max:120','ads.*.text'=>'nullable|string|max:300','ads.*.url'=>'required|string|max:500','ads.*.placement'=>'required|in:home_top,home_middle,shop_top','ads.*.starts_at'=>'nullable|date','ads.*.ends_at'=>'nullable|date|after_or_equal:ads.*.starts_at','ads.*.active'=>'nullable|boolean','ads.*.image'=>'nullable|string|max:1000','ads.*.image_file'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120']);$old=StoreSetting::getValue('site_ads',[]);$ads=[];foreach(($d['ads']??[]) as $i=>$a){$image=$a['image']??($old[$i]['image']??'');if($request->hasFile("ads.$i.image_file"))$image=Storage::disk('public')->putFile('sadja/ads',$request->file("ads.$i.image_file"));$ads[]=['title'=>$a['title'],'text'=>$a['text']??'','url'=>$a['url'],'placement'=>$a['placement'],'starts_at'=>$a['starts_at']??null,'ends_at'=>$a['ends_at']??null,'active'=>isset($a['active']),'image'=>$image];}StoreSetting::putValue('site_ads',$ads);return back()->with('success','Les campagnes publicitaires ont été enregistrées.'); }
}
