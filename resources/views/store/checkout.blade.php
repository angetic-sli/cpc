@extends('layouts.app')
@section('title','Finaliser la commande — CPC')
@section('content')
<section class="page-hero"><p class="eyebrow">DERNIÈRE ÉTAPE</p><h1>Finaliser la commande</h1></section>
<section class="section checkout-wrap"><form method="POST" action="{{ route('checkout.store') }}" class="form-card">@csrf
<h2>Informations de livraison</h2>
<div class="form-grid">
<label>Nom complet<input name="customer_name" value="{{ old('customer_name',auth()->user()->name) }}" required></label>
<label>Email<input type="email" name="customer_email" value="{{ old('customer_email',auth()->user()->email) }}" required></label>
<label>Téléphone<input name="customer_phone" value="{{ old('customer_phone',auth()->user()->phone) }}" required></label>
<label class="full-field">Adresse de livraison<textarea name="shipping_address" required>{{ old('shipping_address',auth()->user()->address) }}</textarea></label>
<label>Zone de livraison<select name="shipping_zone" required><option value="">Choisir une zone</option>@foreach(\App\Models\StoreSetting::getValue('shipping_zones',[]) as $zone)@if(!empty($zone['active']))<option value="{{ $zone['name'] }}" @selected(old('shipping_zone')===$zone['name'])>{{ $zone['name'] }} — {{ number_format((float)$zone['price'],0,',',' ') }} FCFA @if(!empty($zone['min_days'])||!empty($zone['max_days']) ) ({{ $zone['min_days']??0 }}-{{ $zone['max_days']??0 }} jours) @endif</option>@endif @endforeach</select></label>
<label>Transporteur<select name="carrier"><option value="">Choisir selon disponibilité</option>@foreach(\App\Models\StoreSetting::getValue('shipping_carriers',[]) as $carrier)@if(!empty($carrier['active']))<option value="{{ $carrier['name'] }}" @selected(old('carrier')===$carrier['name'])>{{ $carrier['name'] }}@if(!empty($carrier['extra_fee'])) — supplément {{ number_format((float)$carrier['extra_fee'],0,',',' ') }} FCFA @endif</option>@endif @endforeach</select></label>
<label>Mode de paiement<select name="payment_method" required><option value="cash_on_delivery" @selected(old('payment_method','cash_on_delivery')==='cash_on_delivery')>Paiement à la livraison</option><option value="bank_transfer" @selected(old('payment_method')==='bank_transfer')>Virement bancaire</option></select></label>
<label class="full-field">Note de commande<textarea name="notes">{{ old('notes') }}</textarea></label>
</div>
@if(!empty($shippingOptions['free_from']))<p class="notice">Livraison gratuite à partir de {{ number_format((float)$shippingOptions['free_from'],0,',',' ') }} FCFA d’achats.</p>@endif
@if(!empty($shippingOptions['instructions']))<p class="muted">{{ $shippingOptions['instructions'] }}</p>@endif
<p class="notice">Les frais sont calculés selon la zone, le transporteur et les paramètres de la boutique. Aucun paiement en ligne n’est proposé. Le règlement se fait à la livraison ou par virement bancaire.</p>
<button class="btn btn-dark" type="submit">Confirmer la commande ↗</button>
</form></section>

@endsection
