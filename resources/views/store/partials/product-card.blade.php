<article class="product-card">
 <a href="{{ route('product.show',$product) }}" class="product-image">
  @if($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" loading="lazy">@else <div class="product-placeholder"><span>CPC</span></div>@endif
  @if($product->sale_price)<span class="product-badge">OFFRE</span>@elseif($product->is_featured)<span class="product-badge">SÉLECTION</span>@endif
 </a>
 <div class="product-info"><p class="product-category">{{ $product->category?->name ?? 'Collection CPC' }}</p><h3><a href="{{ route('product.show',$product) }}">{{ $product->name }}</a></h3><div class="product-price">@if($product->sale_price)<del>{{ number_format($product->price,0,',',' ') }} FCFA</del>@endif <strong>{{ number_format($product->sale_price ?? $product->price,0,',',' ') }} FCFA</strong></div></div>
</article>
