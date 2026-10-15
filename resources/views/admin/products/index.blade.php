@extends('admin.layout')
@section('title','Produits — CPC')
@section('content')
<div class="admin-heading"><div><p class="eyebrow">CATALOGUE</p><h1>Produits</h1></div><a href="{{ route('admin.produits.create') }}" class="btn btn-dark">+ Ajouter un produit</a></div>
<div class="admin-panel"><div class="table-scroll"><table><thead><tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Visibilité</th><th>Actions</th></tr></thead><tbody>@forelse($products as $p)<tr><td><strong>{{ $p->name }}</strong><small class="table-sub">{{ $p->sku }}</small></td><td>{{ $p->category?->name ?? '—' }}</td><td>{{ number_format($p->sale_price??$p->price,0,',',' ') }} FCFA</td><td>{{ $p->stock }}</td><td>{{ $p->is_active?'Actif':'Masqué' }}</td><td class="actions"><a href="{{ route('admin.produits.edit',$p) }}">Modifier</a><form method="POST" action="{{ route('admin.produits.destroy',$p) }}" onsubmit="return confirm('Supprimer ce produit ?')">@csrf @method('DELETE')<button>Supprimer</button></form></td></tr>@empty<tr><td colspan="6">Aucun produit.</td></tr>@endforelse</tbody></table></div>{{ $products->links() }}</div>
@endsection
