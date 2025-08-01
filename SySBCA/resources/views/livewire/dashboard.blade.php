<div>
    <div wire:loading wire:target="chercherStatistiques"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>
    @if (auth()->check() && auth()->user()->role->nom_role == 'Formation sanitaire')
        <div class="flex justify-between items-center relative mb-4">
            <h2 class="text-2xl font-semibold text-teal-600">
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <p>Tableau de bord
                        @if (in_array(auth()->user()->role->nom_role, ['Formation sanitaire', 'District']))
                            <span class="font-semibold text-[18px] text-gray-500"> |
                                {{ Auth::user()->entity['nom'] }}</span>
                        @endif
                    </p>
                </span>
            </h2>
            <div>
                <div class="flex bg-gray-100 border border-gray-300 gap-2 p-4 items-center rounded-md shadow-sm">
                    <i class="bi bi-calendar-week text-blue-800 text-2xl"></i>
                    <span class="text-xl text-gray-600 font-semibold">Période actuelle :</span>
                    <span class="text-blue-900 text-2xl font-bold">{{ $periode_actuelle->nom }}</span>
                </div>
            </div>
        </div>
        <!-- Div du haut avec couleurs sobres -->
        <div class="bg-gradient-to-br from-white to-slate-100 rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 bg-gradient-to-r from-slate-700 to-gray-900 text-white">

                <!-- Bloc gauche : Enregistrement -->
                <div class="bg-slate-800">
                    <div class="text-center px-6 py-4 border-b border-slate-700">
                        <h2 class="text-lg font-semibold tracking-wide text-slate-100">
                            Consommation de
                            <span class="text-blue-400 font-bold">{{ $periode_actuelle->nom }}</span>
                        </h2>
                    </div>

                    <div class="flex divide-x divide-slate-700">
                        <!-- Section FS -->
                        <div class="w-1/2 text-center p-6 hover:bg-slate-700 transition duration-200">
                            <div class="text-3xl font-bold text-blue-300 mb-3">FS</div>
                            @if ($fs_submit)
                                <div class="flex flex-col items-center justify-center gap-3 text-green-400">
                                    <div class="flex justify-center items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-4xl"></i>
                                        <p class="text-lg font-semibold">Soumis</p>
                                    </div>
                                    <p class="text-xs text-gray-300 mt-1">Consommation soumise pour cette période</p>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center gap-3 text-red-400">
                                    <div class="flex justify-center items-center gap-2">
                                        <i class="bi bi-x-circle-fill text-4xl"></i>
                                        <p class="text-lg font-semibold">Non soumis</p>
                                    </div>
                                    <p class="text-xs text-gray-300 mt-1">Consommation non soumise pour cette période
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Section ASC -->
                        <div class="w-1/2 text-center p-6 hover:bg-slate-700 transition duration-200">
                            <div class="text-3xl font-bold text-teal-300 mb-3">ASC</div>
                            @if ($asc_submit)
                                <div class="flex flex-col items-center justify-center gap-3 text-green-400">
                                    <div class="flex justify-center items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-4xl"></i>
                                        <p class="text-lg font-semibold">Soumis</p>
                                    </div>
                                    <p class="text-xs text-gray-300 mt-1">Consommation soumise pour cette période</p>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center gap-3 text-red-400">
                                    <div class="flex justify-center items-center gap-2">
                                        <i class="bi bi-x-circle-fill text-4xl"></i>
                                        <p class="text-lg font-semibold">Non soumis</p>
                                    </div>
                                    <p class="text-xs text-gray-300 mt-1">Consommation non soumise pour cette période
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bloc droit : Résumé -->
                <div class="bg-slate-900">
                    <div class="text-center px-6 py-4 border-b border-slate-800">
                        <h2 class="text-lg font-semibold tracking-wide text-slate-100">
                            Toutes les consommations
                        </h2>
                    </div>

                    <div class="flex divide-x divide-slate-800">
                        <div class="w-1/2 text-center p-6 hover:bg-slate-800 transition">
                            <div class="text-3xl font-bold text-blue-300">FS</div>
                            <div class="text-4xl font-extrabold text-white">{{ $consommations_fs_total }}</div>
                            <p class="text-xs text-gray-400 mt-1">Total enregistrées</p>
                        </div>
                        <div class="w-1/2 text-center p-6 hover:bg-slate-800 transition">
                            <div class="text-3xl font-bold text-teal-300">ASC</div>
                            <div class="text-4xl font-extrabold text-white">{{ $consommations_asc_total }}</div>
                            <p class="text-xs text-gray-400 mt-1">Total enregistrées</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- Séparateur avec titre -->
        <div class="text-2xl text-teal-600 mt-8 mb-6 font-bold flex items-center justify-center gap-4">
            <hr class="flex-1 border-gray-300">
            <p class="whitespace-nowrap px-4">Date de soumission des consommations</p>
            <hr class="flex-1 border-gray-300">
        </div>
        <!-- Tables plus larges -->
        <div class="mt-6 flex justify-between gap-6">
            <!-- Table 1 - Plus large -->
            <div class="bg-white shadow-lg rounded-lg overflow-auto max-h-[400px] flex-1">
                <div class="text-xl font-semibold text-blue-900 mb-4">Formation sanitaire (FS)</div>
                <table class="w-full border-collapse">
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="bg-gray-100">
                            <th class="px-8 py-4 text-left text-sm font-medium text-blue-900 border-b w-1/2">Période de
                                la
                                consommation</th>
                            <th class="px-8 py-4 text-left text-sm font-medium text-blue-900 border-b w-1/2">Date de
                                soummission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consommations_fs as $conso)
                            <tr class="border-b hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-8 py-5 text-blue-900 font-medium">{{ $conso->periode->nom }}</td>
                                <td class="px-8 py-5 text-blue-900">{{ $conso->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-8 py-8 text-center text-gray-500 italic">
                                    Aucune consommation trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table 2 - Plus large -->
            <div class="bg-white shadow-lg rounded-lg overflow-auto max-h-[400px] flex-1">
                <table class="w-full border-collapse">
                    <div class="text-xl font-semibold mb-4 text-blue-900 ">Agent Santé Communautaire (ASC)</div>
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="bg-gray-100">
                            <th class="px-8 py-4 text-left text-sm font-medium text-blue-900 border-b w-1/2">Période de
                                la
                                consommation</th>
                            <th class="px-8 py-4 text-left text-sm font-medium text-blue-900 border-b w-1/2">Date de
                                soummission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consommations_asc as $conso)
                            <tr class="border-b hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-8 py-5 text-blue-900 font-medium">{{ $conso->periode->nom }}</td>
                                <td class="px-8 py-5 text-blue-900">{{ $conso->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-8 py-8 text-center text-gray-500 italic">
                                    Aucune consommation trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif(auth()->check() && in_array(auth()->user()->role->nom_role, ['Administrateur', 'District']))
        <div class="flex justify-between items-center relative mb-4">
            <h2 class="text-2xl font-semibold text-teal-600">
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <p>Tableau de bord
                        @if (in_array(auth()->user()->role->nom_role, ['Formation sanitaire', 'District']))
                            <span class="font-semibold text-[18px] text-gray-500"> |
                                {{ Auth::user()->entity['nom'] }}</span>
                        @endif
                    </p>
                </span>
            </h2>
            <div>
                <div class="flex bg-gray-100 border border-gray-300 gap-2 p-4 items-center rounded-md shadow-sm">
                    <i class="bi bi-calendar-week text-blue-800 text-2xl"></i>
                    <span class="text-xl text-gray-600 font-semibold">Période actuelle :</span>
                    <span class="text-blue-900 text-2xl font-bold">{{ $periode_actuelle->nom }}</span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 mb-4">
            @if (auth()->check() && auth()->user()->role->nom_role == 'Administrateur')
                <div class="flex flex-col w-48">
                    <label class="text-sm font-semibold text-blue-800 mb-2">Regions :</label>
                    <div class="relative">
                        <select wire:model="region_search" wire:change="chercherStatistiques"
                            class="w-full bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les régions</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">
                                    {{ $region->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex flex-col w-1/4">
                    <label class="text-sm font-semibold text-blue-800 mb-2">District :</label>
                    <div class="relative">
                        <select wire:model="district_search" wire:change="chercherStatistiques"
                            class="w-full bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les districts</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">
                                    {{ $district->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="flex flex-col w-1/4">
                <label class="text-sm font-semibold text-blue-800 mb-2">Formation sanitaire :</label>
                <div class="relative">
                    <select wire:model="fs_search" wire:change="chercherStatistiques"
                        class="w-full bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les formations sanitaires</option>
                        @foreach ($fs as $formation)
                            <option value="{{ $formation->id }}">
                                {{ $formation->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex flex-col w-1/4">
                <label class="text-sm font-semibold text-blue-800 mb-2">Période :</label>
                <div class="relative">
                    <select wire:model="periode_search" wire:change="chercherStatistiques"
                        class="w-full bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($periodes_all as $periode)
                            <option value="{{ $periode->id }}">
                                {{ $periode->nom }} : {{ $periode->mois_debut }} - {{ $periode->mois_fin }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="space-y-6 bg-opacity-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mb-4 gap-6">
                <div class="bg-slate-100 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div
                            class="bg-blue-100 p-3 rounded-full aspect-square w-12 h-12 flex items-center justify-center">
                            <i class="bi bi-hospital text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Formations Sanitaires</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $nb_fs }}</p>
                            <p class="text-sm text-gray-500">Total dans le district</p>
                        </div>
                    </div>
                </div>

                <!-- Nombre total d'ASC -->
                <div class="bg-slate-100 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div
                            class="bg-green-100 p-3 rounded-full aspect-square w-12 h-12 flex items-center justify-center">
                            <i class="bi bi-people text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Agents de Santé</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $nb_asc }}</p>
                            <p class="text-sm text-gray-500">Total dans le district</p>
                        </div>
                    </div>
                </div>
                @php
                    $reste_fs = $nb_fs - $nb_fs_soumission;
                    $reste_asc = $nb_fs - $nb_asc_soumission;
                @endphp
                <!-- FS ayant soumis -->
                <div class="bg-slate-100 rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div
                            class="bg-orange-100 p-3 rounded-full aspect-square w-12 h-12 flex items-center justify-center">
                            <i class="bi bi-check-circle text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">FS Soumissions</h3>
                            <p class="text-3xl font-bold text-orange-600">{{ $nb_fs_soumission }}</p>
                            <p class="text-sm text-gray-500">sur {{ $nb_fs }} FS | Non soumis :
                                {{ $reste_fs }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ASC ayant soumis -->
                <div class="bg-slate-100 rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div
                            class="bg-purple-100 p-3 rounded-full aspect-square w-12 h-12 flex items-center justify-center">
                            <i class="bi bi-person-check text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">ASC Soumissions</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ $nb_asc_soumission }}</p>
                            <p class="text-sm text-gray-500">sur {{ $nb_fs }} ASC (FS) | Non soumis :
                                {{ $reste_asc }}</p>
                        </div>
                    </div>
                </div>

                <!-- ✅ Consommation FS validée -->
                <div class="bg-slate-100 rounded-lg shadow-lg p-6 border-l-4 border-cyan-500">
                    <div class="flex items-center">
                        <div
                            class="bg-cyan-100 p-3 rounded-full aspect-square w-12 h-12 flex items-center justify-center">
                            <i class="bi bi-patch-check text-cyan-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Consommations FS Validés</h3>
                            <p class="text-3xl font-bold text-cyan-600">{{ $nb_fs_valide }}</p>
                            <p class="text-sm text-gray-500">Sur {{ $nb_fs_soumission }} | Reste à validé :
                                {{ $nb_fs_soumission - $nb_fs_valide }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ✅ Consommation ASC validée -->
                <div class="bg-slate-100 rounded-lg shadow-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div
                            class="bg-indigo-100 p-3 rounded-full aspect-square w-12 h-12 flex items-center justify-center">
                            <i class="bi bi-patch-check-fill text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Consommations ASC Validés</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $nb_asc_valide }}</p>
                            <p class="text-sm text-gray-500">Sur {{ $nb_asc_soumission }} | Reste à validé :
                                {{ $nb_asc_soumission - $nb_asc_valide }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="bg-slate-100 p-6 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <i class="bi bi-check2-square text-indigo-600 text-xl mr-2"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Complétude des Rapports</h3>
                </div>
                <div class="space-y-6">
                    @php
                        $fs_pourcentage = $nb_fs > 0 ? intval(($nb_fs_soumission / $nb_fs) * 100) : 0;
                        $asc_pourcentage = $nb_fs > 0 ? intval(($nb_asc_soumission / $nb_fs) * 100) : 0;
                    @endphp
                    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 1000)" class="space-y-6">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Formation Sanitaire (FS) <span
                                        class="text-blue-600"> | {{ $nb_fs_soumission }} sur
                                        {{ $nb_fs }}</span></span>
                                <span class="text-sm font-semibold text-indigo-600">{{ $fs_pourcentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-indigo-600 h-4 rounded-full transition-all duration-1000 ease-in-out"
                                    :style="loaded ? 'width: {{ $fs_pourcentage }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Agents Santé Communautaire (ASC) <span
                                        class="text-blue-600"> | {{ $nb_asc_soumission }} sur
                                        {{ $nb_fs }}</span></span>
                                <span class="text-sm font-semibold text-teal-600">{{ $asc_pourcentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-teal-500 h-4 rounded-full transition-all duration-1000 ease-in-out"
                                    :style="loaded ? 'width: {{ $asc_pourcentage }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-100 p-6 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <i class="bi bi-clock-history text-orange-500 text-xl mr-2"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Promptitude des Soumissions</h3>
                </div>

                <div class="space-y-6">
                    @php
                        $fs_promptitude = $nb_fs > 0 ? intval(($fs_prompt / $nb_fs) * 100) : 0;
                        $asc_promptitude = $nb_fs > 0 ? intval(($asc_prompt / $nb_fs) * 100) : 0;
                    @endphp
                    <!-- Promptitude FS -->
                    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 1000)" class="space-y-6">

                        <!-- Barre de complétude FS -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Formation Sanitaire (FS) <span
                                        class="text-blue-600"> | {{ $fs_prompt }} sur
                                        {{ $nb_fs }}</span></span>
                                <span class="text-sm font-semibold text-orange-600">{{ $fs_promptitude }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-orange-500 h-4 rounded-full transition-all duration-1000 ease-in-out"
                                    :style="loaded ? 'width: {{ $fs_promptitude }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        <!-- Barre de complétude ASC -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Agents Santé Communautaire (ASC) <span
                                        class="text-blue-600"> | {{ $asc_prompt }} sur
                                        {{ $nb_fs }}</span></span>
                                <span class="text-sm font-semibold text-yellow-600">{{ $asc_promptitude }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-yellow-400 h-4 rounded-full transition-all duration-1000 ease-in-out"
                                    :style="loaded ? 'width: {{ $asc_promptitude }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-4">

            <!-- Historique des soumissions -->
            <div class="lg:col-span-2 xl:col-span-2 backdrop-blur-sm bg-slate-100 rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <i class="bi bi-clock-history text-purple-600 text-xl mr-2"></i>
                        <h3 class="text-xl font-semibold text-gray-800">Historique des soumission des consommations
                        </h3>

                    </div>
                    <div class="flex gap-2">
                        <button>Exporter</button>
                    </div>
                </div>

                <div class=" rounded-lg shadow-inner max-h-80 overflow-auto max-h-[250px]">
                    <table class="min-w-full divide-y divide-gray-200 bg-gray-50/80 text-sm text-gray-800 rounded-lg">
                        <thead class="bg-gray-200 text-gray-700 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">Formation Sanitaire</th>
                                <th class="px-4 py-3 text-left font-medium">Type structure</th>
                                <th class="px-4 py-3 text-left font-medium">Période</th>
                                <th class="px-4 py-3 text-left font-medium">Date soumission</th>
                                <th class="px-4 py-3 text-left font-medium">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($soumissions as $soumission)
                                <tr class="hover:bg-gray-100/70 transition">
                                    <td class="px-4 py-2">{{ $soumission->formationSanitaire->nom }}</td>
                                    <td class="px-4 py-2">{{ $soumission->acteur }}</td>
                                    <td class="px-4 py-2">{{ $soumission->periode->nom }}</td>
                                    <td class="px-4 py-2">{{ $soumission->submitted_at }}</td>
                                    <td class="px-4 py-2">{{ $soumission->etat }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-4">
                                        Aucune consommation somise ou consommation déjà validée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Produits les plus demandés -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <i class="bi bi-graph-up-arrow text-teal-600 text-xl mr-2"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Médicaments les plus demandés</h3>
                </div>

                <div class=" rounded-lg shadow-inner max-h-80 overflow-auto max-h-[250px]">
                    <table class="min-w-full divide-y divide-gray-200 bg-gray-50/80 text-sm text-gray-800 rounded-lg">
                        <thead class="bg-gray-200 text-gray-700 sticky top-0">
                            <tr>
                                <th class="text-left px-4 py-3 font-medium">Medicaments</th>
                                <th class="text-right px-4 py-3 font-medium">Quantitété Commandée</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($topCommandes as $produit)
                                <tr class="hover:bg-gray-100/80 transition">
                                    <td class="px-4 py-3">{{ $produit->nom }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-teal-600">
                                        {{ number_format($produit->total_commande, 0, ',', ' ') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-4">
                                        Aucun médicament n'a été commandé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Deuxième ligne : Médicaments en rupture -->
        <div class=" mt-6">
            <div class="lg:col-span-2 xl:col-span-2 backdrop-blur-sm bg-slate-100 rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <i class="bi bi-clock-history text-purple-600 text-xl mr-2"></i>
                        <h3 class="text-xl font-semibold text-gray-800">Historique de validation des consommation</h3>
                    </div>
                    <div class="flex gap-2">
                        <button>Exporter</button>
                    </div>
                </div>
                <div class=" rounded-lg shadow-inner max-h-80 overflow-auto max-h-[400px]">
                    <table class="min-w-full divide-y divide-gray-200 bg-gray-50/80 text-sm text-gray-800 rounded-lg">
                        <thead class="bg-gray-200 text-gray-700 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">Formation Sanitaire</th>
                                <th class="px-4 py-3 text-left font-medium">Type structure</th>
                                <th class="px-4 py-3 text-left font-medium">Période</th>
                                <th class="px-4 py-3 text-left font-medium">Date validation</th>
                                <th class="px-4 py-3 text-left font-medium">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($validations as $validation)
                                <tr class="hover:bg-gray-100/70 transition">
                                    <td class="px-4 py-2">{{ $validation->formationSanitaire->nom }}</td>
                                    <td class="px-4 py-2">{{ $validation->acteur }}</td>
                                    <td class="px-4 py-2">{{ $validation->periode->nom }}</td>
                                    <td class="px-4 py-2">{{ $validation->submitted_at }}</td>
                                    <td class="px-4 py-2">{{ $validation->etat }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-4">
                                        Aucune consommation déjà validée pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
@endif
</div>
