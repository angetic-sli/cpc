# SADJA — plateforme e-commerce Laravel

Projet de démarrage Laravel pour une boutique SADJA, avec une interface boutique inspirée de l'architecture visuelle du site de référence (grandes images, navigation par univers, fiches produits, panier, compte client) et un back-office de gestion.

> Important : l'archive de référence fournie est un miroir HTML HTTrack, pas une sauvegarde WordPress. Ce projet reconstruit les fonctionnalités dans Laravel ; il ne reprend pas le backend WooCommerce et ne peut pas importer automatiquement commandes, comptes ou paiements de l'ancien site.

## Stack
- Laravel 12 / PHP 8.2+
- Blade, CSS et JavaScript vanilla
- MySQL/MariaDB
- Authentification Laravel (sessions)
- Autorisations via rôles et permissions stockés en base
- Panier en session, commandes, gestion des produits/catégories, utilisateurs et tableau de bord

## Installation
1. Installer PHP 8.2+, Composer, Node.js et MySQL.
2. Créer une application Laravel :
   ```bash
   composer create-project laravel/laravel sadja
   ```
3. Copier le contenu de cette archive dans la racine du projet Laravel en fusionnant les dossiers.
4. Configurer `.env` :
   ```env
   APP_NAME=SADJA
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sadja
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Installer les dépendances frontend et générer la clé :
   ```bash
   composer install
   php artisan key:generate
   npm install
   npm run build
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```
6. Ouvrir `http://127.0.0.1:8000`.

## Compte administrateur de démonstration
Le seeder crée :
- Email : `admin@sadja.local`
- Mot de passe : `ChangeMe123!`

**Changez immédiatement ce mot de passe avant toute mise en production.** Le compte de démonstration ne doit pas être utilisé sur un serveur public.

## Fonctionnalités incluses
- Accueil boutique, liste des produits, catégories, recherche, page produit
- Panier en session, modification des quantités et validation de commande
- Création de compte, connexion, déconnexion et espace client
- Historique des commandes client
- Back-office : dashboard, produits, catégories, commandes, utilisateurs
- Rôles et permissions en base (Admin, Gestionnaire, Client)
- Téléversement d'images produits via `storage`
- Validation serveur, protection CSRF, middleware d'autorisation
- Prix en FCFA par défaut, adaptable dans `.env`/config

## À compléter avant mise en production
- Connecter un prestataire de paiement (CinetPay, PayDunya, Stripe ou autre) et vérifier les webhooks.
- Configurer SMTP, politique de confidentialité, CGV, retours et livraison.
- Ajouter tests automatisés, journalisation/monitoring, sauvegardes et limitation de débit.
- Revoir le branding, les visuels et les textes SADJA. Les images de référence ne sont pas copiées automatiquement.
- Vérifier la compatibilité de toutes les routes avec la version Laravel installée.

## Structure
- `app/Models` : produits, catégories, commandes, rôles et permissions
- `app/Http/Controllers` : boutique, panier, compte, administration
- `database/migrations` et `database/seeders`
- `resources/views` : Blade storefront et back-office
- `public/css/sadja.css`, `public/js/sadja.js`

## Mise à jour : paiement, livraison et sécurité

### Paiement Pro
- Paramètres d'environnement ajoutés dans `.env.example` : `PAYMENT_PRO_ENABLED`, `PAYMENT_PRO_BASE_URL`, `PAYMENT_PRO_API_KEY`, `PAYMENT_PRO_CREATE_PATH`, `PAYMENT_PRO_CURRENCY`, `PAYMENT_PRO_CHECKOUT_URL_PATH`, `PAYMENT_PRO_REFERENCE_PATH`.
- `App\Services\PaymentProService` est un adaptateur initial. **Il doit être aligné sur la documentation API officielle Paiement Pro** (authentification, payload, réponse, signature et webhook) avant d'activer le paiement en production. Ne pas considérer le retour navigateur comme une preuve de paiement. La confirmation doit être faite côté serveur par callback signé ou vérification API.
- Garder les clés privées uniquement dans `.env`; ne jamais les exposer au navigateur ou au dépôt Git.

### Livraison
- Administration : `/admin/parametres` (permissions `settings.view` et `settings.update`).
- Les zones et tarifs sont configurés en JSON, par exemple :
  `[{"name":"Abidjan","price":1500},{"name":"Intérieur du pays","price":3000}]`
- Les transporteurs sont configurés en JSON, par exemple :
  `[{"name":"Livraison SA’DJA","active":true},{"name":"Partenaire","active":false}]`
- Les commandes stockent zone, transporteur et numéro de suivi. Ajouter le numéro de suivi depuis l'écran de commande nécessite encore un formulaire dédié si le suivi doit être modifiable par l'administration.

### Autorisations
Les permissions fines ont été ajoutées au seeder pour produits, catégories, commandes, utilisateurs et paramètres. Exécuter `php artisan db:seed` pour les synchroniser. Vérifier ensuite chaque rôle avec des comptes de test; l'administrateur reçoit toutes les permissions du seeder, le gestionnaire un sous-ensemble.

### Vérifications avant production
1. `composer install`, copier `.env.example` vers `.env`, puis `php artisan key:generate`.
2. Configurer la base de données puis `php artisan migrate --seed`.
3. `php artisan route:list` et `php artisan test`.
4. Tester ajout panier, quantité supérieure au stock, commande sans stock, frais de livraison, permissions 403, formulaires invalides, et paiement en mode sandbox.
5. Le paiement réel ne doit être activé qu'après adaptation du contrat API Paiement Pro et test du webhook signé. Les tests d'intégration n'ont pas pu être exécutés ici car le projet est un scaffold de fichiers Laravel et ne contient pas l'installation complète de Composer.

## Livraison et Paiement Pro (intégration WebService v2)

### Livraison
- Les zones et tarifs sont configurables dans `Admin > Paramètres boutique` sous forme de liste JSON, par exemple :
  `[{"name":"Abidjan","price":1500,"active":true},{"name":"Intérieur du pays","price":3000,"active":true}]`.
- Les transporteurs actifs sont configurables dans la même page :
  `[{"name":"Livraison SA’DJA","active":true},{"name":"Transporteur partenaire","active":false}]`.
- L'administration de chaque commande permet de renseigner le transporteur, le statut et le numéro de suivi. Le client voit ces informations dans son espace.

### Paiement Pro
L'intégration utilise le endpoint JSON documenté par Paiement Pro :
`https://sandbox.paiementpro.net/webservice/onlinepayment/init/curl-init.php`.
Configurez dans `.env` les variables `PAYMENT_PRO_ENABLED`, `PAYMENT_PRO_MERCHANT_ID`, `PAYMENT_PRO_INIT_URL`, `PAYMENT_PRO_CURRENCY_CODE=952` et, après confirmation du fournisseur, les variables `PAYMENT_PRO_WEBHOOK_SECRET` et `PAYMENT_PRO_HASH_ALGORITHM`. Laissez `PAYMENT_PRO_CHANNEL` vide pour permettre à Paiement Pro de proposer le choix du canal si le compte marchand le permet.

**Important sécurité :** la redirection du navigateur ne valide jamais un paiement. Le webhook vérifie référence, montant, identifiant marchand et hash avant de modifier le statut. La page publique de documentation Paiement Pro indique qu'un `hashcode` est requis mais ne spécifie pas la formule de signature. Le vérificateur livré utilise HMAC sur les champs scalaires triés (SHA-256 par défaut) et échoue fermé. Demandez à Paiement Pro de confirmer par écrit la formule de signature et adaptez `PaymentProService::verifyNotification()` si leur formule diffère avant toute mise en production. Ne basculez pas cette vérification en mode permissif.

### Vérifications recommandées
1. Utiliser d'abord le compte marchand et l'URL sandbox.
2. Tester l'initialisation avec une référence unique, la redirection et une notification signée valide.
3. Tester une signature invalide, un montant modifié, une référence inconnue et une notification dupliquée : aucun de ces cas ne doit marquer la commande comme payée.
4. Tester les zones inactives, les tarifs, les transporteurs désactivés et le suivi côté client.
5. Exécuter `php artisan migrate --seed`, `php artisan route:list`, `php artisan test` et vérifier les logs Laravel.


## Paramétrage administrateur de la livraison et des canaux Paiement Pro

Depuis la sidebar d'administration, ouvrir **Livraison & paiements**. L'écran permet de configurer :
- activation/désactivation de la livraison, seuil de livraison gratuite, frais de préparation, délai global et instructions;
- autant de zones que nécessaire, leur tarif, activation et délai indicatif;
- transporteurs, activation, supplément tarifaire et délai estimé;
- activation du suivi et saisie du numéro de suivi depuis le détail d'une commande;
- activation du paiement en ligne et liste des moyens de paiement visibles au checkout, avec code fournisseur et libellé.

Les listes des zones, transporteurs et canaux sont éditables en JSON dans l'écran d'administration. Les codes de canaux doivent correspondre exactement à ceux activés pour votre compte marchand Paiement Pro. Les exemples inclus sont `OMCIV2`, `MOMOCI`, `WAVECI` et `CARD`; ils doivent être confirmés dans votre espace marchand.

Au checkout, le client choisit son canal (par exemple Orange Money, MTN, Wave ou carte bancaire). Le serveur transmet ce canal dans le champ `channel` de l'API JSON Paiement Pro et redirige le client vers l'URL sécurisée retournée par Paiement Pro. Les frais sont recalculés côté serveur à partir du tarif de la zone, du supplément du transporteur, des frais de préparation et du seuil de livraison gratuite.

**Avant mise en production :** renseignez l'endpoint de production Paiement Pro dans `PAYMENT_PRO_INIT_URL` après validation du compte marchand, puis faites un test réel à faible montant. La documentation publique du fournisseur décrit le champ `hashcode` du callback mais ne publie pas sa formule de signature. La vérification livrée échoue volontairement si le hash n'est pas vérifiable. Demandez à Paiement Pro la formule exacte ou son mécanisme de vérification serveur et adaptez `PaymentProService::verifyNotification()` avant d'activer les paiements réels. Le retour navigateur seul ne doit jamais confirmer une transaction.
