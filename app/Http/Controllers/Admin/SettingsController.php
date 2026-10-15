<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings', [
            'zones' => StoreSetting::getValue('shipping_zones', [
                ['name'=>'Abidjan','price'=>1500,'active'=>true,'min_days'=>1,'max_days'=>2],
                ['name'=>'Intérieur du pays','price'=>3000,'active'=>true,'min_days'=>2,'max_days'=>5],
            ]),
            'carriers' => StoreSetting::getValue('shipping_carriers', [['name'=>'Livraison SA’DJA','active'=>true,'extra_fee'=>0,'estimated_days'=>2]]),
            'shipping' => StoreSetting::getValue('shipping_options', ['enabled'=>true,'free_from'=>0,'handling_fee'=>0,'estimated_days'=>'1 à 5 jours ouvrés','instructions'=>'']),
        ]);
    }

    public function update(Request $request)
    {
        $data=$request->validate([
            'zones_json'=>'required|string|max:30000','carriers_json'=>'required|string|max:30000',
            'shipping_tracking_enabled'=>'nullable|boolean','shipping_enabled'=>'nullable|boolean',
            'free_shipping_threshold'=>'nullable|integer|min:0|max:100000000','handling_fee'=>'nullable|integer|min:0|max:100000000',
            'shipping_estimated_days'=>'required|string|max:120','shipping_instructions'=>'nullable|string|max:2000',
        ]);
        $zones=$this->decodeList($data['zones_json'],'zones_json','Les zones doivent être une liste JSON.');
        foreach($zones as $i=>$z){
            if(!is_array($z)||trim((string)($z['name']??''))===''||!is_numeric($z['price']??null)||(float)$z['price']<0) return back()->withErrors(['zones_json'=>'Chaque zone doit avoir un nom et un tarif positif ou nul (zone '.($i+1).').'])->withInput();
            $zones[$i]=['name'=>trim((string)$z['name']),'price'=>(int)round((float)$z['price']),'active'=>filter_var($z['active']??true,FILTER_VALIDATE_BOOLEAN),'min_days'=>max(0,(int)($z['min_days']??0)),'max_days'=>max(0,(int)($z['max_days']??0))];
        }
        $carriers=$this->decodeList($data['carriers_json'],'carriers_json','Les transporteurs doivent être une liste JSON.');
        foreach($carriers as $i=>$c){
            if(!is_array($c)||trim((string)($c['name']??''))===''||!is_numeric($c['extra_fee']??0)||(float)($c['extra_fee']??0)<0) return back()->withErrors(['carriers_json'=>'Chaque transporteur doit avoir un nom et un supplément positif ou nul (ligne '.($i+1).').'])->withInput();
            $carriers[$i]=['name'=>trim((string)$c['name']),'active'=>filter_var($c['active']??false,FILTER_VALIDATE_BOOLEAN),'extra_fee'=>(int)round((float)($c['extra_fee']??0)),'estimated_days'=>max(0,(int)($c['estimated_days']??0))];
        }
        StoreSetting::putValue('shipping_zones',array_values($zones));
        StoreSetting::putValue('shipping_carriers',array_values($carriers));
        StoreSetting::putValue('shipping_tracking_enabled',(bool)($data['shipping_tracking_enabled']??false));
        StoreSetting::putValue('shipping_options',['enabled'=>(bool)($data['shipping_enabled']??false),'free_from'=>(int)($data['free_shipping_threshold']??0),'handling_fee'=>(int)($data['handling_fee']??0),'estimated_days'=>trim($data['shipping_estimated_days']),'instructions'=>trim((string)($data['shipping_instructions']??''))]);
        return back()->with('success','Paramètres de livraison enregistrés.');
    }
    private function decodeList(string $json,string $field,string $message): array
    {
        $value=json_decode($json,true);
        if(!is_array($value)||!array_is_list($value)) throw ValidationException::withMessages([$field=>$message.' Exemple : [] ou une liste d’objets JSON.']);
        return $value;
    }
}
