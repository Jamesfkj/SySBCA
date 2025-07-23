<aside id="sidebar" x-bind:class="sidebarOpen ? 'w-auto' : 'w-16'" class="">

    <nav class="space-y-3 text-sm px-2 pt-6">
        <a href="{{ route('dashboard') }}"
            class="{{ str_starts_with(request()->path(), 'dashboard') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-speedometer"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Tableau de bord</span>
        </a>
        @if (auth()->check())
            @php
                $role = auth()->user()->role->nom_role;
            @endphp

            @if ($role === 'Administrateur')
                <a href="{{ route('utilisateurs.index') }}"
                    class="{{ request()->is('utilisateurs*') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
                    <div
                        class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span x-show="sidebarOpen" class="truncate">Utilisateurs</span>
                </a>

                <a href="{{ route('regions.index') }}"
                    class="{{ request()->is('regions*') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
                    <div
                        class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                        <i class="bi bi-globe"></i>
                    </div>
                    <span x-show="sidebarOpen" class="truncate">Régions</span>
                </a>
                <a href="{{ route('districts.index') }}"
                    class="{{ request()->is('districts*') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
                    <div
                        class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                        <i class="bi bi-map-fill"></i>
                    </div>
                    <span x-show="sidebarOpen" class="truncate">Districts</span>
                </a>
            @endif
            @if (in_array($role, ['Administrateur', 'District']))
                <a href="{{ route('fs.index') }}"
                    class="{{ request()->is('fs*') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
                    <div
                        class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                        <i class="bi bi-hospital-fill"></i>
                    </div>
                    <span x-show="sidebarOpen" class="truncate">Formations sanitaires</span>
                </a>
            @endif
            @if ($role === 'Administrateur')
                <a href="{{ route('medicaments.index') }}"
                    class="{{ request()->is('medicaments*') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
                    <div
                        class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                        <i class="bi bi-capsule"></i>
                    </div>
                    <span x-show="sidebarOpen" class="truncate">Médicaments</span>
                </a>
            @endif
        @endif
        <a href="{{ route('consommations.index') }}"
            class=" {{ str_starts_with(request()->path(), 'consommations') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-box-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Consommations</span>
        </a>
        @if (in_array($role, ['Administrateur', 'District']))
            <a href="{{ route('synthese.district') }}"
            class=" {{ str_starts_with(request()->path(), 'synthese-district') ? 'bg-green-100 text-green-700' : '' }} flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Synthèse District</span>
        </a>
        @endif
        <hr class="border-white/30 my-3">
        <a href="#"
            class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-green-100 hover:text-green-700 transition">
            <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                <i class="bi bi-person-fill"></i>
            </div>
            <span x-show="sidebarOpen" class="truncate">Profil</span>
        </a>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-red-100 hover:text-red-700 transition w-full text-left">
                <div class="bg-white/80 w-9 aspect-square rounded-full flex items-center justify-center text-teal-900">
                    <i class="bi bi-box-arrow-left"></i>
                </div>
                <span x-show="sidebarOpen" class="truncate">Déconnexion</span>
            </button>
        </form>
    </nav>
</aside>
