<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NDI 2025') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://kit.fontawesome.com/40018cf627.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ndi-blue': '#000055',
                        'ndi-purple': '#d8b4fe',
                        'ndi-gold': '#D2C72F',
                        'ndi-lime': '#D9F103',
                    },
                    fontFamily: {
                        'montserrat': ['Montserrat', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        
        /* Styles pour le jeu */
        .draggable-source { cursor: grab; }
        .draggable-source:active { cursor: grabbing; }
        
        /* Pour masquer le contenu tant que Alpine/JS n'est pas chargé (optionnel) */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-montserrat flex flex-col min-h-screen bg-gray-50 text-ndi-blue">

    @include('layouts.partials.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script>
        // Script simple pour le menu burger
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            if(menu) menu.classList.toggle('hidden');
        }
    </script>
    
    @stack('scripts')
</body>
</html>