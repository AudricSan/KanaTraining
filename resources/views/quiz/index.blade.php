@extends('layouts.app')

@section('content')
    <main>
        <section class="_1">
            <nav class="">
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

                <div class="mascot-companion">
                    <img src="/image/mascot/brand-icon.png" alt="" width="140" height="140" />
                </div>
            </nav>
        </section>

        <section class="_2">
            <h2 class="character" lang="ja" aria-live="polite"> あ </h2>
            <h3 class="kanjitype" aria-live="polite"> read </h3>

            <form class="forms" action="#" onsubmit="checkAnswerd(); return false" autocomplete="off">
                <label class="sr-only" for="input">Réponse (romaji)</label>
                <input id="input" name="input" type="text" class="input" autofocus maxlength="3" />

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
            </form>
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
    <script src="{{ App\Support\Asset::versioned('js/quiz.js') }}"></script>
@endpush
