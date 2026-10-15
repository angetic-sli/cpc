@extends('layouts.app')
@section('title','Créer un compte — CPC')
@section('content')
<section class="auth-wrap"><form method="POST" action="{{ route('register') }}" class="form-card auth-card">@csrf<p class="eyebrow">REJOIGNEZ CPC</p><h1>Créer un compte</h1><label>Nom complet<input name="name" value="{{ old('name') }}" required></label><label>Email<input type="email" name="email" value="{{ old('email') }}" required></label><label>Téléphone<input name="phone" value="{{ old('phone') }}"></label><label>Mot de passe<input type="password" name="password" required></label><label>Confirmer le mot de passe<input type="password" name="password_confirmation" required></label><button class="btn btn-dark btn-full">Nous contacter</button><p class="auth-bottom">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p></form></section>
@endsection
