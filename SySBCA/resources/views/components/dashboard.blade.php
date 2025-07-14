<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laravel</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    @endif

    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-800 flex h-screen overflow-hidden font-[Rubik]">
    <nav class="h-full bg-black pt-20 bg-gradient-to-t from-teal-900 to-teal-600 text-white shadow-lg flex-shrink-0 transition-all duration-1000 overflow-hidden"
        x-data="{ sidebarOpen: true }">
        <header class="w-full fixed top-0 left-0 z-10">
            <nav class="bg-white shadow flex items-center justify-between px-4 py-3">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/pnlp3.jpg') }}" alt="Logo" class="h-10">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-800 focus:outline-none text-teal-600 font-bold">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                </div>
                <div>
                    <div class="flex justify-between justify-between px-4 py-3">
                        <div>
                        </div>
                        <div class="flex items-center gap-2 p-2 bg-teal-700 rounded-full">
                            <i class="bi bi-person-circle text-xl text-white"></i>
                            <span class="text-white">Bonjour {{ Auth::user()->username }}, vous êtes connectés entant que
                                {{ Auth::user()->role->nom_role }}</span>
                        </div>
                    </div>
                </div>

            </nav>
        </header>
        <div>
            <livewire:sidebar />
        </div>
    </nav>


    <main id="main-content" class="flex-1 overflow-y-auto shadow-lg pt-20 p-4"
        style="background-image: url('{{ asset('images/bg.png') }}'); background-repeat: no-repeat; background-size: cover; background-position: center;">
        {{ $slot }}
    </main>
    @livewireScripts
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
