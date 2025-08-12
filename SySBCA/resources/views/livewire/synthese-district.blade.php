<div>
    <div wire:loading wire:target="rechercherSynthese, nextSlide, previousSlide, goToSlide, exporterPDF"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>
    <div class="flex flex-wrap justify-between items-center relative mb-6 gap-4">
        <!-- Titre -->
        <h2 class="flex items-center gap-3">
            <!-- Icône -->
            <div
                class="bg-gradient-to-br from-teal-600 to-emerald-700 w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="bi bi-file-earmark-text text-xl"></i>
            </div>

            <!-- Texte titre -->
            <div class="flex flex-col">
                <p class="text-2xl font-bold text-teal-600">Synthèse des commandes du district</p>
                @if (in_array(auth()->user()->role->nom_role, ['Formation saniraire', 'District']))
                    <span class="text-gray-500 text-lg font-normal">{{ Auth::user()->entity['nom'] }}</span>
                @endif
            </div>
        </h2>

        <!-- Actions -->
        @if (count($visibleCards) > 1)
            <div class="relative">
                <!-- Bouton principal -->
                <button
                    class="bg-blue-600 text-white hover:bg-blue-800 font-medium py-2 px-4 rounded-lg shadow-sm flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-800"
                    onclick="document.getElementById('exportMenu').classList.toggle('hidden')">
                    <i class="bi bi-download"></i> Exporter
                    <i class="bi bi-caret-down-fill text-xs"></i>
                </button>

                <!-- Menu dropdown -->
                <div id="exportMenu"
                    class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-20">
                    <button wire:click="exporterPDF"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                        <i class="bi bi-file-earmark-pdf text-red-600"></i> PDF
                    </button>
                    <button wire:click="exporterExcel"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                        <i class="bi bi-file-earmark-excel text-green-600"></i> Excel
                    </button>
                </div>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('exportMenu');
            if (!e.target.closest('#exportMenu') && !e.target.closest('button')) {
                menu.classList.add('hidden');
            }
        });
    </script>
    <div
        class="relative mb-4 bg-gradient-to-r from-white via-blue-50 to-indigo-50 border border-blue-100 px-6 py-4 rounded-xl shadow-lg backdrop-blur-sm">
        <!-- Ligne décorative supérieure -->
        <div
            class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-600 rounded-t-xl">
        </div>

        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4 text-sm font-medium text-gray-700 flex-wrap">
                @if (auth()->check() && auth()->user()->role->nom_role == 'Administrateur')
                    <div
                        class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-lg border border-blue-200/50 shrink-0">
                        <span class="text-blue-900 font-bold truncate max-w-[150px]" title="{{ $district_info->nom }}">
                            District : {{ $district_info->nom }}
                        </span>
                    </div>
                    <div class="w-px h-6 bg-gradient-to-b from-transparent via-blue-300 to-transparent shrink-0">
                    </div>
                @endif

                <div
                    class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-lg border border-blue-200/50 shrink-0">
                    <span class="text-gray-600 whitespace-nowrap">Type :</span>
                    <span class="text-indigo-800 font-bold truncate max-w-[120px]" title="{{ $type_synthese }}">
                        {{ $type_synthese }}
                    </span>
                </div>
                <div class="w-px h-6 bg-gradient-to-b from-transparent via-blue-300 to-transparent shrink-0">
                </div>
                <div
                    class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-lg border border-blue-200/50 shrink-0">
                    <span class="text-gray-600 whitespace-nowrap">Période :</span>
                    <span class="text-green-800 font-bold truncate max-w-[120px]" title="{{ $periode_info->nom }}">
                        {{ $periode_info->nom }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                @if (auth()->check() && auth()->user()->role->nom_role == 'Administrateur')
                    <div class="relative">
                        <select wire:model.live="districts_search" wire:change="rechercherSynthese"
                            class="w-40 bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->nom }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                @endif
                <div class="relative">
                    <select wire:model.live="type_synthese" wire:change="rechercherSynthese"
                        class="w-32 bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer text-center">
                        <option value="FS">FS</option>
                        <option value="ASC">ASC</option>
                        <option value="FS+ASC">FS + ASC</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <select wire:model.live="periode_search" wire:change="rechercherSynthese"
                        class="w-64 bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        @foreach ($periodes_all as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->nom }} :
                                {{ $periode->mois_debut }}-{{ $periode->mois_fin }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carrousel Synthèse -->
    <div class="overflow-hidden rounded-3xl relative min-h-[400px]">
        @if (empty($visibleCards) || count($visibleCards) === 0)
            <div class="flex-shrink-0 w-full" style="min-width: 100%; flex: 0 0 100%;">
                <div
                    class="text-center text-gray-500 py-32 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-2xl font-medium text-gray-400 mb-2">Aucune synthèse enregistrée</p>
                    <p class="text-gray-400">Les données apparaîtront ici une fois disponibles</p>
                </div>
            </div>
        @else
            <div class="flex transition-transform duration-500 ease-in-out carousel-container"
                style="transform: translateX(-{{ $currentSlide * 100 }}%)">
                @foreach ($visibleCards as $index => $synthese)
                    @php
                        $stock_theo =
                                        $synthese['qte_en_stock'] -
                                        $synthese['qte_utilisee'] -
                                        $synthese['qte_retour_cameg'] -
                                        $synthese['perte_avarie'] -
                                        $synthese['perimee'];
                                    $perte_non_dec = $synthese['qte_restante'] - $stock_theo;
                    @endphp
                    <div class="flex-shrink-0 w-full flex justify-center px-2 min-w-[70%]">
                        <div
                            class="bg-white rounded-3xl border border-teal-600 overflow-hidden w-full max-w-4xl mx-auto shadow-lg hover:shadow-xl transition-all duration-300">

                            <!-- Header avec gradient -->
                            <div
                                class="bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-700 px-6 py-3 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24">
                                </div>
                                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm text-white">
                                                {{ $synthese['medicament']['code'] }}
                                            </div>
                                            <div>
                                                <h2 class="text-xl font-bold text-white mb-1">
                                                    {{ $synthese['medicament']['nom'] ?? 'Médicament inconnu' }}
                                                </h2>
                                                <p class="text-teal-100 text-sm">Synthèse trimestrielle</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium">
                                                <i class="bi bi-check-circle mr-1"></i>Validé
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Corps de la carte -->
                            <div class="p-4">
                                <!-- Métriques principales -->
                                <div class="grid grid-cols-3 gap-6 mb-3">
                                    <!-- Stock total en début -->
                                    <div
                                        class="bg-slate-100 border-l-4 border-blue-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div
                                                class="bg-blue-100 text-blue-600 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4zM3 8a1 1 0 000 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a1 1 0 100-2H3zm8 6a1 1 0 11-2 0V9a1 1 0 112 0v5z" />
                                                </svg>
                                            </div>
                                            <span class="text-blue-700">Stock total</span>
                                        </div>
                                        <div class="text-3xl font-bold text-blue-700">
                                            {{ $synthese['qte_en_stock'] ?? 0 }}</div>
                                    </div>

                                    <!-- CMM ajustée -->
                                    <div
                                        class="bg-slate-100 border-l-4 border-orange-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div
                                                class="bg-orange-100 text-orange-600 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <span class="text-orange-700">CMM ajustée</span>
                                        </div>
                                        <div class="text-3xl font-bold text-orange-700">{{ $synthese['cmma'] ?? 0 }}
                                        </div>
                                    </div>

                                    <!-- Stock de sécurité -->
                                    <div
                                        class="bg-slate-100 border-l-4 border-teal-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div
                                                class="bg-teal-100 text-teal-600 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <span class="text-teal-700">Stock de sécurité</span>
                                        </div>
                                        <div class="text-3xl font-bold text-teal-700">
                                            {{ $synthese['stock_securite'] ?? 0 }}</div>
                                    </div>

                                    <!-- Quantité commandée -->
                                    <div
                                        class="bg-slate-100 border-l-4 border-gray-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div
                                                class="bg-gray-200 text-gray-700 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-700">Quantité commandée</span>
                                        </div>
                                        <div class="text-3xl font-bold text-gray-700">
                                            {{ $synthese['cmd_trim_svt'] ?? 0 }}</div>
                                    </div>

                                    <!-- Quantité accordée -->
                                    <div
                                        class="bg-slate-100 border-l-4 border-indigo-800 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div
                                                class="bg-indigo-200 text-indigo-800 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                <i class="bi bi-check2-square text-indigo-800 text-lg"></i>
                                            </div>
                                            <span class="text-indigo-800">Quantité accordée</span>
                                        </div>
                                        @if ($synthese['qte_accordee'])
                                            <div class="text-3xl font-bold text-indigo-800">
                                                {{ $synthese['qte_accordee'] }}</div>
                                        @else
                                            <div class="text-indigo-800 text-lg font-medium">
                                                 N/A
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Différence ajout/retrait (si applicable) -->
                                    @if ($synthese['qte_accordee'] && $synthese['qte_accordee'] != $synthese['cmd_trim_svt'])
                                        <div
                                            class="bg-gradient-to-br rounded-xl border p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center
                                        {{ $synthese['qte_accordee'] > $synthese['cmd_trim_svt'] ? 'from-emerald-50 to-emerald-100 border-emerald-200' : 'from-red-50 to-red-100 border-red-200' }}">
                                            <div class="flex items-center gap-2 mb-3">
                                                <i
                                                    class="text-xl {{ $synthese['qte_accordee'] > $synthese['cmd_trim_svt'] ? 'bi bi-arrow-up-circle text-emerald-600' : 'bi bi-arrow-down-circle text-red-600' }}"></i>
                                                <h3
                                                    class="text-lg font-bold {{ $synthese['qte_accordee'] > $synthese['cmd_trim_svt'] ? 'text-emerald-800' : 'text-red-800' }}">
                                                    {{ $synthese['qte_accordee'] > $synthese['cmd_trim_svt'] ? 'Ajout' : 'Retiré' }}
                                                </h3>
                                            </div>
                                            <div
                                                class="text-3xl font-bold {{ $synthese['qte_accordee'] > $synthese['cmd_trim_svt'] ? 'text-emerald-800' : 'text-red-800' }}">
                                                {{ $synthese['qte_accordee'] > $synthese['cmd_trim_svt'] ? '+' : '' }}{{ $synthese['qte_accordee'] - $synthese['cmd_trim_svt'] }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bouton toggle détails -->
                                <div class="flex justify-center">
                                    <button type="button"
                                        class="toggle-details px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm rounded-lg transition-colors">
                                        Voir plus
                                    </button>
                                </div>

                                <!-- Détails étendus (cachables) -->
                                <div
                                    class="details-section bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200 mt-2 hidden">
                                    <h3 class="text-gray-800 font-bold text-lg mb-4 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-teal-600 rounded-full"></div>
                                        Informations détaillées du trimestre
                                    </h3>
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Stock début</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['qte_dispo_deb_periode'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Qté reçue</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['qte_recu'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Qté utilisée</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['qte_utilisee'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Bénéficiaires</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['nb_beneficiaire'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Périmé</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['perimee'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Pertes et avariées</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['perte_avarie'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Retournés CAMEG</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['qte_retour_cameg'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Jours de rupture</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['nb_jour_rupture'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3 text-sm mt-4">
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Stock réel en
                                                fin</span>
                                            <span
                                                class="font-bold text-gray-800">{{ $synthese['qte_restante'] ?? 0 }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                            <span class="text-gray-600 font-medium">Stock théorique</span>
                                            <span class="font-bold text-gray-800">{{ $stock_theo }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center p-3 bg-white rounded-lg border 
    {{ $perte_non_dec > 0 ? 'border-yellow-300' : 'border-red-200' }}">
                                            <span
                                                class="{{ $perte_non_dec > 0 ? 'text-yellow-600' : 'text-red-600' }} font-medium">
                                                Ecart
                                            </span>
                                            <span
                                                class="font-bold {{ $perte_non_dec > 0 ? 'text-yellow-600' : 'text-red-600' }}">
                                                {{ $perte_non_dec }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        @endif
    </div>

    <!-- Boutons de navigation -->
    @if (count($visibleCards) > 1)
        <div class="flex justify-between items-center mt-6">
            <button wire:click="previousSlide"
                class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                {{ $currentSlide <= 0 ? 'disabled' : '' }}>
                <i class="bi bi-chevron-left"></i>
                Précédent
            </button>
            <div
                class="mx-auto flex items-center gap-3 bg-white/40 backdrop-blur-sm px-4 py-2 rounded-full shadow-md z-10">

                <!-- Compteur -->
                <div class="flex items-center gap-1 text-sm font-medium">
                    <span class="text-teal-600 font-bold">{{ $currentSlide + 1 }}</span>
                    <span class="text-gray-500">/ {{ count($visibleCards) }}</span>
                </div>

                <!-- Séparateur -->
                @if (count($visibleCards) > 1)
                    <div class="w-px h-4 bg-gray-300"></div>
                @endif

                <!-- Indicateurs (dots) -->
                @if (count($visibleCards) > 1)
                    <div class="flex items-center gap-1">
                        @for ($i = 0; $i < count($visibleCards); $i++)
                            <button wire:click="goToSlide({{ $i }})"
                                class="h-2.5 rounded-full transition-all duration-300 
                        {{ $currentSlide == $i ? 'bg-teal-600 w-6 shadow-md' : 'bg-gray-300 hover:bg-gray-400 w-2.5' }}">
                            </button>
                        @endfor
                    </div>
                @endif

            </div>
            <button wire:click="nextSlide"
                class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                {{ $currentSlide >= count($visibleCards) - 1 ? 'disabled' : '' }}>
                Suivant
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    @endif

    <script>
        // Fonction pour basculer les détails
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('toggle-details')) {
                const detailsSection = e.target.closest('.p-4').querySelector('.details-section');
                if (detailsSection) {
                    detailsSection.classList.toggle('hidden');
                    e.target.textContent = detailsSection.classList.contains('hidden') ? 'Voir plus' : 'Voir moins';
                }
            }
        });

        // Navigation par clavier
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                @this.call('previousSlide');
            } else if (e.key === 'ArrowRight') {
                @this.call('nextSlide');
            }
        });

        // Support du swipe sur mobile
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        const carousel = document.querySelector('.overflow-hidden');

        if (carousel) {
            carousel.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isDragging = true;
            });

            carousel.addEventListener('touchmove', function(e) {
                if (!isDragging) return;
                currentX = e.touches[0].clientX;
                e.preventDefault();
            });

            carousel.addEventListener('touchend', function(e) {
                if (!isDragging) return;

                const diffX = startX - currentX;

                if (Math.abs(diffX) > 50) { // Seuil minimum pour le swipe
                    if (diffX > 0) {
                        @this.call('nextSlide');
                    } else {
                        @this.call('previousSlide');
                    }
                }

                isDragging = false;
            });
        }

        // Mise à jour automatique du carrousel quand les données changent
        document.addEventListener('livewire:updated', function() {
            // Réinitialiser les événements après mise à jour Livewire
            console.log('Carrousel mis à jour');
        });
    </script>
</div>
