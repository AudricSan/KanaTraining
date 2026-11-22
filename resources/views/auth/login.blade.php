@extends('layouts.app')

@section('content')
    <main>
        <section class="_1"></section>

        <section class="_2 auth-page">
            <img src="/image/mascot/bell.png" alt="" class="auth-mascot" width="160" height="160" />
            <h2>Connexion</h2>
            <p>Connecte-toi pour suivre ton XP, ta série de jours et ton meilleur score.</p>

            @include('partials.flash')

            @if ($providers)
                <div class="provider-buttons">
                    @foreach ($providers as $provider)
                        <a href="{{ route('login.provider', $provider) }}" class="provider-btn provider-{{ $provider->value }}">
                            @include('partials.provider-icon', ['provider' => $provider])
                            Se connecter avec {{ $provider->label() }}
                        </a>
                    @endforeach
                </div>
                <p class="auth-hint">Déjà inscrit avec un autre service ? Connecte-toi avec celui-ci, puis lie les autres depuis ton profil.</p>
            @else
                <p class="flash flash-error">Aucun moyen de connexion n'est configuré pour le moment.</p>
            @endif

            <p><a href="{{ route('quiz') }}">Retour à l'entraînement</a></p>
        </section>
    </main>
@endsection
