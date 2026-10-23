# KanaTraining

Application web gratuite pour apprendre les hiragana, katakana et kanji du JLPT (N5 à N1).
Version 3 : réécriture en **Laravel 12** (MVC), à parité avec l'ancienne version PHP maison,
qui reste consultable dans [`legacy/`](legacy/).

## Fonctionnalités

- Quiz hiragana / katakana (base, combo, dakuon, dakuon combo) et kanji JLPT N5 → N1
  (lecture ou traduction), plusieurs sets combinables, table d'aide, partage du score sur Twitter.
- Connexion avec Twitch, Google ou Discord (Socialite) : le compte est créé à la première connexion, les autres services se lient depuis le profil.
- XP par réponse selon la difficulté du set (gagnée si juste, perdue si faux, jamais sous 0),
  série de jours, meilleur score et dernier score.
- Statistiques par caractère : les caractères ratés reviennent plus souvent (tirage pondéré)
  et les 5 pires apparaissent dans le profil (« À travailler »).
- 24 succès (XP, séries, score, maîtrise d'un set), notifiés en direct pendant le quiz.
- Classement des 20 meilleurs élèves, thème clair / sombre, pages d'erreur avec la mascotte.

## Architecture

| Couche | Où |
| --- | --- |
| Routes | `routes/web.php` |
| Contrôleurs | `app/Http/Controllers` (quiz, profil, classement, auth Twitch / Google / Discord, API JSON) |
| Logique métier | `app/Services/ScoreService.php` (XP, série, scores, stats), `app/Services/AchievementService.php` |
| Modèles | `app/Models` : `User`, `CharacterSet`, `Kana`, `Kanji`, `CharacterStat`, `Achievement` |
| Données | `database/migrations`, `database/seeders`, `database/data/{kana,kanji}.json` |
| Vues | `resources/views` (Blade) ; CSS / JS / images dans `public/`, sources SCSS dans `resources/scss` |

Les kana et kanji sont en base : ajouter un set (ex. vocabulaire) = une ligne dans
`CharacterSetSeeder` + ses caractères dans `database/data`, sans toucher au JavaScript.
L'XP d'un set est la colonne `character_sets.xp_value`, les succès sont des lignes de
`achievements` (`metric` = `xp`, `streak`, `highscore` ou `category`, avec un `threshold`).

## Installation (XAMPP / MySQL)

```bash
composer install
cp .env.example .env
php artisan key:generate
# créer la base "kanatraining" dans MySQL, puis renseigner DB_*, TWITCH_*, GOOGLE_* et DISCORD_* dans .env
php artisan migrate --seed
php artisan serve        # ou un virtual host Apache qui pointe sur public/
```

URLs de redirection à déclarer pour chaque service (un service sans clés n'apparaît pas) :

| Service | Console | URL de redirection |
|---|---|---|
| Twitch | https://dev.twitch.tv/console/apps | `<APP_URL>/callback` (la même que l'ancienne version) |
| Google | https://console.cloud.google.com/apis/credentials | `<APP_URL>/login/google/callback` |
| Discord | https://discord.com/developers/applications (OAuth2) | `<APP_URL>/login/discord/callback` |

### Reprendre les données de l'ancienne version

Renseigner `LEGACY_DB_*` dans `.env` (la base MySQL de l'ancienne app), puis :

```bash
php artisan kana:import-legacy
```

La commande copie les élèves (XP, série, scores, date d'inscription), leurs statistiques par
caractère et leurs succès débloqués. Elle peut être relancée sans créer de doublons.

## Tests

```bash
php artisan test
./vendor/bin/pint --test
```

## Licence

[AGPL-3.0](LICENSE) — Audric Rosier ([@AudricSan](https://github.com/AudricSan)).
