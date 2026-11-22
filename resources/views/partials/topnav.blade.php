<nav class="topnav" aria-label="Navigation principale">
    <a href="{{ route('quiz') }}" @if (request()->routeIs('quiz')) aria-current="page" @endif><span class="material-icons-round">school</span> Entraînement</a>
    <a href="{{ route('leaderboard') }}" @if (request()->routeIs('leaderboard')) aria-current="page" @endif><span class="material-icons-round">leaderboard</span> Classement</a>
    @auth
        <a href="{{ route('student.show') }}" @if (request()->routeIs('student.*')) aria-current="page" @endif><span class="material-icons-round">person</span> Profil</a>
        <form method="POST" action="{{ route('logout') }}" class="topnav-form">
            @csrf
            <button type="submit"><span class="material-icons-round">logout</span> Déconnexion</button>
        </form>
    @else
        <a href="{{ route('login') }}" @if (request()->routeIs('login')) aria-current="page" @endif><span class="material-icons-round">person</span> Connexion</a>
    @endauth
    <button type="button" class="theme-toggle-btn" id="themeToggleBtn" onclick="selectTheme()" aria-label="Changer de thème" aria-pressed="false">
        <span class="material-icons-round" id="themeToggleIcon">dark_mode</span>
    </button>
</nav>
