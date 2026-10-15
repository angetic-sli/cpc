@extends('admin.layout')
@section('title','Couleurs du site — CPC')
@section('breadcrumb','Couleurs du site')
@section('content')
<div class="admin-page-heading"><div><p class="eyebrow">IDENTITÉ VISUELLE</p><h1>Couleurs du site</h1><p>Ces couleurs sont appliquées globalement à la boutique publique.</p></div></div>
<form method="POST" action="{{ route('admin.site.theme.save') }}" class="admin-panel admin-form" style="padding:28px;max-width:900px">@csrf @method('PUT')<div class="form-grid">
@foreach(['primary'=>'Couleur principale (boutons, accents)','secondary'=>'Couleur foncée (en-tête, fonds foncés)','background'=>'Fond général du site','text'=>'Couleur des textes','surface'=>'Fond des cartes et panneaux'] as $key=>$label)<label>{{ $label }}<div style="display:flex;gap:12px;align-items:center;margin-top:8px"><input type="color" name="{{ $key }}" value="{{ $theme[$key] ?? '#FFFFFF' }}" style="width:64px;height:48px;padding:3px"><input class="color-hex" value="{{ $theme[$key] ?? '#FFFFFF' }}" aria-label="Code hexadécimal" readonly></div></label>@endforeach
</div><div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:25px"><button class="btn btn-dark" type="submit">Appliquer les couleurs</button><a class="btn btn-outline" href="{{ route('home') }}" target="_blank">Voir la boutique ↗</a></div></form>
@endsection
@push('scripts')<script>document.querySelectorAll('input[type=color][name]').forEach(el=>{const hex=el.parentElement.querySelector('.color-hex');el.addEventListener('input',()=>hex.value=el.value.toUpperCase())});</script>@endpush
