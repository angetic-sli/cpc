@extends('layouts.app')
@section('title',$product->name.' — CPC')
@section('content')
<div class="breadcrumb"><a href="{{ route('home') }}">Accueil</a> / <a href="{{ route('shop') }}">Tous les articles</a> / {{ $product->name }}</div>
<section class="product-detail"><div class="detail-image">@if($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">@else<div class="product-placeholder large"><span>CPC</span></div>@endif</div><div class="detail-copy"><p class="eyebrow">{{ $product->category?->name ?? 'BAZAR CPC' }}</p><h1>{{ $product->name }}</h1><div class="detail-price">@if($product->sale_price)<del>{{ number_format($product->price,0,',',' ') }} FCFA</del>@endif {{ number_format($product->sale_price ?? $product->price,0,',',' ') }} FCFA</div><p class="detail-short">{{ $product->short_description }}</p><div class="stock-note">{{ $product->stock>0?'Disponible':'Rupture de stock' }} @if($product->stock>0) · {{ $product->stock }} en stock @endif</div>
@if($product->stock>0)<form method="POST" action="{{ route('cart.add',$product) }}" class="add-cart-form">@csrf
@if(!empty($product->colors))<label>Couleur <select name="color" required><option value="">Choisir une couleur</option>@foreach($product->colors as $color)<option value="{{ $color }}">{{ $color }}</option>@endforeach</select></label>@endif
@if(!empty($product->sizes))<label>Taille <select name="size" required><option value="">Choisir une taille</option>@foreach($product->sizes as $size)<option value="{{ $size }}">{{ $size }}</option>@endforeach</select></label>@endif
<label>Quantité <input type="number" name="quantity" min="1" max="{{ min(20,$product->stock) }}" value="1"></label><button class="btn btn-dark" type="submit">Ajouter au panier ↗</button></form>@endif
<div class="detail-description"><h3>Description</h3><p>{{ $product->description ?: $product->short_description }}</p><p>Consultez les informations de l’article et les options de livraison avant de finaliser votre commande.</p></div></div></section>
@if($related->count())<section class="section"><div class="section-heading"><div><p class="eyebrow">À DÉCOUVRIR AUSSI</p><h2>Vous aimerez aussi</h2></div></div><div class="product-grid">@foreach($related as $item) @include('store.partials.product-card',['product'=>$item]) @endforeach</div></section>@endif
@endsection
