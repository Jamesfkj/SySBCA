<aside id="sidebar" :class="sidebarOpen ? 'w-auto' : 'w-16'"
    class="bg-gradient-to-t from-teal-900 to-teal-600 text-white shadow-lg pt-16 flex-shrink-0 transition-all duration-1000 overflow-hidden">

    <nav class="space-y-3 text-sm px-2 pt-6">
        <a href="#"
            class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-speedometer"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Tableau de bord</span>
        </a>
        <a href="{{ route('regions.index') }}"
            class="{{ str_starts_with(request()->path(), 'regions') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-globe"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Régions</span>
        </a>


        <a href="{{ route('districts.index') }}"
            class=" {{ str_starts_with(request()->path(), 'districts') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-map-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Districts</span>
        </a>
        <a href="{{ route('fs.index') }}"
            class=" {{ str_starts_with(request()->path(), 'fs') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-hospital-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Formations sanitaires</span>
        </a>
        <a href="{{ route('medicaments.index') }}"
            class=" {{ str_starts_with(request()->path(), 'medicaments') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-capsule"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Médicaments</span>
        </a>
        <a href="#"
            class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-box-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Consommations</span>
        </a>
        <a href="#"
            class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-cart-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Besoins</span>
        </a>

        <hr class="border-white/30 my-3">

        <a href="#"
            class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-person-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Profil</span>
        </a>
        <a href="{{ route('logout') }}"
            class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-red-100 hover:text-red-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Déconnexion</span>
        </a>
    </nav>
</aside>
