@extends('admin.layout')
@section('title','Paramètres de livraison')
@section('breadcrumb','Paramètres de livraison')
@section('content')
<div class="page-head"><div><p class="eyebrow">CONFIGURATION DE LA BOUTIQUE</p><h1>Paramètres de livraison</h1><p>Gérez les zones, tarifs, transporteurs et délais de livraison.</p></div></div>
<form method="POST" action="{{ route('admin.settings.update') }}" class="form-card">@csrf @method('PUT')
<h2>1. Paramètres généraux de livraison</h2>
<label class="check-row"><input type="checkbox" name="shipping_enabled" value="1" @checked(old('shipping_enabled',$shipping['enabled']??true))> Activer la livraison sur la boutique</label>
<div class="form-grid">
<label>Seuil de livraison gratuite (FCFA, 0 = désactivé)<input type="number" min="0" step="1" name="free_shipping_threshold" value="{{ old('free_shipping_threshold',$shipping['free_from']??0) }}"></label>
<label>Frais de préparation/emballage (FCFA)<input type="number" min="0" step="1" name="handling_fee" value="{{ old('handling_fee',$shipping['handling_fee']??0) }}"></label>
<label class="full-field">Délai global indicatif<input name="shipping_estimated_days" maxlength="120" required value="{{ old('shipping_estimated_days',$shipping['estimated_days']??'1 à 5 jours ouvrés') }}"></label>
<label class="full-field">Instructions de livraison<textarea name="shipping_instructions" rows="3">{{ old('shipping_instructions',$shipping['instructions']??'') }}</textarea></label>
</div>
<label>Zones de livraison et tarifs (JSON)<textarea name="zones_json" rows="9" required>{{ old('zones_json',json_encode($zones,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)) }}</textarea></label>
<p class="muted">Champs d’une zone : name, price, active, min_days, max_days. Vous pouvez ajouter autant de zones que nécessaire.</p>
<label>Transporteurs et options (JSON)<textarea name="carriers_json" rows="7" required>{{ old('carriers_json',json_encode($carriers,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)) }}</textarea></label>
<p class="muted">Champs d’un transporteur : name, active, extra_fee, estimated_days. Le supplément est ajouté au tarif de la zone.</p>
<label class="check-row"><input type="checkbox" name="shipping_tracking_enabled" value="1" @checked(old('shipping_tracking_enabled',\App\Models\StoreSetting::getValue('shipping_tracking_enabled',false)))> Activer le suivi de livraison / numéro de suivi</label>
<button class="btn btn-dark" type="submit">Enregistrer les paramètres</button></form>
@endsection
