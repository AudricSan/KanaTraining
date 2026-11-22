# KanaTraining 🇯🇵

**Apprenez le japonais, un caractère à la fois.**

KanaTraining est une application web gratuite et open source dédiée à l'apprentissage de l'écriture japonaise. Elle permet de s'entraîner aux hiragana, aux katakana et aux kanji du JLPT, du niveau N5 au niveau N1.

L'objectif est de proposer un outil d'apprentissage simple à utiliser, mais suffisamment complet pour accompagner les apprenants dans leur progression, grâce à différents modes d'exercices, un suivi personnalisé et un système de progression.

> Un projet indépendant conçu pour rendre l'apprentissage du japonais plus accessible.

---

## ✨ Fonctionnalités

### 📚 Apprentissage du japonais

- **Hiragana** : caractères de base, combinaisons, dakuon et combinaisons dakuon.
- **Katakana** : caractères de base, combinaisons, dakuon et combinaisons dakuon.
- **Kanji** : apprentissage des kanji du JLPT N5 à N1.
- **Sets combinables** : personnalisez vos exercices en sélectionnant plusieurs ensembles de caractères.
- **Table d'aide** : retrouvez les caractères et leurs informations pendant vos exercices.

### 🎮 Trois modes d'exercice

Adaptez votre façon d'apprendre selon vos besoins.

| Mode | Description |
|---|---|
| ✍️ Saisie | Retrouvez le caractère ou sa signification en saisissant votre réponse. |
| 📝 QCM | Sélectionnez la bonne réponse parmi quatre propositions. |
| 🔄 Mode inverse | Retrouvez le caractère à partir de son romaji ou de sa signification. |

Les exercices peuvent être effectués librement ou sous forme de sessions de 10 ou 20 questions.

À la fin d'une session, retrouvez un récapitulatif de vos résultats et les caractères sur lesquels vous avez fait des erreurs. Vous pouvez ensuite relancer un exercice dédié à vos erreurs.

---

## 📈 Progression et suivi

KanaTraining intègre plusieurs mécanismes pour encourager un apprentissage régulier.

- **Expérience (XP)** : gagnez ou perdez de l'expérience en fonction de vos réponses et de la difficulté de l'exercice.
- **Séries quotidiennes** : suivez vos jours consécutifs d'apprentissage.
- **Statistiques individuelles** : consultez vos résultats caractère par caractère.
- **Apprentissage adaptatif** : les caractères que vous maîtrisez moins sont proposés plus fréquemment.
- **Liste « À travailler »** : retrouvez les cinq caractères les plus difficiles pour vous.
- **Meilleurs scores** : gardez une trace de vos performances.
- **Succès** : débloquez 24 succès en fonction de votre activité et de votre progression.
- **Classement** : comparez votre progression avec celle des autres apprenants.

---

## 🏆 Un apprentissage gamifié

Apprendre régulièrement est essentiel pour progresser.

KanaTraining intègre des éléments de gamification pour rendre les sessions d'apprentissage plus motivantes :

- Accumulation d'expérience.
- Succès et objectifs à débloquer.
- Séries quotidiennes.
- Classement des apprenants.
- Suivi des scores.
- Maîtrise progressive des différents sets.

La gamification reste au service de l'apprentissage : l'objectif est de vous aider à mémoriser durablement les caractères japonais.

---

## 👤 Compte utilisateur

Connectez-vous à KanaTraining pour conserver votre progression et retrouver vos statistiques.

Les connexions sont disponibles via :

- Twitch
- Google
- Discord

Votre compte permet notamment de conserver votre expérience, vos séries, vos scores, vos statistiques et vos succès.

---

## 🛠️ Technologies

KanaTraining Version 3 repose sur une architecture modernisée.

| Technologie | Utilisation |
|---|---|
| PHP | Backend |
| Laravel 12 | Framework et architecture MVC |
| MySQL | Base de données |
| Blade | Templates et interface |
| JavaScript | Interactions et moteur des exercices |
| SCSS / CSS | Styles et interface responsive |
| Socialite | Authentification OAuth |
| PHPUnit | Tests automatisés |

La version 3 est une réécriture de l'application historique en PHP. L'ancienne version est conservée dans le répertoire `legacy/`.

---

## 🏗️ Architecture

Le projet suit une architecture MVC avec séparation des responsabilités.

| Couche | Emplacement |
|---|---|
| Routes | `routes/web.php` |
| Contrôleurs | `app/Http/Controllers/` |
| Services métier | `app/Services/` |
| Modèles | `app/Models/` |
| Migrations | `database/migrations/` |
| Seeders | `database/seeders/` |
| Données pédagogiques | `database/data/` |
| Vues | `resources/views/` |
| Styles | `resources/scss/` |
| JavaScript | `public/js/` |

### Logique métier

Les services principaux centralisent les mécanismes de progression :

- `ScoreService.php` : expérience, scores, séries et statistiques.
- `AchievementService.php` : vérification et attribution des succès.

Les sets de caractères sont gérés en base de données. L'ajout d'un nouvel ensemble de contenu peut être effectué à travers les seeders et les données associées, sans avoir à réécrire le moteur des exercices.

---

## 🚀 Installation

### Prérequis

- PHP compatible avec Laravel 12
- Composer
- MySQL
- Extensions PHP requises par Laravel

### Installation locale

Cloner le dépôt :

```bash
git clone https://github.com/AudricSan/KanaTraining.git
cd KanaTraining
```

Installer les dépendances :

```bash
composer install
```

Créer le fichier de configuration :

```bash
cp .env.example .env
php artisan key:generate
```

Créer une base de données MySQL nommée `kanatraining`, puis renseigner les informations de connexion dans `.env`.

Configurer également les identifiants OAuth des services souhaités :

- Twitch
- Google
- Discord

Exécuter les migrations et les seeders :

```bash
php artisan migrate --seed
```

Lancer le serveur de développement :

```bash
php artisan serve
```

L'application sera accessible à l'adresse locale indiquée par Laravel.

---

## 🔐 Configuration OAuth

Les URL de redirection doivent être configurées dans les consoles des fournisseurs d'authentification.

| Fournisseur | Console | URL de callback |
|---|---|---|
| Twitch | [Developer Console](https://dev.twitch.tv/console/apps) | `/callback` |
| Google | [Google Cloud Console](https://console.cloud.google.com/apis/credentials) | `/login/google/callback` |
| Discord | [Discord Developer Portal](https://discord.com/developers/applications) | `/login/discord/callback` |

Remplacez `<APP_URL>` par l'URL de votre installation.

Les fournisseurs dont les identifiants ne sont pas configurés ne sont pas affichés dans l'application.

---

## 🔄 Migration depuis l'ancienne version

KanaTraining Version 3 dispose d'une commande permettant de récupérer les données de l'ancienne application.

La migration permet de transférer :

- Les comptes utilisateurs.
- L'expérience accumulée.
- Les séries quotidiennes.
- Les scores.
- Les statistiques par caractère.
- Les succès déjà débloqués.

### Configuration

Renseignez les variables `LEGACY_DB_*` dans votre fichier `.env` afin de permettre l'accès à l'ancienne base MySQL.

Puis lancez :

```bash
php artisan kana:import-legacy
```

La commande est conçue pour pouvoir être exécutée à nouveau sans créer de doublons.

---

## 🧪 Tests

Les tests automatisés permettent de vérifier les fonctionnalités principales et d'éviter les régressions.

Exécuter la suite de tests :

```bash
php artisan test
```

Vérifier le formatage du code avec Laravel Pint :

```bash
./vendor/bin/pint --test
```

---

## 🤝 Contribution

KanaTraining est un projet open source.

Les suggestions, signalements de bugs et contributions sont les bienvenus. Si vous souhaitez participer à son amélioration, vous pouvez ouvrir une issue ou proposer une pull request sur GitHub.

Toute contribution permettant d'améliorer l'expérience d'apprentissage, la qualité du code, l'accessibilité ou la fiabilité de l'application est appréciée.

---

## 📜 Licence

KanaTraining est distribué sous licence **GNU Affero General Public License v3.0 (AGPL-3.0)**.

Vous êtes libre d'étudier, d'utiliser, de modifier et de redistribuer le projet conformément aux conditions de cette licence.

Voir le fichier [LICENSE](LICENSE) pour plus d'informations.

---

## 👨‍💻 Auteur

**Audric Rosier**

GitHub : [@AudricSan](https://github.com/AudricSan)

---

<p align="center">
  <i>Made with care for Japanese learners 🇯🇵</i>
</p>