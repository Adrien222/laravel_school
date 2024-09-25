<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon Application')</title>
</head>
<body>

    <header>
        <nav>
            <ul>
                <!-- Lien global vers /tools -->
                <li><a href="{{ url('/tools') }}">Voir tous les outils</a></li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

</body>
</html>
