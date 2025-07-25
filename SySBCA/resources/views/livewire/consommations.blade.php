<div>
    <!-- Message flash -->
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
            role="alert">
            <span>{{ session('message') }}</span>
        </div>
    @endif
    <!-- Barre de chargement -->
    <div wire:loading
        wire:target="afficherFormulaire,afficherTableau,filtrerParPériode,ajouterConsommation,showEditInput,supprimerDonneesTemporaires,choix,chargerDepuisSession,chargerMedicaments,chercherConsommations,enregistrerTemporairement,enregistrerQteAccorde"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>
    <!-- En-tête -->
    <div class="flex justify-between items-center relative mb-4">
        <h2 class="text-2xl font-semibold text-teal-600">
            @if ($tableauVisible)
                <span class="flex items-center gap-2">
                    <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-box-fill"></i>
                    </div>
                    <p>Consommations des médicaments <span class="font-semibold text-[18px] text-gray-500"> |
                            {{ Auth::user()->entity['nom'] }}</span></p>
                </span>
            @elseif ($formulaireVisible)
                <span class="flex items-center gap-2">
                    <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-plus"></i>
                    </div>
                    <p>Ajouter une consommation<span class="font-semibold text-[18px] text-gray-500"> |
                            {{ Auth::user()->entity['nom'] }}</span></p>
                </span>
            @endif
        </h2>
        <div>
            <!-- Boutons de navigation -->
            @if ($tableauVisible && auth()->check() && auth()->user()->role->nom_role == 'Formation sanitaire')
                <button wire:click="afficherFormulaire()"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>
                        Nouvelle consommation
                    </span>
                </button>
            @endif

            @if ($formulaireVisible)
                <button wire:click="afficherTableau"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-eye"></i>
                        </div>
                        Liste des consommations
                    </span>
                </button>
            @endif
        </div>
    </div>
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white">
        @if ($tableauVisible)
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
                        * Filtrer les données de la table en changeant la période et le type de structure entre FS et
                        ASC.
                    </p">
                    @if (auth()->check() && auth()->user()->role->nom_role === 'District')
                        <p class="text-sm text-gray-700">* Cliquer sur le petit bic pour enrégistrer ou modifier la quantité accordée.</p>
                    @endif
                </div>
            </div>
            <div class="mb-1 flex justify-between bg-gray-50  px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-[15px] text-gray-600">
                    @if (auth()->check() && auth()->user()->role->nom_role === 'District')
                        <p>
                            Formation sanitaire :
                            <span class="text-blue-900 font-semibold">{{ $fs_choisie->nom }}</span>
                        </p>
                        <span>|</span>
                    @endif
                    <p>
                        Structure :
                        <span class="text-blue-900 font-semibold">{{ $structure_defaut }}</span>
                    </p>
                    <span>|</span>
                    <p>
                        Période :
                        <span class="text-blue-900 font-semibold">{{ $periode_actuelle->nom }}</span>
                    </p>
                </div>
                <div>
                    <input type="text" wire:model.live="search" placeholder="Rechercher un médicament..."
                    class="w-96 rounded-full border border-gray-400 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                </div>
                <div class="flex items-center gap-2">
                    @if (auth()->check() && auth()->user()->role->nom_role === 'District')
                        <select wire:model.live="fs" wire:change="chercherConsommations"
                            class="w-44 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-600">
                            @foreach ($formation_sanitaire as $fs)
                                <option value="{{ $fs->id }}">{{ $fs->nom }}</option>
                            @endforeach
                        </select>
                    @endif

                    <select wire:model.live="structure_defaut" wire:change="chercherConsommations"
                        class="w-28 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-blue-600">
                        <option value="FS">FS</option>
                        <option value="ASC">ASC</option>
                    </select>

                    <select wire:model.live="periode_search" wire:change="chercherConsommations"
                        class="w-72 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-blue-600">
                        @foreach ($periodes_all as $periode)
                            <option value="{{ $periode->id }}">
                                {{ $periode->nom }} : {{ $periode->mois_debut }}-{{ $periode->mois_fin }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                @forelse ($consommations_all as $consommation)
                    <div wire:key="consommation-{{ $consommation->consommation_id }}"
                        class="bg-white shadow-lg rounded-3xl border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-teal-200 card-wrapper">
                        <!-- Header produit -->
                        <div
                            class="bg-gradient-to-r from-teal-700 to-teal-600 px-4 py-2 rounded-t-3xl flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-white tracking-wide truncate">
                                {{ $consommation->medicament->nom }}
                            </h2>
                            <button type="button"
                                class="text-xs text-teal-100 hover:text-white hover:bg-teal-600 px-2 py-1 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 flex-shrink-0 toggle-button">
                                <span class="flex items-center gap-1 label-open border-1 border-white">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Plus
                                </span>
                                <span class="flex items-center gap-1 hidden label-close">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    Moins
                                </span>
                            </button>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="px-4 py-4 text-sm">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                                    <span class="font-semibold text-blue-700">Quantité en stock</span>
                                    <div class="text-blue-800 font-bold text-lg">
                                        {{ $consommation->qte_en_stock }}
                                    </div>
                                </div>
                                <div class="bg-teal-50 border border-teal-200 rounded-xl p-3">
                                    <span class="font-semibold text-teal-700">Stock de sécurité</span>
                                    <div class="text-teal-800 font-bold">
                                        {{ $consommation->stock_securite }}
                                    </div>
                                </div>
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-3">
                                    <span class="font-semibold text-orange-700">CMM ajustée</span>
                                    <div class="text-orange-800 font-bold">
                                        {{ $consommation->cmma }}
                                    </div>
                                </div>
                                <div class="bg-gray-100 border border-gray-200 rounded-xl p-3">
                                    <span class="font-semibold text-gray-700 text-[13px]">Quantité commandée</span>
                                    <div class="text-gray-700 font-bold text-lg">
                                        {{ $consommation->cmd_trim_svt }}
                                    </div>
                                </div>  
                                @php
                                    $user = auth()->user();
                                    $role = $user?->role->nom_role;
                                    $accordee = $consommation->qte_accordee;
                                    $medicament_id = $consommation->medicament_id;
                                    $consommation_id = $consommation->consommation_id;
                                @endphp
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 col-span-2">
                                    <span class="font-semibold text-blue-700">Qté accordée par le district</span>
                                    @if ($role === 'Formation sanitaire')
                                        <div class="text-blue-800 font-bold mt-1 text-lg">
                                            {{ $accordee ?? '--' }}
                                        </div>
                                    @elseif ($role === 'District')
                                        @if ($not_edit[$medicament_id])
                                            <div class="text-blue-800 font-bold mt-1 flex justify-between text-lg">
                                                {{ $accordee ?? '--' }}
                                                <button type="submit"
                                                    wire:click="showEditInput({{ $medicament_id }}, {{ $consommation_id }})"
                                                    class="px-3 py-1 bg-white text-blue-600 rounded-md hover:bg-blue-100 font-medium">
                                                    <i class="bi bi-pen"></i>
                                                </button>
                                            </div>
                                        @endif
                                        @if ($edit[$medicament_id])
                                            <form
                                                wire:submit.prevent="enregistrerQteAccorde({{ $consommation_id }}, {{ $consommation->medicament_id }})"
                                                class="mt-2">
                                                <div class="flex gap-2 items-center">
                                                    <input type="number" wire:model="quantites_accordees.{{ $medicament_id }}"
                                                        placeholder="Saisir quantité"
                                                        class="flex-1 text-xs px-2 py-1 border border-blue-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        min="0" step="1" required>
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs font-medium">
                                                        OK
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <!-- Détails étendus -->
                            <div class="mt-4 details hidden transition duration-300 ease-out">
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-100 space-y-4">
                                    <h3 class="text-gray-700 font-semibold text-sm flex items-center gap-2">
                                        <div class="w-2 h-2 bg-teal-700 rounded-full"></div>
                                        Informations détaillées (Du trimestre)
                                    </h3>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Stock en début :</span>
                                            <span class="font-semibold">{{ $consommation->qte_dispo_deb_periode }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Qté reçue :</span>
                                            <span class="font-semibold">{{ $consommation->qte_recu }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Qté utilisée :</span>
                                            <span class="font-semibold">{{ $consommation->qte_utilisee }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Bénéficiaires :</span>
                                            <span class="font-semibold">{{ $consommation->nb_beneficiaire }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Périmé :</span>
                                            <span class="font-semibold">{{ $consommation->perimee }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Pertes & avariées :</span>
                                            <span class="font-semibold">{{ $consommation->perte_avarie }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Retour CAMEG :</span>
                                            <span class="font-semibold">{{ $consommation->qte_retour_cameg }}</span>
                                        </div>
                                        <div class="flex justify-between border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Jours rupture :</span>
                                            <span class="font-semibold">{{ $consommation->nb_jour_rupture }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between col-span-2 border border-gray-300 bg-white p-1 rounded">
                                            <span class="text-gray-600">Stock en fin :</span>
                                            <span class="font-semibold">{{ $consommation->qte_restante }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div
                            class="text-center text-gray-500 py-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-lg font-medium text-gray-400">Aucune consommation enregistrée</p>
                            <p class="text-sm text-gray-400 mt-1">Les données apparaîtront ici une fois disponibles</p>
                        </div>
                    </div>
                @endforelse
            </div>
        @elseif ($formulaireVisible)
                <!-- Bouton Toggle -->
                <button id="toggleButton" onclick="toggleInstructions()"
                    class="flex items-center gap-2 text-teal-600 text-decoration-underline">
                    <span id="buttonText">Masquer les instructions</span>
                    <svg id="arrowIcon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Instructions (div à masquer/afficher) -->
                <div id="instructionsDiv" class="overflow-x-auto transition-all duration-300 ease-in-out"
                    style="max-height: 200px; opacity: 1; margin-bottom: 16px;">
                    <div class="gap-2 items-center bg-gray-50 border border-gray-200 pb-2 px-4 py-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-700 mb-2">
                            * Les colonnes en <span class="text-red-600 font-semibold">rouge</span> sont calculées
                            automatiquement à partir de vos saisies.
                        </p>
                        <p class="text-sm text-gray-700 mb-2">
                            * Veuillez remplir les colonnes contenant l'indication <em>« Saisir... »</em> avec une valeur
                            minimale de 0.
                        </p>
                        <p class="text-sm text-gray-700">
                            * Les champs de calculs automatiques dépendent obligatoirement du Stock disponible en début de
                            trimestre.
                        </p>
                    </div>
                </div>

                <div class="flex justify-between p-2 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <p class="text-blue-900">Choisir entre FS et ASC pour continuer:</p>
                        <div>
                            <select wire:model.live="type_structure" wire:change="chargerMedicaments"
                                class="w-72 bg-blue-100 rounded-full p-1 border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-700">
                                <option value="">-- Sélectionner --</option>
                                <option value="FS">FS</option>
                                <option value="ASC">ASC</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <p class="text-blue-900">Choisir la période ou laisser par défaut:</p>
                        <div>
                            <select wire:model.live="periode_choisie" wire:change="chargerMedicaments"
                                class="w-72 bg-blue-100 rounded-full p-1 border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-700">
                                @foreach ($periodes_disponibles as $periode)
                                    <option value="{{ $periode->id }}">
                                        {{ $periode->nom }} : {{ $periode->mois_debut }}-{{ $periode->mois_fin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white transition-opacity duration-200 overflow-auto max-h-[650px] {{ empty($type_structure) ? 'bg-gray-500 opacity-50 pointer-events-none select-none' : '' }}">
                    <form wire:submit.prevent="ajouterConsommation"
                        wire:key="formulaire-{{ $type_structure }}-{{ $periode_choisie }}">
                        <div class="space-y-6 ">
                            @foreach ($medicaments as $index => $medicament)
                                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <button type="button" onclick="toggleForm({{ $index }})"
                                                class="flex items-center gap-2 text-blue-700 hover:underline font-semibold">
                                                <h3 class="text-lg">{{ $medicament->nom }}</h3>
                                                <svg class="w-4 h-4 transition-transform duration-300" id="arrowIcon_{{ $index }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div>
                                            <span
                                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Médicament
                                                #{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                    <div class="transition-all duration-300 ease-in-out">
                                        @if(session()->has("message_sauvegarde_$index"))
                                            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>{{ session("message_sauvegarde_$index") }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div id="formulaire_{{ $index }}"
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Stock disponible en début de trimestre
                                            </label>
                                            <input type="number" id="stock_debut_{{ $index }}"
                                                oninput="calculerStock({{ $index }}), calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.stk_dsp_deb_trim"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.stk_dsp_deb_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Quantité reçue dans le trimestre
                                            </label>
                                            <input type="number" id="qte_recu_{{ $index }}"
                                                oninput="calculerStock({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_get_in_trim"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.qte_get_in_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-red-600 mb-2">
                                                <span class="font-bold">Quantité en Stock</span>
                                            </label>
                                            <input type="number" readonly id="qte_en_stock_{{ $index }}"
                                                wire:model.live="consommations.{{ $index }}.qte_en_stock"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-red-500 font-bold text-center text-[17px]" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Quantité utilisée
                                            </label>
                                            <input type="number" id="qte_used_{{ $index }}"
                                                oninput="calculerStockSecurite({{ $index }}), calculerStock({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_used"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.qte_used') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Nombre de bénéficiaires
                                            </label>
                                            <input type="number" id="nb_beneficiaire_{{ $index }}"
                                                oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.nb_beneficiaire"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.nb_beneficiaire') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Périmé
                                            </label>
                                            <input type="number" id="perimee_{{ $index }}" oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.perimee"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.perimee') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Pertes et avariées
                                            </label>
                                            <input type="number" id="perte_avarie_{{ $index }}" oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.perte_avarie"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.perte_avarie') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Quantité retournée à la CAMEG
                                            </label>
                                            <input type="number" id="qte_ret_cameg_{{ $index }}" oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_ret_cameg"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.qte_ret_cameg') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Nombre de jours de rupture
                                            </label>
                                            <input type="number" id="nb_jour_rupture_{{ $index }}"
                                                oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.nb_jour_rupture"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.nb_jour_rupture') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-blue-900 mb-2">
                                                Quantité restante en stock en fin du trimestre
                                            </label>
                                            <input type="number" id="qte_stock_fin_trim_{{ $index }}"
                                                oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_stock_fin_trim"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                  @error('consommations.' . $index . '.qte_stock_fin_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-red-600 mb-2">
                                                <span class="font-bold">Stock de sécurité pour le trimestre à venir</span>
                                            </label>
                                            <input type="number" readonly id="stk_de_securite_{{ $index }}"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.stk_de_securite"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-red-500 font-bold text-center text-[17px]" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-red-600 mb-2">
                                                <span class="font-bold">CMM ajustée (CMMa)</span>
                                            </label>
                                            <input type="number" readonly id="ccma_{{ $index }}"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.ccma"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-red-500 font-bold text-center text-[17px]" />
                                        </div>
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-red-600 mb-2">
                                                <span class="font-bold">Quantité commandée pour le trimestre à venir</span>
                                            </label>
                                            <input type="number" readonly id="cmd_trim_svt_{{ $index }}"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.cmd_trim_svt"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-red-500 font-bold text-center text-[17px]" />
                                        </div>

                                    </div>
                                    <div class="flex justify-center end">
                                        <button type="button" wire:click="enregistrerTemporairement({{ $index }})"
                                            @if(empty($type_structure) || empty($periode_choisie)) disabled @endif
                                            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed shadow-lg text-white p-2 rounded-full transition-colors duration-200 flex items-center gap-2">
                                            Enregistrer temporairement
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Boutons -->
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                                Enregistrer
                            </button>
                            <button type="reset"
                                class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition-colors">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
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
    function viderTousLesInputs() {
        const inputs = document.querySelectorAll('form input');
        inputs.forEach(input => {
            if (!input.hasAttribute('readonly')) {
                input.value = '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }
    function toggleForm(index) {
        const formulaire = document.getElementById(`formulaire_${index}`);
        const arrowIcon = document.getElementById(`arrowIcon_${index}`);

        if (formulaire) {
            formulaire.classList.toggle('hidden');

            if (arrowIcon) {
                const isHidden = formulaire.classList.contains('hidden');
                arrowIcon.style.transform = isHidden ? 'rotate(-90deg)' : 'rotate(0deg)';
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".card-wrapper").forEach((card) => {
            const toggleButton = card.querySelector(".toggle-button");
            const details = card.querySelector(".details");
            const labelOpen = card.querySelector(".label-open");
            const labelClose = card.querySelector(".label-close");

            toggleButton.addEventListener("click", () => {
                details.classList.toggle("hidden");
                labelOpen.classList.toggle("hidden");
                labelClose.classList.toggle("hidden");
            });
        });
    });
</script>
</div>