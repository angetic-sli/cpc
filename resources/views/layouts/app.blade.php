<!doctype html>
<html lang="fr">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>@yield('title', 'CPC — Centrale Pluriel Connexion | Boutique en ligne')</title>
 <meta name="description" content="@yield('meta_description', 'CPC — Centrale Pluriel Connexion : découvrez nos produits et commandez en ligne.')">
 <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
 @php($siteTheme = \App\Models\StoreSetting::getValue('site_theme', ['primary'=>'#0756D8','secondary'=>'#073B91','background'=>'#F3F7FD','text'=>'#073B91','surface'=>'#FFFFFF']))
 <link rel="stylesheet" href="{{ asset('css/CPC.css') }}">
 <style>:root{--accent:{{ $siteTheme['primary'] ?? '#0756D8' }};--gold:{{ $siteTheme['primary'] ?? '#0756D8' }};--gold-light:{{ $siteTheme['primary'] ?? '#0756D8' }};--ink:{{ $siteTheme['secondary'] ?? '#073B91' }};--paper:{{ $siteTheme['background'] ?? '#F6F8FB' }};--white:{{ $siteTheme['surface'] ?? '#FFFFFF' }};--surface:{{ $siteTheme['surface'] ?? '#FFFFFF' }};--text:{{ $siteTheme['text'] ?? '#1E293B' }};--primary:{{ $siteTheme['primary'] ?? '#0756D8' }}}body{background:var(--paper);color:var(--text)}.slide-cta,.carousel-arrows button:hover{background:var(--primary)}.site-ad-banner{display:flex;align-items:center;gap:24px;margin:24px 3%;padding:18px 24px;background:var(--surface);border:1px solid rgba(0,0,0,.08);border-radius:24px;overflow:hidden}.site-ad-banner img{width:180px;height:100px;object-fit:cover;border-radius:16px}.site-ad-banner small{display:block;color:var(--primary);font-size:10px;letter-spacing:.18em}.site-ad-banner strong{display:block;font:26px var(--serif)}.site-ad-banner span{font-size:12px}@media(max-width:600px){.site-ad-banner{gap:12px;padding:12px}.site-ad-banner img{width:95px;height:85px}.site-ad-banner strong{font-size:18px}}</style>
 @stack('styles')
</head>
<body>
 <div class="announcement">CPC • Le bazar en ligne pour toutes vos envies • Mode, maison, high-tech et bien plus</div>
 <header class="site-header">
  <a class="brand brand-logo" href="{{ route('home') }}"><img src="{{ asset('images/cpc-logo.png') }}" alt="CPC — Centrale Pluriel Connexion"></a>
  <button class="menu-toggle" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
  <nav class="main-nav">
   <a href="{{ route('shop') }}">Boutique</a><a href="{{ route('home') }}#univers">Catégories</a><a href="{{ route('shop') }}?sort=latest">Nouveautés</a><a href="{{ route('shop') }}">Promotions</a>
  </nav>
  <div class="header-actions">
   <form action="{{ route('search') }}" class="search-form"><input name="q" placeholder="Que recherchez-vous ?" value="{{ request('q') }}" aria-label="Rechercher"><button aria-label="Rechercher">⌕</button></form>
   <a href="{{ route('cart.index') }}" class="icon-link">Panier <span class="cart-count">{{ array_sum(array_column(session('cart',[]),'quantity')) }}</span></a>
   @auth <a href="{{ route('account') }}" class="icon-link">Mon compte</a>@else <a href="{{ route('login') }}" class="icon-link">Connexion</a>@endauth
  </div>
 </header>
 <nav class="market-category-nav" aria-label="Catégories populaires">
  <a href="{{ route('shop') }}">Tous les produits</a>
  @foreach(\App\Models\Category::query()->orderBy('name')->limit(10)->get() as $navCategory)
   <a href="{{ route('category.show', $navCategory) }}">{{ $navCategory->name }}</a>
  @endforeach
  <a href="{{ route('shop') }}?sort=latest">Nouveautés</a>
 </nav>
 @if(request()->routeIs('shop','category.show'))
 @php($layoutAds = \App\Models\StoreSetting::getValue('site_ads', []))
 @foreach($layoutAds as $layoutAd)
  @if(($layoutAd['placement'] ?? '') === 'shop_top' && !empty($layoutAd['active']) && (empty($layoutAd['starts_at']) || now()->gte(\Carbon\Carbon::parse($layoutAd['starts_at']))) && (empty($layoutAd['ends_at']) || now()->lte(\Carbon\Carbon::parse($layoutAd['ends_at']))))
   <a class="site-ad-banner" href="{{ $layoutAd['url'] }}" target="_blank" rel="noopener sponsored">@if(!empty($layoutAd['image']))<img src="{{ str_starts_with($layoutAd['image'],'http') ? $layoutAd['image'] : \Illuminate\Support\Facades\Storage::url($layoutAd['image']) }}" alt="{{ $layoutAd['title'] }}">@endif<div><small>PUBLICITÉ</small><strong>{{ $layoutAd['title'] }}</strong><span>{{ $layoutAd['text'] ?? '' }} ↗</span></div></a>
  @endif
 @endforeach
 @endif
 @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
 @if(session('error'))<div class="flash error">{{ session('error') }}</div>@endif
 @if($errors->any())<div class="flash error">{{ $errors->first() }}</div>@endif
 <main>@yield('content')</main>
 <footer class="site-footer">
  <div class="footer-grid"><div><a class="brand brand-light brand-logo" href="{{ route('home') }}"><img src="{{ asset('images/cpc-logo.png') }}" alt="CPC — Centrale Pluriel Connexion"></a><p>Le bazar en ligne pour tous vos achats : mode, maison, électronique, beauté et bien plus.</p></div>
  <div><h4>Découvrir</h4><a href="{{ route('shop') }}">Boutique</a><a href="{{ route('home') }}#univers">Nos collections</a><a href="{{ route('home') }}#contact">Nous contacter</a></div>
  <div><h4>Votre compte</h4><a href="{{ route('account') }}">Mon compte</a><a href="{{ route('cart.index') }}">Mon panier</a><a href="{{ route('login') }}">Connexion</a></div>
  <div><h4>Contact</h4><p>Une question sur un article ou une commande ? Contactez-nous.</p><form class="newsletter" onsubmit="event.preventDefault(); alert('La newsletter sera activée après configuration du service email.');"><input type="email" required placeholder="Votre adresse email"><button type="submit">OK</button></form></div></div>
  <div class="footer-bottom"><span>© {{ date('Y') }} CPC. Tous droits réservés.</span><span>Mentions légales · Confidentialité · Conditions de vente</span></div>
 </footer>
 <script src="{{ asset('js/CPC.js') }}" defer></script>@stack('scripts')
</body></html>
