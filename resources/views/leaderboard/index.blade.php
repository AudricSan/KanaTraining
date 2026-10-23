@extends('layouts.app')

@section('content')
    <main>
        <section class="_1"></section>

        <section class="_2 leaderboard">
            <h2>Classement</h2>

            @if ($students->isEmpty())
                <div class="leaderboard-empty-state">
                    <img src="/image/mascot/box-empty.png" alt="" class="leaderboard-empty-mascot" width="180" height="180" />
                    <p class="leaderboard-empty">Aucun élève classé pour le moment. Sois le premier à t'entraîner !</p>
                </div>
            @else
                <ol class="leaderboard-list">
                    @foreach ($students as $student)
                        <li class="leaderboard-row {{ $student->id === $currentUserId ? 'current' : '' }}">
                            <span class="leaderboard-rank">{{ $loop->iteration }}</span>
                            <img class="leaderboard-avatar" src="{{ $student->avatar }}" alt="" width="36" height="36" />
                            <span class="leaderboard-name">{{ $student->name }}</span>
                            <span class="leaderboard-xp"><span class="material-icons-round">bolt</span> {{ $student->global_xp }}</span>
                            <span class="leaderboard-streak">🔥 {{ $student->streak_days }}</span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>
    </main>
@endsection
