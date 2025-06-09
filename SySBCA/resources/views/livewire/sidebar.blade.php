<aside id="sidebar"
    :class="sidebarOpen ? 'w-auto' : 'w-16'"
    class="bg-gradient-to-t from-teal-900 to-teal-600 text-white shadow-lg pt-16 flex-shrink-0 transition-all duration-300 overflow-hidden">

    <nav class="space-y-3 text-sm px-2">
        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-speedometer"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Tableau de bord</span>
        </a>
        <a href="{{ route('regions.index') }}" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-globe"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Régions</span>
        </a>
        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-map-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Districts</span>
        </a>
        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-hospital-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Formations sanitaires</span>
        </a>
        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-bug-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Antipaludiques</span>
        </a>
        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-box-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Consommations</span>
        </a>
        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-cart-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Besoins</span>
        </a>

        <hr class="border-white/30 my-3">

        <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-person-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Profil</span>
        </a>
        <a href="{{ route('logout') }}" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-red-100 hover:text-red-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Déconnexion</span>
        </a>
    </nav>
</aside>
