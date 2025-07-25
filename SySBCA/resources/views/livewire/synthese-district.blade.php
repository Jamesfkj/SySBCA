<div>
    <div wire:loading wire:target="rechercherSynthese" class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>
    <div class="flex justify-between items-center relative mb-4">
        <h2 class="text-2xl font-semibold text-teal-600">
            <span class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <p>Synthèse des commandes du district <span class="font-semibold text-[18px] text-gray-500"> |
                        {{ Auth::user()->entity['nom'] }}</span></p>
            </span>
        </h2>
    </div>
    <div>
        <div>
            <button id="toggleButton" onclick="toggleInstructions()"
                class="flex items-center gap-2 text-teal-600 text-decoration-underline">
                <span id="buttonText">Masquer les instructions</span>
                <svg id="arrowIcon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="instructionsDiv" class="overflow-x-auto transition-all duration-300 ease-in-out mb-1"
                style="max-height: 200px; opacity: 1">
                <div class="gap-2 items-center bg-gray-50 border border-gray-200 px-4 py-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-700">
                        * Filtrer les données de la table en changeant la période et le type de structure entre FS, ASC
                        ou FS + ASC
                    </p>
                </div>
            </div>
            <div class="mb-1 flex justify-between">
                <div class="flex items-center gap-2 text-[15px] text-gray-600">
                    <p>
                        Type de synthèse : 
                        <span class="text-blue-900 font-semibold">{{ $type_synthese }}</span>
                    </p>
                    <span>|</span>
                    <p>
                        Consommation de la période :
                        <span class="text-blue-900 font-semibold">{{ $periode_info->nom }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <select wire:model.live="type_synthese" wire:change="rechercherSynthese"
                        class="w-32 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-600">
                        <option value="FS">FS</option>
                        <option value="ASC">ASC</option>
                        <option value="FS+ASC">FS + ASC</option>
                    </select>

                    <select wire:model.live="periode_search" wire:change="rechercherSynthese"
                        class="w-72 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-600">
                        @foreach ($periodes_all as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->nom }} :
                                {{ $periode->mois_debut }}-{{ $periode->mois_fin }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($synthese_district as $synthese)
            <div x-data="{ open: false }"
                class="bg-white shadow-lg rounded-3xl border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-teal-200">

                <!-- Header produit -->
                <div
                    class="bg-gradient-to-r from-teal-700 to-teal-600 px-4 py-3 rounded-t-3xl flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-white tracking-wide truncate">
                        {{ $synthese->medicament->nom }}
                    </h2>
                    <button @click="open = !open"
                        class="text-xs text-teal-100 hover:text-white hover:bg-teal-600 px-2 py-1 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 flex-shrink-0">
                        <span x-show="!open" class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Plus
                        </span>
                        <span x-show="open" class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            Moins
                        </span>
                    </button>
                </div>

                <!-- Corps de la carte -->
                <div class="px-4 py-4 text-sm">
                    <!-- Informations principales (en 2 colonnes) -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Quantité en stock -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                            <span class="font-semibold text-blue-800">Quantité en stock</span>
                            <div class="text-blue-800 font-bold text-lg">
                                {{ $synthese->qte_en_stock }}
                            </div>
                        </div>

                        <!-- Stock de sécurité -->
                        <div class="bg-red-50 border border-red-100 rounded-xl p-3">
                            <span class="font-semibold text-red-700">Stock de sécurité</span>
                            <div class="text-red-800 font-bold">
                                {{ $synthese->stock_securite }}
                            </div>
                        </div>

                        <!-- CMM ajustée -->
                        <div class="bg-teal-50 border border-teal-200 rounded-xl p-3">
                            <span class="font-semibold text-teal-700 ">CMM ajustée</span>
                            <div class="text-teal-800 font-bold">
                                {{ $synthese->cmma }}
                            </div>
                        </div>

                        <!-- Quantité commandée -->
                        <div class="bg-teal-50 border border-teal-200 rounded-xl p-3">
                            <span class="font-semibold text-teal-700 text-[13px]">Quantité commandée</span>
                            <div class="text-teal-800 font-bold">
                                {{ $synthese->cmd_trim_svt }}
                            </div>
                        </div>

                        <!-- Quantité accordée ou formulaire -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 col-span-2">
                            <span class="font-semibold text-blue-700">Qté accordée par le district</span>

                                <div class="text-blue-800 font-bold">
                                    {{$synthese->qte_accordee ?? '--'}}
                                </div>
                        </div>
                    </div>
                    <!-- Détails étendus -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-4">
                        <div
                            class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-100 space-y-4">
                            <h3 class="text-gray-700 font-semibold text-sm flex items-center gap-2">
                                <div class="w-2 h-2 bg-teal-700 rounded-full"></div>
                                Informations détaillées (Du trimestre)
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Stock en début :</span>
                                    <span class="font-semibold">{{ $synthese->qte_dispo_deb_periode }}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Qté reçue :</span>
                                    <span class="font-semibold">{{ $synthese->qte_recu }}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Qté utilisée :</span>
                                    <span class="font-semibold">{{ $synthese->qte_utilisee }}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Bénéficiaires :</span>
                                    <span class="font-semibold">{{ $synthese->nb_beneficiaire }}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Périmé :</span>
                                    <span class="font-semibold">{{ $synthese->perimee }}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Pertes & avariées :</span>
                                    <span class="font-semibold">{{ $synthese->perte_avarie}}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Retour CAMEG :</span>
                                    <span class="font-semibold">{{ $synthese->qte_retour_cameg }}</span>
                                </div>
                                <div class="flex justify-between border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Jours rupture :</span>
                                    <span class="font-semibold">{{ $synthese->nb_jour_rupture}}</span>
                                </div>
                                <div class="flex justify-between col-span-2 border border-gray-300 bg bg-white p-1 rounded">
                                    <span class="text-gray-600">Stock en fin :</span>
                                    <span class="font-semibold">{{ $synthese->qte_restante }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center text-gray-500 py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-lg font-medium text-gray-400">Aucune consommation enregistrée</p>
                    <p class="text-sm text-gray-400 mt-1">Les données apparaîtront ici une fois disponibles</p>
                </div>
            </div>
        @endforelse
    </div>
    <script>
        let isVisible = true;
        // Fonction pour afficher / masquer la section des instructions
        function toggleInstructions() {
            const instructionsDiv = document.getElementById('instructionsDiv');
            const buttonText = document.getElementById('buttonText');
            const arrowIcon = document.getElementById('arrowIcon');

            if (isVisible) {
                // Masquer les instructions
                instructionsDiv.style.maxHeight = '0px';
                instructionsDiv.style.opacity = '0';
                instructionsDiv.style.marginBottom = '0px';
                buttonText.textContent = 'Afficher les instructions';
                arrowIcon.style.transform = 'rotate(-90deg)';
            } else {
                // Afficher les instructions
                instructionsDiv.style.maxHeight = '200px';
                instructionsDiv.style.opacity = '1';
                instructionsDiv.style.marginBottom = '16px';
                buttonText.textContent = 'Masquer les instructions';
                arrowIcon.style.transform = 'rotate(0deg)';
            }

            isVisible = !isVisible;
        }
    </script>
</div>