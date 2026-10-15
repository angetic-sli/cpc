@extends('admin.layout')
@section('title','Catégorie — CPC')
@section('content')
<div class="admin-heading"><div><p class="eyebrow">ORGANISATION</p><h1>{{ isset($category)?'Modifier la catégorie':'Nouvelle catégorie' }}</h1></div></div>
<form method="POST" action="{{ isset($category)?route('admin.categories.update',$category):route('admin.categories.store') }}" class="admin-panel admin-form">@csrf @if(isset($category))@method('PUT')@endif<label>Nom *<input name="name" required value="{{ old('name',$category->name??'') }}"></label><label>Description<textarea name="description">{{ old('description',$category->description??'') }}</textarea></label><label class="check-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$category->is_active??true))> Catégorie active</label><button class="btn btn-dark">Enregistrer</button></form>
@endsection
