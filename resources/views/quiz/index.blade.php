@extends('layouts.app')

@section('content')
    <button type="button" class="navbtn" aria-expanded="false" aria-controls="settings">
        <span class="material-icons-round">tune</span> Réglages <span class="menuicon material-icons-round">expand_more</span>
    </button>

    <main>
        <section class="_1">
            <nav id="settings" aria-label="Réglages de l'exercice">
                @foreach ($groups as $group => $sets)
                    <details class="menu-group" @if ($loop->first) open @endif>
                        <summary>{{ $group }}<span class="menu-group-badge hidden" aria-hidden="true"></span></summary>
                        <div class="menu-group-body">
                            @foreach ($sets as $set)
                                <form action="">
                                    <input type="checkbox" class="switch" id="{{ $set->slug }}" name="cc" onchange="selectDificulty(this.id)" />
                                    <label for="{{ $set->slug }}">{{ $set->name }}</label>
                                </form>
                            @endforeach
                        </div>
                    </details>
                @endforeach

                <details class="menu-group exercise-settings" open>
                    <summary>Exercice</summary>
                    <div class="menu-group-body">
                        <fieldset>
                            <legend>Mode</legend>
                            <label><input type="radio" name="mode" value="type" /> Saisie</label>
                            <label><input type="radio" name="mode" value="choice" /> QCM</label>
                            <label><input type="radio" name="mode" value="reverse" /> Inverse</label>
                        </fieldset>
                        <fieldset>
                            <legend>Session</legend>
                            <label><input type="radio" name="sessionLength" value="0" /> Libre</label>
                            <label><input type="radio" name="sessionLength" value="10" /> 10</label>
                            <label><input type="radio" name="sessionLength" value="20" /> 20</label>
                        </fieldset>
                    </div>
                </details>

                <div class="mascot-companion">
                    <img src="/image/mascot/brand-icon.png" alt="" width="140" height="140" />
                </div>
            </nav>
        </section>

        <section class="_2">
            <div class="exercise-progress hidden" id="progress">
                <span id="progressText"></span>
                <div class="progress-bar" aria-hidden="true"><div id="progressFill"></div></div>
            </div>

            <div class="exercise-play" id="play">
                <h2 class="character" lang="ja" aria-live="polite"> あ </h2>
                <h3 class="kanjitype hidden" aria-live="polite"></h3>

                <form class="forms" action="#" onsubmit="checkAnswerd(); return false" autocomplete="off">
                    <label class="sr-only" for="input">Ta réponse</label>
                    <input id="input" name="input" type="text" class="input" autofocus maxlength="4" placeholder="Ta réponse" autocapitalize="off" spellcheck="false" />
                </form>

                <div class="choices hidden" id="choices"></div>

                <p id="feedback" role="status" aria-live="polite"></p>
            </div>

            <div class="session-recap hidden" id="recap" aria-live="polite"></div>

            <div id="toast" role="status"></div>

            <div class="stats-row">
                <div class="score">
                    <span>Votre score</span>
                    <span class="material-icons-round">star</span>
                    <span class="good">0</span>/<span class="total">0</span>
                </div>

                @if ($user)
                    <div class="live-stats" id="liveStats" aria-live="polite">
                        <span class="material-icons-round">bolt</span> <span id="liveXp">{{ $user->global_xp }}</span> XP
                        <span>🔥</span> <span id="liveStreak">{{ $user->streak_days }}</span> j
                    </div>
                @endif
            </div>
        </section>

        <section class="_3">
            <div class="helpSpawner" id="helpSpawner" role="dialog" aria-label="Table des caractères">
                <ul class="helpContainer"></ul>
            </div>

            <a href="#" class="helpOpen" aria-expanded="false" aria-controls="helpSpawner" aria-label="Afficher l'aide"><span class="material-icons-round iconhelp">info</span></a>
            <a href="#" class="helpClose hidden" aria-label="Fermer l'aide"><span class="material-icons-round">close</span></a>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        const isLoggedIn = @json($user !== null);
        const weakCharacters = @json((object) $weakCharacters);
        const charactersUrl = @json(route('api.characters'));
        const scoreUrl = @json(route('api.score'));
    </script>
    <script src="{{ App\Support\Asset::versioned('js/const.js') }}"></script>
    <script src="{{ App\Support\Asset::versioned('js/function.js') }}"></script>
    <script src="{{ App\Support\Asset::versioned('js/exercise.js') }}"></script>
    <script src="{{ App\Support\Asset::versioned('js/quiz.js') }}"></script>
@endpush
