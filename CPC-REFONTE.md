# CPC – Centrale Pluriel Connexion

## Modifications incluses
- Identité graphique CPC : bleu profond `#17365D`, or doux `#D9A441`, blanc et gris clair.
- Logo SVG léger sans symbole d’émeraude.
- Mise à jour des libellés et métadonnées visibles dans les vues Blade.
- Harmonisation des styles du site public et de l’administration.
- Conservation des routes, contrôleurs, modèles, migrations et mécanismes métier existants.
- Les secrets `.env`, la base SQLite locale, `vendor/` et `node_modules/` ont été retirés de l’archive livrée.

## Installation
1. Installer PHP compatible avec les contraintes de `composer.json`, Composer, Node.js et npm.
2. Copier `.env.example` vers `.env` et renseigner les paramètres de base de données, mail et paiement.
3. Exécuter `composer install`.
4. Exécuter `php artisan key:generate`.
5. Créer une base de données vide puis lancer `php artisan migrate --seed` si les seeders du projet sont appropriés à votre environnement.
6. Exécuter `npm install` puis `npm run build`.
7. Exécuter `php artisan storage:link` puis configurer le serveur web sur le dossier `public/`.

## Important
Cette archive contient la refonte visuelle et les sources existantes. Les identifiants de paiement et autres secrets doivent être configurés localement. Les flux métier et paiements doivent être testés en environnement de recette avant mise en production.


## Paiements
Le paiement en ligne et toute intégration Paiement Pro ont été supprimés. Le checkout propose uniquement le paiement à la livraison et le virement bancaire. Aucune redirection vers un prestataire de paiement ni route webhook de paiement n’est conservée.


## Boutique CPC et règlements
Le site reste une boutique e-commerce : catalogue, fiches produits, panier et commande sont conservés. Le paiement en ligne et l’intégration Paiement Pro ne sont pas utilisés. Les règlements sont effectués à la livraison ou par virement bancaire, selon les options configurées. Le logo CPC fourni est utilisé sur le site public et dans l’administration. Palette principale : bleu profond #17365D, or #D9A441, blanc et gris clair.


## Variantes de produits : couleurs et tailles
Une migration ajoute les champs `colors` et `sizes` aux produits. Après déploiement, exécuter `php artisan migrate`. Dans l’administration, renseigner les couleurs et tailles séparées par des virgules (ex. `Noir, Blanc, Bleu` et `S, M, L, XL`). Les choix sont demandés sur la fiche produit et récapitulés dans le panier.


## Refonte visuelle CPC Bazar
Interface publique modernisée avec une navigation e-commerce responsive, une recherche visible, des catégories en cartes, une grille produit et une palette bleu CPC/or. Le logo CPC fourni est utilisé dans l'en-tête et le pied de page. Les textes et la structure restent gérés par les vues Laravel existantes.
