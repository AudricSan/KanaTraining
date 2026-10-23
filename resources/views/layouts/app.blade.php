@php use App\Support\Asset; @endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Entraînez vos Kanas')</title>

    {{-- Applique le thème sauvegardé avant le premier rendu, pour éviter un flash
         de thème clair suivi d'une bascule vers le thème sombre. --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                document.documentElement.setAttribute('data-theme', t === 'dark' ? 'dark' : 'light');
            } catch (e) {}
        })();
    </script>

    <meta name="application-name" content="KanaTraining" />
    <meta name="creator" content="Audric Rosier" />
    <meta name="publisher" content="Audric Rosier" />
    <meta name="author" content="https://github.com/AudricSan" />
    <meta name="description" content="Application simple pour apprendre gratuitement les Kanas." />
    <meta name="robots" content="index,follow,noodp,noimageindex" />

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <link rel="apple-touch-icon" sizes="180x180" href="/image/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/image/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="194x194" href="/image/favicon/favicon-194x194.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="/image/favicon/android-chrome-192x192.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/image/favicon/favicon-16x16.png" />
    <link rel="manifest" href="/image/favicon/site.webmanifest" />
    <link rel="mask-icon" href="/image/favicon/safari-pinned-tab.svg" color="#ffffff" />
    <link rel="shortcut icon" href="/image/favicon/favicon.ico" />
    <link rel="icon" type="image/svg+xml" href="/image/favicon/svg/favicon.svg" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="/image/favicon/mstile-144x144.png" />
    <meta name="msapplication-config" content="/image/favicon/browserconfig.xml" />
    <meta name="theme-color" content="#ffffff" />

    <link href="{{ Asset::versioned('css/reset.css') }}" rel="stylesheet" />
    <link href="{{ Asset::versioned('css/index.css') }}" rel="stylesheet" />
    <link href="{{ Asset::versioned('css/menu.css') }}" rel="stylesheet" />
    <link href="{{ Asset::versioned('css/small.css') }}" rel="stylesheet" />
</head>

<body>
    <div id="blur" class=""></div>

    <header>
        <a href="{{ route('quiz') }}" class="logo-link">
            <img class="logo" src="/image/logo.png" alt="Logo KanaTraining" />
            <h1>Entraînez vos Kanas</h1>
        </a>
        <button class="navbtn"> Menu <span class="menuicon material-icons-round">expand_more</span> </button>
    </header>

    @include('partials.topnav')

    @yield('content')

    <footer>
        <a href="" target="_blank" class="twitter-share">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.163-1.227.163-.3 0-.593-.028-.877-.082.593 1.85 2.313 3.198 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.202-.005-.403-.014-.602.91-.658 1.7-1.477 2.323-2.41z"/></svg>
            <span>Partager sur Twitter</span>
        </a>

        <div class="author">
            <a href="https://twitter.com/AudricSan" target="_blank">
                <span class="material-icons-round">person</span>
                par Audric_San
            </a>

            <p>|</p>

            <a href="https://github.com/AudricSan/KanaTraining" target="_blank" class="git">
                Le projet sur GitHub
                <svg class="icongit" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8Z"/></svg>
            </a>
        </div>
    </footer>

    <script src="{{ Asset::versioned('js/save.js') }}"></script>
    <script src="{{ Asset::versioned('js/theme.js') }}"></script>
    @stack('scripts')
</body>

</html>
