<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SelfCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jersey+15+Charted&family=Keania+One&family=Labrada:ital,wght@0,100..900;1,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Nosifer&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800 flex h-screen overflow-hidden">
    <header class="w-full fixed top-0 left-0 z-10">
    <nav class="bg-white text-gray-800 flex items-center justify-between px-4 py-3 shadow">
        <!-- Gauche : bouton menu + titre -->
        <div class="flex items-center gap-4">
            <button id="menu-toggle" class="text-gray-800 focus:outline-none ">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <span class="text-lg font-semibold">SelfCare Dashboard</span>
        </div>

        <!-- Droite : nom utilisateur + icône -->
        <div class="flex items-center gap-2 p-1 bg-green-600 rounded-full">
            <span class="text-sm text-white">Jean Dupont</span>
            <i class="bi bi-person-circle text-xl text-white"></i>
        </div>
    </nav>
</header>

    <livewire:sidebar />
    <!-- Main -->
    <main id="main-content" class="flex-1 overflow-y-auto h-full">

        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6 pb-10 pt-20">
            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="font-semibold text-lg text-green-700">Consommations</h2>
                <p class="text-3xl font-bold text-gray-900 mt-2">86</p>
            </div>

            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="font-semibold text-lg text-green-700">Besoins</h2>
                <p class="text-3xl font-bold text-gray-900 mt-2">23</p>
            </div>

            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="font-semibold text-lg text-green-700">Utilisation (7 jours)</h2>
                <div class="h-24 bg-gray-100 rounded mt-2 flex items-center justify-center text-gray-400 text-sm">
                    Graphique ici
                </div>
            </div>
        </div>
    </main>

    <!-- Script pour gérer l'affichage du menu -->
    <script>
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        if (menuToggle) {
            menuToggle.addEventListener("click", () => {
                sidebar.classList.toggle("w-64");
                sidebar.classList.toggle("w-0");
            });
        }
    </script>
</body>

</html>