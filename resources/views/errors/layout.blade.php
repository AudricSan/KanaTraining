@extends('layouts.app')

@section('content')
    <main>
        <section class="_1"></section>

        <section class="_2 error-page">
            <img src="/image/mascot/http-error/@yield('mascot').png" alt="" class="error-mascot" width="220" height="220" />
            <p class="error-code">Erreur @yield('code')</p>
            <h2>@yield('heading')</h2>
            <p>@yield('message')</p>
            <a href="{{ route('quiz') }}" class="error-home-link">Retour à l'entraînement</a>
        </section>
    </main>
@endsection
