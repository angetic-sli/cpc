@extends('admin.layout')
@section('title','Catégories — CPC')
@section('content')
<div class="admin-heading"><div><p class="eyebrow">ORGANISATION</p><h1>Catégories</h1></div><a href="{{ route('admin.categories.create') }}" class="btn btn-dark">+ Ajouter une catégorie</a></div>
<div class="admin-panel"><div class="table-scroll"><table><thead><tr><th>Nom</th><th>Slug</th><th>Produits</th><th>Statut</th><th>Actions</th></tr></thead><tbody>@forelse($categories as $c)<tr><td>{{ $c->name }}</td><td>{{ $c->slug }}</td><td>{{ $c->products_count }}</td><td>{{ $c->is_active?'Active':'Masquée' }}</td><td class="actions"><a href="{{ route('admin.categories.edit',$c) }}">Modifier</a><form method="POST" action="{{ route('admin.categories.destroy',$c) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">@csrf @method('DELETE')<button>Supprimer</button></form></td></tr>@empty<tr><td colspan="5">Aucune catégorie.</td></tr>@endforelse</tbody></table></div>{{ $categories->links() }}</div>
@endsection
