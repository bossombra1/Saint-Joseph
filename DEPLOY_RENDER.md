# Déployer Saint-Joseph sur Render

Cette application est une application **Laravel 12 / PHP 8.4** avec assets Vite. Le dépôt contient un `Dockerfile` et un `render.yaml` pour créer un service web et un worker de file d’attente.

## 1. Préparer Supabase

Le déploiement configuré utilise Supabase pour PostgreSQL et les fichiers téléversés.

1. Créer un projet Supabase.
2. Dans **Project Settings → Database**, récupérer les paramètres de connexion PostgreSQL. Utiliser de préférence le **Session Pooler** si le serveur Render ne peut pas joindre l’adresse directe IPv6.
3. Créer un bucket Storage nommé, par exemple, `saint-joseph`. Le disque configuré dans Laravel utilise une URL publique pour les objets : régler le bucket comme public si les images doivent être accessibles sans authentification.
4. Récupérer les identifiants S3 de Supabase Storage : endpoint S3, Access Key ID et Secret Access Key. Ne jamais mettre ces secrets dans GitHub.

## 2. Créer les services Render

1. Ouvrir le tableau de bord Render : https://dashboard.render.com/
2. Choisir **New → Blueprint** et connecter le dépôt `bossombra1/Saint-Joseph`.
3. Pour tester les changements de déploiement avant intégration, sélectionner la branche `deploy/render-saint-joseph`. Après validation, fusionner la branche dans `main`.
4. Render lira `render.yaml` et préparera le service web `saint-joseph-artisan` ainsi que le worker `saint-joseph-artisan-queue`.
5. Le conteneur écoute sur le port `10000`; le health check utilise Laravel sur `/up`.

## 3. Renseigner les variables d’environnement

Les variables déclarées avec `sync: false` sont volontairement laissées hors du dépôt. Configure-les dans Render, **pour le service web et le worker**, avant de relancer les services.

### Base de données (web + worker)

- `DB_CONNECTION=pgsql`
- `DB_HOST` : hôte fourni par Supabase
- `DB_PORT=5432` (ou le port indiqué par le pooler sélectionné)
- `DB_DATABASE` : généralement `postgres`
- `DB_USERNAME` et `DB_PASSWORD` : identifiants PostgreSQL fournis par Supabase
- `DB_SSLMODE=require`

Le premier démarrage du service web exécute `php artisan migrate --force`. **Vérifie que la base cible est la bonne et qu’elle contient une sauvegarde avant le premier démarrage** : les migrations modifient le schéma.

### URL et clé de l’application

- `APP_URL` : l’URL HTTPS exacte attribuée par Render au service web.
- `APP_KEY` : Render la génère automatiquement pour le service web; le worker récupère cette même clé.

Ne régénère pas `APP_KEY` après la mise en production : cela invaliderait notamment les sessions et les données chiffrées.

### Stockage Supabase (web + worker)

- `FILESYSTEM_DISK=supabase`
- `SUPABASE_URL` : URL du projet Supabase, sans suffixe Storage
- `SUPABASE_BUCKET` : nom du bucket
- `SUPABASE_ENDPOINT` : endpoint S3 de Supabase Storage
- `SUPABASE_ACCESS_KEY_ID` et `SUPABASE_SECRET_ACCESS_KEY` : identifiants S3
- `SUPABASE_SERVICE_KEY` : clé de service si les fonctionnalités qui l’utilisent sont activées

Les fichiers locaux d’un service Render ne sont pas un stockage persistant. Vérifie donc le bucket et les identifiants avant d’utiliser les téléversements en production.

### Courriels et intégrations facultatives

Pour envoyer des courriels, renseigner `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` et `MAIL_FROM_ADDRESS`. Le blueprint utilise SMTP sur le port 587; adapte-le aux paramètres de ton fournisseur.

Les clés `PAYMONGO_*`, `GROQ_API_KEY` et `OPENROUTER_API_KEY` sont nécessaires uniquement si les intégrations correspondantes sont utilisées. Configure les mêmes valeurs sur le worker lorsque les tâches en arrière-plan en ont besoin.

## 4. Vérifier le déploiement

Après configuration des variables :

1. Lancer ou relancer le déploiement du service web.
2. Vérifier les logs de build Docker et de démarrage Laravel.
3. Vérifier que le health check `/up` passe.
4. Vérifier que les migrations sont terminées et que les tables `sessions`, `cache`, `jobs` et `failed_jobs` existent.
5. Tester la page d’accueil, la connexion à l’administration, les formulaires, l’envoi d’e-mails et un téléversement d’image.
6. Vérifier les logs du worker et envoyer une tâche en arrière-plan de test.

## Notes importantes

- Le blueprint crée un **worker séparé**; ce service peut entraîner un coût distinct selon le plan Render.
- Les valeurs de base de données, les identifiants de stockage et les clés d’intégration ne doivent jamais être commitées.
- Les changements de Docker et de configuration ont été préparés dans la branche `deploy/render-saint-joseph`; le déploiement réel et les tests d’intégration doivent être confirmés dans ton compte Render/Supabase.
