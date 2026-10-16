<!doctype html>
<html lang="fr">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title','Administration CPC')</title>
 <link rel="stylesheet" href="{{ asset('css/CPC.css') }}">
<style>:root{--ink:#073B91;--accent:#0756D8;--gold:#0756D8;--admin:#073B91} .admin-sidebar,.admin-topbar{background:#073B91!important;border-color:transparent!important}.admin-nav-link.active{border-color:transparent!important;background:rgba(255,255,255,.16)!important;color:#fff!important}</style></head>
<body class="admin-body">
 <aside class="admin-sidebar" id="adminSidebar">
  <a class="admin-brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/cpc-logo.png') }}" alt="CPC Compagnie"><span>ESPACE ADMIN</span></a>
  <p class="admin-label">PILOTAGE</p>
  <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="admin-nav-icon">▦</span><span>Tableau de bord</span></a>
  <p class="admin-label">GESTION DES SERVICES</p>
  <a class="admin-nav-link {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}" href="{{ route('admin.produits.index') }}"><span class="admin-nav-icon">◇</span><span>Produits</span></a>
  <a class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><span class="admin-nav-icon">▤</span><span>Catégories de Produits</span></a>
  <a class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><span class="admin-nav-icon">▣</span><span>Commandes</span></a>
  <a class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}"><span class="admin-nav-icon">⇄</span><span>Paramètres & paiements</span></a>
  <p class="admin-label">PERSONNALISATION DU SITE</p>
  <a class="admin-nav-link {{ request()->routeIs('admin.site.hero*') ? 'active' : '' }}" href="{{ route('admin.site.hero') }}"><span class="admin-nav-icon">▣</span><span>Hero : textes & images</span></a>
  <a class="admin-nav-link {{ request()->routeIs('admin.site.theme*') ? 'active' : '' }}" href="{{ route('admin.site.theme') }}"><span class="admin-nav-icon">◉</span><span>Couleurs du site</span></a>
  <a class="admin-nav-link {{ request()->routeIs('admin.site.ads*') ? 'active' : '' }}" href="{{ route('admin.site.ads') }}"><span class="admin-nav-icon">▧</span><span>Publicités</span></a>
  <p class="admin-label">ÉQUIPE & CLIENTS</p>
  <a class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><span class="admin-nav-icon">♙</span><span>Utilisateurs</span></a>
  <div class="admin-sidebar-bottom"><a class="admin-nav-link" href="{{ route('home') }}"><span class="admin-nav-icon">↗</span><span>Voir le site</span></a><div class="admin-user"><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</span><div><strong>{{ auth()->user()->name ?? 'Administrateur' }}</strong><small>Compte connecté</small></div></div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><span class="admin-nav-icon">⇥</span> Déconnexion</button></form></div>
 </aside>
 <main class="admin-main"><header class="admin-topbar"><button class="admin-mobile-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false">☰</button><div><span>CPC</span><b>/</b><span>@yield('breadcrumb','Administration')</span></div><span class="admin-topbar-status"><i></i> Espace sécurisé</span></header>
 @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
 @if(session('error'))<div class="flash error">{{ session('error') }}</div>@endif
 @if($errors->any())<div class="flash error">{{ $errors->first() }}</div>@endif
 @yield('content')
 </main>
 <script src="{{ asset('js/CPC.js') }}" defer></script>
</body></html>
