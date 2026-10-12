# Déployer Saint-Joseph gratuitement sur Render

Cette application est une application **Laravel 12 / PHP 8.4** avec assets Vite. Le dépôt contient un `Dockerfile` et un `render.yaml` configurés pour un **seul service web Render Free**, sans worker payant. La base PostgreSQL et le stockage des fichiers sont prévus sur Supabase.

## 1. Créer le projet Supabase

1. Créer un projet Supabase avec le forfait gratuit.
2. Dans **Project Settings → Database**, récupérer les paramètres PostgreSQL. Utiliser le Session Pooler si la connexion directe ne fonctionne pas depuis Render.
3. Créer un bucket Storage et récupérer les paramètres S3 (endpoint, Access Key ID et Secret Access Key).
4. Ne jamais enregistrer les mots de passe ou clés dans GitHub.

Attention : les limites et règles du forfait gratuit Supabase peuvent évoluer. Vérifier les limites affichées dans le tableau de bord Supabase.

## 2. Créer le service Render

1. Ouvrir https://dashboard.render.com/.
2. Choisir **New → Blueprint** et connecter `bossombra1/Saint-Joseph`.
3. Sélectionner la branche `deploy/free-render` pour tester cette configuration, puis utiliser `render.yaml`.
4. Vérifier que le Blueprint ne propose qu'un seul service web nommé `saint-joseph-artisan` et que son plan est `free`.
5. Ne pas ajouter de carte bancaire. Si Render demande toujours un moyen de paiement, annuler et vérifier que la branche et le fichier `render.yaml` sélectionnés sont bien ceux-ci.

Le conteneur écoute sur le port `10000`; le health check utilise `/up`.

## 3. Renseigner les variables

Dans Render, renseigner les variables marquées comme non synchronisées (`sync: false`) pour le service web.

### Base de données

- `DB_CONNECTION=pgsql`
- `DB_HOST` : hôte fourni par Supabase
- `DB_PORT=5432`, ou le port indiqué par le pooler
- `DB_DATABASE` : généralement `postgres`
- `DB_USERNAME` et `DB_PASSWORD` : identifiants fournis par Supabase
- `DB_SSLMODE=require`

Le conteneur exécute `php artisan migrate --force` au démarrage. Vérifier que le projet Supabase choisi est bien la base cible avant le premier démarrage.

### URL et stockage

- `APP_URL` : URL HTTPS exacte attribuée par Render
- `SUPABASE_URL` : URL du projet
- `SUPABASE_BUCKET` : nom du bucket
- `SUPABASE_ENDPOINT` : endpoint S3 de Supabase Storage
- `SUPABASE_ACCESS_KEY_ID` et `SUPABASE_SECRET_ACCESS_KEY` : identifiants S3
- `SUPABASE_SERVICE_KEY` : uniquement si les fonctionnalités de l'application en ont besoin

Les fichiers locaux Render sont éphémères : les téléversements doivent bien utiliser le stockage Supabase. Configurer le bucket en public uniquement si les images doivent être visibles sans authentification.

### E-mails et intégrations facultatives

Pour les e-mails, renseigner les variables `MAIL_*` avec les paramètres du fournisseur. Les clés `PAYMONGO_*`, `GROQ_API_KEY` et `OPENROUTER_API_KEY` ne sont nécessaires que si les intégrations correspondantes sont utilisées.

## 4. Limites de la version gratuite

- Le service web Render Free s'endort après 15 minutes sans trafic et peut prendre environ une minute à redémarrer.
- Le système de fichiers local est éphémère : ne pas y conserver les fichiers téléversés ou une base SQLite.
- La file d'attente Laravel est configurée sur `sync` afin de ne pas nécessiter de worker séparé. Les tâches sont exécutées pendant la requête web et peuvent donc ralentir certaines pages.
- Le service gratuit convient aux essais et à un petit site, mais n'offre pas les garanties de disponibilité d'un hébergement payant.

## 5. Vérification

Après le déploiement, vérifier les logs, le health check `/up`, la page d'accueil, la connexion à l'administration, les formulaires, les e-mails et le téléversement d'images. Le site n'est pas considéré comme déployé tant que Render n'affiche pas un déploiement réussi et que l'URL n'a pas été testée.
