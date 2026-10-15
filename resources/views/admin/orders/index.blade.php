@extends('admin.layout')
@section('title','Commandes — CPC')
@section('content')
<div class="admin-heading"><div><p class="eyebrow">VENTES</p><h1>Commandes</h1></div></div><div class="admin-panel"><div class="table-scroll"><table><thead><tr><th>Référence</th><th>Client</th><th>Date</th><th>Total</th><th>Commande</th><th>Paiement</th><th></th></tr></thead><tbody>@forelse($orders as $o)<tr><td><strong>{{ $o->number }}</strong></td><td>{{ $o->customer_name }}<small class="table-sub">{{ $o->customer_email }}</small></td><td>{{ $o->created_at->format('d/m/Y') }}</td><td>{{ number_format($o->total,0,',',' ') }} FCFA</td><td>{{ $o->status }}</td><td>{{ $o->payment_status }}</td><td><a href="{{ route('admin.orders.show',$o) }}">Gérer ↗</a></td></tr>@empty<tr><td colspan="7">Aucune commande enregistrée.</td></tr>@endforelse</tbody></table></div>{{ $orders->links() }}</div>
@endsection
