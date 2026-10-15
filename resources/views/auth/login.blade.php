@extends('layouts.app')
@section('title','Connexion — CPC')
@section('content')
<section class="auth-wrap"><form method="POST" action="{{ route('login') }}" class="form-card auth-card">@csrf<p class="eyebrow">BON RETOUR</p><h1>Connexion</h1><p>Retrouvez vos commandes et vos informations.</p><label>Adresse email<input type="email" name="email" value="{{ old('email') }}" required autofocus></label><label>Mot de passe<input type="password" name="password" required></label><label class="check-label"><input type="checkbox" name="remember"> Se souvenir de moi</label><button class="btn btn-dark btn-full">Se connecter</button><p class="auth-bottom">Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p></form></section>
@endsection
