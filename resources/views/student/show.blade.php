@extends('layouts.app')

@section('content')
    <main>
        <section class="_1"></section>

        <section class="_2 student-profile">
            @include('partials.flash')

            <div class="profile-grid">
                <div class="profile-card profile-identity">
                    <img class="student-avatar" src="{{ $student->avatar }}" alt="Avatar de {{ $student->name }}" width="96" height="96" />
                    <h2>{{ $student->name }}</h2>

                    <ul class="student-stats">
                        <li><span>XP total</span> <strong>{{ $student->global_xp }}</strong></li>
                        <li><span>Série de jours</span> <strong>{{ $student->streak_days }}</strong></li>
                        <li><span>Meilleur score</span> <strong>{{ $student->high_score }}</strong></li>
                        <li><span>Dernier score</span> <strong>{{ $student->last_score }}</strong></li>
                    </ul>

                    <div class="profile-accounts">
                        <h3 class="achievements-title">Moyens de connexion</h3>
                        <ul class="accounts-list">
                            @foreach ($providers as $item)
                                <li class="account {{ $item['account'] ? 'linked' : '' }}">
                                    <span class="account-name provider-{{ $item['provider']->value }}">
                                        @include('partials.provider-icon', ['provider' => $item['provider']])
                                        {{ $item['provider']->label() }}
                                    </span>
                                    @if ($item['account'])
                                        <span class="account-nick">{{ $item['account']->nickname }}</span>
                                        @if ($canUnlink)
                                            <form method="POST" action="{{ route('login.unlink', $item['provider']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="account-action">Retirer</button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login.provider', $item['provider']) }}" class="account-action">Lier</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="profile-card profile-achievements">
                    <h3 class="achievements-title">Succès</h3>
                    <ul class="achievements-list">
                        @foreach ($achievements as $achievement)
                            <li class="badge {{ $achievement['unlocked'] ? 'unlocked' : 'locked' }}" title="{{ $achievement['description'] }}">
                                <span class="badge-icon">{{ $achievement['icon'] }}</span><span class="badge-name">{{ $achievement['name'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="profile-card profile-weak">
                    <h3 class="achievements-title">À travailler</h3>
                    @if ($weak->isNotEmpty())
                        <ul class="weak-list">
                            @foreach ($weak as $item)
                                <li><span class="weak-char" lang="ja">{{ $item['character'] }}</span><span class="weak-type">{{ $item['label'] }}</span><span class="weak-count">{{ $item['count'] }}×</span></li>
                            @endforeach
                        </ul>
                    @else
                        <div class="weak-empty">
                            <img src="/image/mascot/docs-hero.png" alt="" class="weak-empty-mascot" width="120" height="120" />
                            <p>Pas encore de statistiques. Entraîne-toi pour voir tes points faibles ici !</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
