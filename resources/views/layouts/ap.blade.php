<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    @if (Auth::user()->profil === 'Contrôleur')
        @include('layouts.partials._navControleur')
    @endif
    @if (Auth::user()->profil === 'direction')
        @include('layouts.partials._navDirection')
    @endif
    @if (Auth::user()->profil === 'dga')
        @include('layouts.partials._navDirection')
    @endif
    @if (Auth::user()->profil === 'dagrh')
        @include('layouts.partials._navDirection')
    @endif
    @if (Auth::user()->profil === 'controle_gestion')
        @include('layouts.partials._navDirection')
    @endif
    @if (Auth::user()->profil === 'moyens_generaux')
        @include('layouts.partials._navMoyensGeneraux')
    @endif
    @if (Auth::user()->profil === 'rh')
        @include('layouts.partials._navRH')
    @endif
    @if (Auth::user()->profil === 'budget')
        @include('layouts.partials._navDirection')
    @endif
    @if (Auth::user()->profil === 'comptabilite')
        @include('layouts.partials._navDirection')
    @endif
    @if (Auth::user()->profil === 'Admin')
        @include('layouts.partials._nav')
    @endif
    @if (Auth::user()->profil === 'Informatique')
        @include('layouts.partials._navInformatique')
    @endif

</head>

<body>
    <div id="app">

        <main class="py-4">
            @yield('content')
        </main>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap5.bundle.min.js') }}"></script>
    @yield('scripts')
</body>
<footer>

    @include('layouts.partials._footer')
</footer>

</html>
