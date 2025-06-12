<!DOCTYPE html>
<html lang="fr" x-data="{ sidebarOpen: true }" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SelfCare</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800 flex h-screen overflow-hidden font-[Rubik]">

    <!-- Header -->
    <header class="w-full fixed top-0 left-0 z-10">
        <nav class="bg-white text-gray-800 flex items-center justify-between shadow">
            <!-- Left -->
            <div>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/pnlp3.jpg') }}" alt="">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-800 focus:outline-none text-teal-600 font-bold">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    
                </div>
            </div>
            <!-- Right -->
            <div class="flex items-center gap-2 p-1 bg-green-600 rounded-full">
                <span class="text-sm text-white">JF</span>
                <i class="bi bi-person-circle text-xl text-white"></i>
            </div>
        </nav>
    </header>

    <!-- Sidebar -->
    <livewire:sidebar />

    <!-- Main -->
    <main id="main-content" class="flex-1 overflow-y-auto h-full pt-20 p-4"
        style="background-image: url('{{ asset('images/bg3.jpg') }}')">
        {{ $slot }}
    </main>


</body>

</html>
