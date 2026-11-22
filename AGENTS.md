# AGENTS.md

This file provides guidance to Codex (claude.ai/code) when working with code in this repository.

## Aperçu

KanaTraining : site d'entraînement aux hiragana/katakana/kanji (PHP 8 + MySQL + JS vanilla, sans framework). Pas de Composer, pas de npm, pas de tests, pas de linter. Le projet tourne sous Apache (XAMPP) avec `mod_rewrite` ; le site est servi à la racine (`/`) du vhost.

## Exécution et configuration

- Lancer : servir le dossier via XAMPP/Apache (`http://localhost/`). Le `.htaccess` racine réécrit tout vers `public/`, et `public/.htaccess` envoie toute requête qui n'est pas un fichier/dossier vers `public/index.php`. `BASEPATH` est `/` : le site doit être à la racine d'un vhost, pas dans un sous-dossier.
- `model/class/env.php` et `.env` sont **gitignorés** et absents du dépôt : ils fournissent la classe `Kanatraining\env` (`env::startSession()`, `$env->env('CLE')`). Clés utilisées : `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USERNAME`, `DB_PASSWORD`, `TWITCH_CLIENT_ID`, `TWITCH_CLIENT_SECRET`, `TWITCH_SCOPES`. Ne pas la recréer/committer.
- Base de données : schéma dans `model/slq/db.sql` (le dossier s'appelle bien `slq`), puis les seeds/migrations `achievements_seed*.sql`, `character_stats_migration.sql`, `fix_achievements_charset.sql`. À appliquer à la main.
- CSS : les `.css` sont **compilés depuis les `.scss`** (`index`, `menu`, `small`, `darktheme`) et les deux sont versionnés. Modifier le `.scss` puis recompiler (ex. extension VS Code Live Sass ou `sass`) ; ne pas éditer le `.css` seul.
- `/student/preview` rend la page profil avec des données factices, sans BDD ni auth : pratique pour itérer sur la mise en page. `/php.routes` liste les routes (debug).
- Le texte de l'interface et les commentaires sont en français.

## Architecture

- **Front controller unique** : `public/index.php` inclut manuellement les classes (pas d'autoload), déclare toutes les routes avec `Route::add($regex, $callback, $method = 'get')` puis appelle `Route::run(BASEPATH)`. Les expressions sont des regex ancrées automatiquement (`^...$`, insensible à la casse) ; un callback qui retourne une chaîne l'affiche (c'est ainsi que `/api/score` renvoie du JSON). Chaque page = `head()` + `include('../view/...')` + `foot()`.
- **Couches** (namespace `Kanatraining`, DAO dans `Kanatraining\DAO`) : `model/class/` (Route, Database = singleton PDO, TwitchOAuth, callback) ; `model/dao/` (`StudentDAO`, `AchievementDAO`, `CharacterStatsDAO`, requêtes PDO préparées) ; `view/` (gabarits PHP) ; `public/include/header.php|footer.php` (layout, nav, cache-busting des assets via `filemtime`).
- **Authentification** : uniquement OAuth Twitch (`/login/twitch` → Twitch → `/callback`, qui vérifie `state` puis crée/charge l'étudiant). Session PHP, `$_SESSION['student_id']`. Les visiteurs non connectés peuvent s'entraîner (progression en `localStorage`, voir `public/js/save.js`) mais rien n'est enregistré côté serveur.
- **Boucle de jeu** : `public/js/function.js` gère les réponses ; pour un utilisateur connecté, chaque réponse fait un `POST /api/score` (JSON : `correct`, `good`, `total`, `type`, `character`). Le serveur met à jour les stats (`StudentDAO::recordAnswer`), les stats par caractère (`CharacterStatsDAO::recordAttempt`) puis débloque les succès (`AchievementDAO::checkAndUnlock`) et renvoie les stats + `newAchievements`.
- **Pratique pondérée** : `header.php` injecte dans la page `weakCharactersJson` (poids par caractère issus de `CharacterStatsDAO::weightsFor`) pour que le JS tire plus souvent les caractères faibles.
- **Données de caractères** : `public/js/kana.js` et `kanji.js` (gros tableaux statiques) ; `const.js` contient l'état global et les sélecteurs DOM ; les scripts partagent des globales (pas de modules).
- **Thème** : clair/sombre via `public/js/theme.js`, variables dans `color.css` / `darktheme.scss`.
