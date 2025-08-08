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
        wire:target="afficherFormulaire,exporterPDF,previousSlide,afficherTableau,nextSlide,filtrerParPériode,ajouterConsommation,renitialiserMedicament,toggleHiddenCards,ajouterMedicament,showEditInput,activerEdition,choix,chargerDepuisSession,chargerMedicaments,chercherConsommations,soumettreConsommation,enregistrerQteAccorde"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>
    <!-- En-tête -->
    <div class="flex justify-between items-center relative mb-4">
        <h2 class="text-2xl font-semibold text-teal-600">
            @if ($tableauVisible)
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-box-fill"></i>
                    </div>
                    <p>Consommations des médicaments
                        @if (in_array(auth()->user()->role->nom_role, ['Formation sanitaire', 'District']))
                            <span class="font-semibold text-[18px] text-gray-500"> |
                                {{ Auth::user()->entity['nom'] }}</span>
                    </p>
            @endif
            </span>
        @elseif ($formulaireVisible)
            <span class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-plus"></i>
                </div>
                <p>Ajouter une consommation
                    @if (in_array(auth()->user()->role->nom_role, ['Formation sanitaire', 'District']))
                        <span class="font-semibold text-[18px] text-gray-500"> |
                            {{ Auth::user()->entity['nom'] }}</span>
                </p>
                @endif
            </span>
            @endif
        </h2>
        <div>
            @if ($tableauVisible && auth()->check() && auth()->user()->role->nom_role == 'Formation sanitaire')
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
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
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
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
                <svg id="arrowIcon" class="w-4 h-4 transition-transform duration-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="flex justify-between">
                <div id="instructionsDiv" class="overflow-x-auto transition-all duration-300 ease-in-out mb-1"
                    style="max-height: 200px; opacity: 1">
                    <div class="gap-2 items-center bg-gray-50 border border-gray-200 px-4 py-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-700">
                            * Filtrer les données de la table en changeant la période et le type de structure entre FS
                            et
                            ASC.
                            </p">
                            @if (auth()->check() && auth()->user()->role->nom_role === 'District')
                                <p class="text-sm text-gray-700">* Cliquer sur le petit bic pour enrégistrer ou modifier
                                    la
                                    quantité
                                    accordée.</p>
                            @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[15px] text-gray-600">
                    @if (auth()->check() && in_array(auth()->user()->role->nom_role, ['District', 'Administrateur']))
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
            </div>
            <div class="flex justify-between bg-gray-50  px-4 py-3 rounded-lg shadow-sm">
                <select id="medicamentSelector"
                    class=" p-2 border rounded-full w-72 focus:ring-1 focus:border-teal-600">
                    @foreach ($consommations_all as $index => $consommation)
                        @php
                            $estCachee =
                                $consommation->cmma == 0 &&
                                $consommation->stock_securite == 0 &&
                                $consommation->cmd_trim_svt == 0;
                        @endphp
                        @if (!$estCachee || $showHiddenCards)
                            <option value="{{ $loop->index }}">{{ $consommation->medicament->nom }}</option>
                        @endif
                    @endforeach
                </select>

                <div class="flex items-center gap-2">
                    @if (auth()->check() && in_array(auth()->user()->role->nom_role, ['District', 'Administrateur']))
                        <select wire:model="fs" wire:change="chercherConsommations"
                            class="w-44 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-600">
                            @foreach ($formation_sanitaire as $fs)
                                <option value="{{ $fs->id }}">{{ $fs->nom }}</option>
                            @endforeach
                        </select>
                    @endif
                    <select wire:model="structure_defaut" wire:change="chercherConsommations"
                        class="w-28 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-blue-600">
                        <option value="FS">FS</option>
                        <option value="ASC">ASC</option>
                    </select>

                    <select wire:model="periode_search" wire:change="chercherConsommations"
                        class="w-72 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-blue-600">
                        @foreach ($periodes_all as $periode)
                            <option value="{{ $periode->id }}">
                                {{ $periode->nom }} : {{ $periode->mois_debut }}-{{ $periode->mois_fin }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if ($this->conso)
                <div class="flex justify-between items-center mb-4"
                    wire:key="header-buttons-{{ $this->conso->id }}-{{ $this->etat }}">
                    <div>
                        <p class="text-gray-700 font-semibold">État de la consommation : {{ $this->conso->etat }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="exporterPDF({{ $this->conso->id }})"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-normal py-1.5 px-4 rounded-full shadow flex items-center gap-1">
                            <i class="bi bi-download"></i> Exporter
                        </button>
                        @if ($this->conso->etat === 'soumis' && auth()->check() && auth()->user()->role->nom_role === 'District')
                            <button wire:click="validerConsommation({{ $this->conso->id }})"
                                class="bg-green-100 hover:bg-green-300 text-green-600 font-normal py-1.5 px-4 rounded-full shadow flex items-center gap-1">
                                <i class="bi bi-check"></i> Valider
                            </button>
                        @endif
                        @if ($this->conso->etat === 'en_cours' && auth()->check() && auth()->user()->role->nom_role === 'Formation Sanitaire')
                            <button wire:click="activerEdition({{ $this->conso->id }})"
                                onclick="console.log('on a cliqué')"
                                class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-normal py-1.5 px-4 rounded-full shadow flex items-center gap-1">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button wire:click="soumettreConsommation({{ $this->conso->id }})"
                                class="bg-teal-700 hover:bg-teal-800 text-white font-normal py-1.5 px-4 rounded-full shadow flex items-center gap-1">
                                <i class="bi bi-send-fill"></i> Soumettre
                            </button>
                        @endif
                    </div>
                </div>
            @endif
            <div class="relative w-full max-w-6xl mx-auto">
                <!-- Indicateur de position -->
                <div class="flex justify-center items-center gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        @forelse ($consommations_all as $index => $consommation)
                            @php
                                $estCachee =
                                    $consommation->cmma == 0 &&
                                    $consommation->stock_securite == 0 &&
                                    $consommation->cmd_trim_svt == 0;
                            @endphp
                            @if (!$estCachee || $showHiddenCards)
                                <button wire:click="setCurrentSlide({{ $loop->index }})"
                                    class="w-3 h-3 rounded-full transition-all duration-300 {{ $loop->index == ($currentSlide ?? 0) ? 'bg-teal-600 w-8' : 'bg-gray-300 hover:bg-gray-400' }}">
                                </button>
                            @endif
                        @empty
                        @endforelse
                    </div>
                    <div class="text-sm text-gray-500 font-medium">
                        <span class="text-teal-600">{{ ($currentSlide ?? 0) + 1 }}</span> /
                        {{ $consommations_all->where(function ($c) use ($showHiddenCards) {return !($c->cmma == 0 && $c->stock_securite == 0 && $c->cmd_trim_svt == 0) || $showHiddenCards;})->count() }}
                    </div>
                </div>

                <!-- Conteneur du carousel -->
                <div class="relative overflow-hidden rounded-3xl">
                    <div class="flex transition-transform duration-500 ease-in-out"
                        style="transform: translateX(-{{ ($currentSlide ?? 0) * 100 }}%)">

                        @forelse ($consommations_all as $index => $consommation)
                            @php
                                $estCachee =
                                    $consommation->cmma == 0 &&
                                    $consommation->stock_securite == 0 &&
                                    $consommation->cmd_trim_svt == 0;
                                $user = auth()->user();
                                $role = $user?->role->nom_role;
                                $accordee = $consommation->qte_accordee;
                                $medicament_id = $consommation->medicament_id;
                                $consommation_id = $consommation->consommation_id;
                            @endphp

                            @if (!$estCachee || $showHiddenCards)
                                <div class="w-full flex-shrink-0 px-2">
                                    <div
                                        class="bg-white shadow-2xl rounded-3xl border border-gray-100 overflow-hidden h-full min-h-[600px]">

                                        <!-- Header avec gradient -->
                                        <div
                                            class="bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-700 px-8 py-8 relative overflow-hidden">
                                            <div
                                                class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32">
                                            </div>
                                            <div
                                                class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24">
                                            </div>

                                            <div class="relative z-10">
                                                <div class="flex items-center justify-between mb-4">
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                                            <i class="bi bi-capsule text-white text-2xl"></i>
                                                        </div>
                                                        <div>
                                                            <h1 class="text-3xl font-bold text-white mb-2">
                                                                {{ $consommation->medicament->nom }}</h1>
                                                            <p class="text-teal-100 text-lg">Consommation trimestrielle
                                                            </p>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white font-medium">
                                                        @if ($this->conso->etat == 'soumis')
                                                            <i class="bi bi-clock mr-2"></i>En validation
                                                        @elseif ($this->conso->etat == 'valide')
                                                            <i class="bi bi-check-circle mr-2"></i>Validé
                                                        @elseif ($this->conso->etat == 'en_cours')
                                                            <i class="bi bi-x-circle-fill mr-2"></i>Validé
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contenu principal -->
                                        <div class="p-8">
                                            <!-- Métriques principales en grand -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                                                <div
                                                    class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200">
                                                    <i class="bi bi-box text-blue-600 text-3xl mb-3"></i>
                                                    <div class="text-4xl font-bold text-blue-800 mb-2">
                                                        {{ $consommation->qte_en_stock }}</div>
                                                    <div class="text-blue-600 font-semibold">Stock total en debut du
                                                        trimestre</div>
                                                </div>

                                                <div
                                                    class="text-center p-6 bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl border border-teal-200">
                                                    <i class="bi bi-shield-check text-teal-600 text-3xl mb-3"></i>
                                                    <div class="text-4xl font-bold text-teal-800 mb-2">
                                                        {{ $consommation->stock_securite }}</div>
                                                    <div class="text-teal-600 font-semibold">Stock de sécurité</div>
                                                </div>

                                                <div
                                                    class="text-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl border border-orange-200">
                                                    <i class="bi bi-graph-up text-orange-600 text-3xl mb-3"></i>
                                                    <div class="text-4xl font-bold text-orange-800 mb-2">
                                                        {{ $consommation->cmma }}</div>
                                                    <div class="text-orange-600 font-semibold">CMM ajustée</div>
                                                </div>

                                                <div
                                                    class="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200">
                                                    <i class="bi bi-cart text-gray-600 text-3xl mb-3"></i>
                                                    <div class="text-4xl font-bold text-gray-800 mb-2">
                                                        {{ $consommation->cmd_trim_svt }}</div>
                                                    <div class="text-gray-600 font-semibold">Quantité commandée</div>
                                                </div>
                                            </div>

                                            <!-- Section quantité accordée -->
                                            <div class="mb-8">
                                                <div class="flex gap-6">
                                                    <!-- Quantité accordée -->
                                                    <div
                                                        class="@if (!is_null($accordee) && $accordee != $consommation->cmd_trim_svt) flex-1 @else w-full @endif p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl border border-indigo-200">
                                                        <div class="flex items-center gap-3 mb-4">
                                                            <i
                                                                class="bi bi-check2-square text-indigo-600 text-2xl"></i>
                                                            <h3 class="text-xl font-bold text-indigo-800">Quantité
                                                                accordée par le District</h3>
                                                        </div>

                                                        @if ($role === 'Formation sanitaire' && $this->conso->etat !== 'valide')
                                                            <div class="text-indigo-600 text-lg font-medium">
                                                                <i class="bi bi-hourglass-split mr-2"></i>En cours de
                                                                validation...
                                                            </div>
                                                        @elseif ($role === 'Formation sanitaire' && $this->conso->etat == 'valide')
                                                            <div class="text-4xl font-bold text-indigo-800">
                                                                {{ $accordee ?? 'Non validé' }}
                                                            </div>
                                                        @elseif ($role === 'District')
                                                            @if ($not_edit[$medicament_id])
                                                                <div class="flex items-center justify-between">
                                                                    <div class="text-4xl font-bold text-indigo-800">
                                                                        {{ $accordee ?? '--' }}
                                                                    </div>
                                                                    @if ($this->conso->etat == 'soumis')
                                                                        <button type="button"
                                                                            wire:click="showEditInput({{ $medicament_id }}, {{ $consommation_id }})"
                                                                            class="px-6 py-3 bg-white text-indigo-600 rounded-xl hover:bg-indigo-50 font-medium border border-indigo-200 transition-colors">
                                                                            <i class="bi bi-pen mr-2"></i>Modifier
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @if ($edit[$medicament_id])
                                                                <form
                                                                    wire:submit.prevent="enregistrerQteAccorde({{ $consommation_id }}, {{ $consommation->medicament_id }})"
                                                                    class="mt-4">
                                                                    <div class="flex gap-4 items-center">
                                                                        <input type="number"
                                                                            wire:model="quantites_accordees.{{ $medicament_id }}"
                                                                            placeholder="Saisir quantité"
                                                                            class="flex-1 px-4 py-3 border border-indigo-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg"
                                                                            min="0" step="1" required>
                                                                        <button type="submit"
                                                                            class="px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium transition-colors">
                                                                            <i class="bi bi-check-lg mr-2"></i>Valider
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    <!-- Différence si applicable -->
                                                    @if (!is_null($accordee) && $accordee != $consommation->cmd_trim_svt)
                                                        <div
                                                            class="flex-1 p-6 bg-gradient-to-br from-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-50 to-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-100 rounded-2xl border border-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-200">
                                                            <div class="flex items-center gap-3 mb-4">
                                                                <i
                                                                    class="bi bi-{{ $accordee > $consommation->cmd_trim_svt ? 'arrow-up-circle' : 'arrow-down-circle' }} text-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-600 text-2xl"></i>
                                                                <h3
                                                                    class="text-xl font-bold text-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-800">
                                                                    {{ $accordee > $consommation->cmd_trim_svt ? 'Ajout' : 'Retiré' }}
                                                                </h3>
                                                            </div>
                                                            <div
                                                                class="text-4xl font-bold text-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-800">
                                                                {{ $accordee > $consommation->cmd_trim_svt ? '+' : '' }}{{ $accordee - $consommation->cmd_trim_svt }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Détails étendus -->
                                            <div
                                                class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                                                <h3
                                                    class="text-gray-800 font-bold text-lg mb-6 flex items-center gap-3">
                                                    <div class="w-3 h-3 bg-teal-600 rounded-full"></div>
                                                    Informations détaillées du trimestre
                                                </h3>
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Stock début</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->qte_dispo_deb_periode }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Quantité reçue dans le
                                                            trimestre</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->qte_recu }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Quantité
                                                            utilisée</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->qte_utilisee }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Bénéficiaires</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->nb_beneficiaire }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Périmé</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->perimee }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Pertes et
                                                            avariées</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->perte_avarie }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Retournés à la
                                                            CAMEG</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->qte_retour_cameg }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200">
                                                        <span class="text-gray-600 font-medium">Nombre de jours de
                                                            rupture</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->nb_jour_rupture }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-white rounded-xl border border-gray-200 md:col-span-1">
                                                        <span class="text-gray-600 font-medium">Stock en fin de
                                                            trimestre</span>
                                                        <span
                                                            class="font-bold text-gray-800 text-lg">{{ $consommation->qte_restante }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="w-full flex-shrink-0">
                                <div
                                    class="text-center text-gray-500 py-32 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                    <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-2xl font-medium text-gray-400 mb-2">Aucune consommation enregistrée
                                    </p>
                                    <p class="text-gray-400">Les données apparaîtront ici une fois disponibles</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Boutons de navigation -->
                <div class="flex justify-between items-center mt-8">
                    <button wire:click="previousSlide"
                        class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ ($currentSlide ?? 0) <= 0 ? 'disabled' : '' }}>
                        <i class="bi bi-chevron-left"></i>
                        Précédent
                    </button>

                    <div class="flex gap-3">
                        <button
                            class="px-4 py-2 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-colors">
                            <i class="bi bi-printer"></i>
                        </button>
                        <button
                            class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                            <i class="bi bi-download"></i>
                        </button>
                        <button
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>

                    <button wire:click="nextSlide"
                        class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ ($currentSlide ?? 0) >=$consommations_all->where(function ($c) use ($showHiddenCards) {return !($c->cmma == 0 && $c->stock_securite == 0 && $c->cmd_trim_svt == 0) || $showHiddenCards;})->count() -1? 'disabled': '' }}>
                        Suivant
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            @push('scripts')
                <script>
                    // Gestion des touches clavier pour la navigation
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

                    carousel.addEventListener('touchstart', function(e) {
                        startX = e.touches[0].clientX;
                        isDragging = true;
                    });

                    carousel.addEventListener('touchmove', function(e) {
                        if (!isDragging) return;
                        currentX = e.touches[0].clientX;
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
                </script>
            @endpush
            <div class="col-span-full mt-4 flex justify-center">
                @if ($this->hiddenCardsCount > 0)
                    <button type="button" wire:click="toggleHiddenCards"
                        class="px-4 py-2 {{ $showHiddenCards ? 'bg-red-600 hover:bg-red-700' : 'bg-teal-600 hover:bg-teal-700' }} text-white rounded-lg transition duration-300">
                        @if ($showHiddenCards)
                            Masquer les médicaments non commandés
                        @else
                            Afficher les médicaments non commandés ({{ $this->hiddenCardsCount }})
                        @endif
                    </button>
                @endif
            </div>
    </div>
    @endif
    @if ($formulaireVisible)
        <!-- Bouton Toggle -->
        <button id="toggleButton" onclick="toggleInstructions()"
            class="flex items-center gap-2 text-teal-600 text-decoration-underline">
            <span id="buttonText">Masquer les instructions</span>
            <svg id="arrowIcon" class="w-4 h-4 transition-transform duration-300" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="flex justify-between bg-gray-50 border-gray-200 mb-4">
            <!-- Sélection structure -->
            <div class="flex items-center gap-2">
                <p class="text-blue-900">Choisir entre FS et ASC pour continuer :</p>
                <div>
                    <select wire:model.live="type_structure" wire:change="chargerMedicaments" onchange="viderInput()"
                        id="structure"
                        class="w-48 bg-blue-100 rounded-full p-1 border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-700
                                                        {{ $modifierConso ? 'bg-blue-100 opacity-50 pointer-events-none select-none' : '' }}">
                        <option value="">-- Sélectionner --</option>
                        <option value="FS">FS</option>
                        <option value="ASC">ASC</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <p class="text-blue-900">Médicament à enrégistrer :</p>
                <div>
                    <select id="select_medicament" onchange="afficherFormulaire(this)"
                        class="w-72 bg-blue-100 rounded-full p-1 border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-700">
                        <option value="">-- Sélectionner --</option>
                        <option value="all">Tous les produits</option>
                        @foreach ($medicaments as $index => $medicament)
                            <option value="{{ $index }}" data-id="{{ $medicament->id }}">
                                {{ $medicament->nom }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="flex items-center gap-2">
                <p class="text-blue-900">Choisir la période ou laisser par défaut :</p>
                <div>
                    <select wire:model.live="periode_choisie" id="periode_search" onchange="viderInput()"
                        class="w-72 bg-blue-100 rounded-full p-1 border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-700 {{ empty($type_structure) ? 'bg-blue-300 opacity-50 pointer-events-none select-none' : '' }}">
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
            <div class="w-full flex justify-end pr-4 hidden mb-1" id="close_form">
                <div>
                    <i class="bi bi-x-circle-fill text-red-600 text-xl cursor-pointer hover:text-red-800"
                        title="Fermer tout le formulaire" onclick="masquerToutFormulaire()"></i>
                </div>
            </div>
            <form wire:submit.prevent="ajouterConsommation">
                <div class="space-y-6 ">
                    @foreach ($medicaments as $index => $medicament)
                        <div wire:key="consommation-{{ $type_structure }}-{{ $periode_choisie }}-{{ $index }}"
                            class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden"
                            id="formulaire_{{ $index }}" wire:key="medicament-{{ $medicament->id }}">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <button type="button" onclick="toggleForm({{ $index }})"
                                        class="flex items-center gap-2 text-blue-700 hover:underline font-semibold">
                                        <h3 class="text-lg">{{ $medicament->nom }}</h3>
                                        <svg class="w-4 h-4 transition-transform duration-300"
                                            id="arrowIcon_{{ $index }}" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Médicament #{{ $index + 1 }}
                                    </span>
                                    <div>
                                        <i class="bi bi-x-circle-fill text-red-600 text-xl cursor-pointer hover:text-red-800"
                                            title="Masquer ce formulaire"
                                            onclick="masquerFormulaire({{ $index }})"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="transition-all duration-300 ease-in-out">
                                @if (session()->has("message_sauvegarde_$index"))
                                    <div
                                        class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
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
                            <div id="form_{{ $index }}"
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-green-700 mb-2">
                                        <span class="font-bold">Stock théorique en début de trimestre</span>
                                    </label>
                                    <input type="number" readonly id="stock_debut_attendu_{{ $index }}"
                                        wire:model.live="consommations.{{ $index }}.stock_debut_attendu"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-green-700 font-bold text-center text-[17px]" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                        <span class="text-red-500">*</span> Stock réel en début de trimestre
                                    </label>
                                    <input type="number" id="stock_debut_{{ $index }}"
                                        oninput="calculerStock({{ $index }}), calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                        wire:model.debounce.500ms="consommations.{{ $index }}.stk_dsp_deb_trim"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      @error('consommations.' . $index . '.stk_dsp_deb_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                        placeholder="Saisir..." min="0" step="1" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                        <span class="text-red-500">*</span> Quantité reçue dans le trimestre
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
                                        <span class="text-red-500">*</span> Quantité utilisée
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
                                    <input type="number" id="perimee_{{ $index }}"
                                        oninput="checkInput({{ $index }})"
                                        wire:model.debounce.500ms="consommations.{{ $index }}.perimee"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      @error('consommations.' . $index . '.perimee') !bg-red-100 !border-red-500 !border-2 @enderror"
                                        placeholder="Saisir..." min="0" step="1" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                        Pertes et avariées
                                    </label>
                                    <input type="number" id="perte_avarie_{{ $index }}"
                                        oninput="checkInput({{ $index }})"
                                        wire:model.debounce.500ms="consommations.{{ $index }}.perte_avarie"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      @error('consommations.' . $index . '.perte_avarie') !bg-red-100 !border-red-500 !border-2 @enderror"
                                        placeholder="Saisir..." min="0" step="1" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                        Quantité retournée à la CAMEG
                                    </label>
                                    <input type="number" id="qte_ret_cameg_{{ $index }}"
                                        oninput="checkInput({{ $index }})"
                                        wire:model.debounce.500ms="consommations.{{ $index }}.qte_ret_cameg"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      @error('consommations.' . $index . '.qte_ret_cameg') !bg-red-100 !border-red-500 !border-2 @enderror"
                                        placeholder="Saisir..." min="0" step="1" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                        <span class="text-red-500">*</span> Nombre de jours de rupture
                                    </label>
                                    <input type="number" id="nb_jour_rupture_{{ $index }}"
                                        oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                        wire:model.debounce.500ms="consommations.{{ $index }}.nb_jour_rupture"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent text-blue-900
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      @error('consommations.' . $index . '.nb_jour_rupture') !bg-red-100 !border-red-500 !border-2 @enderror"
                                        placeholder="Saisir..." min="0" step="1" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-green-700 mb-2">
                                        <span class="font-bold">Stock théorique en fin de trimestre</span>
                                    </label>
                                    <input type="number" readonly
                                        id="qte_stock_fin_trim_attendu_{{ $index }}"
                                        wire:model.live="consommations.{{ $index }}.qte_stock_fin_trim_attendu"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-green-700 font-bold text-center text-[17px]" />
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-blue-900 mb-2">
                                        Stock réel en fin de trimestre
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
                                        <span class="font-bold">Consommation Moyenne Mentuelle ajustée
                                            (CMMa)
                                        </span>
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
                        </div>
                    @endforeach
                </div>
                <!-- Boutons -->
                <div class="mt-4 flex justify-end gap-2">
                    @if ($modifierConso)
                        <button type="submit"
                            class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 transition-colors">
                            Modifier
                        </button>
                    @else
                        <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                            Enregistrer
                        </button>
                    @endif
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

    function afficherFormulaire(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const index = selectedOption.value;
        const medicamentId = selectedOption.dataset.id;

        const closeFormBtn = document.getElementById('close_form');
        if (!index) {
            if (closeFormBtn) closeFormBtn.classList.add('hidden');
            return;
        }
        if (closeFormBtn) closeFormBtn.classList.remove('hidden');

        if (index === "all") {
            const allForms = document.querySelectorAll('[id^="formulaire_"]');
            allForms.forEach(form => form.classList.remove('hidden'));
            console.log("Tous les médicaments sont affichés.");
            return;
        }
        const formElement = document.getElementById('formulaire_' + index);
        if (formElement && formElement.classList.contains('hidden')) {
            formElement.classList.remove('hidden');
        }

        console.log("Médicament sélectionné : ID =", medicamentId, ", Index =", index);
    }

    function masquerToutFormulaire() {
        const allForms = document.querySelectorAll('[id^="formulaire_"]');
        allForms.forEach(form => {
            form.classList.add('hidden');
            const hideBtn = form.querySelector('.btn-masquer');
            if (hideBtn) {
                hideBtn.classList.add('hidden');
            }
        });
        const select = document.getElementById('select_medicament');
        if (select) {
            select.value = "";
        }

        console.log("Tous les formulaires ont été masqués.");
    }


    function masquerFormulaire(index) {
        const formElement = document.getElementById('formulaire_' + index);
        if (formElement) {
            formElement.classList.add('hidden');
        }
        const select = document.getElementById('select_medicament');
        if (select) {
            select.value = "";
        }
    }

    function viderTousLesInputs() {
        const inputs = document.querySelectorAll('form input');
        inputs.forEach(input => {
            if (!input.hasAttribute('readonly')) {
                input.value = '';
                input.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            }
        });
    }

    function toggleForm(index) {
        const formulaire = document.getElementById(`form_${index}`);
        const arrowIcon = document.getElementById(`arrowIcon_${index}`);

        if (formulaire) {
            formulaire.classList.toggle('hidden');

            if (arrowIcon) {
                const isHidden = formulaire.classList.contains('hidden');
                arrowIcon.style.transform = isHidden ? 'rotate(-90deg)' : 'rotate(0deg)';
            }
        }
    }

    function viderperiode() {
        const select = document.getElementById('periode_search');
        if (select) {
            select.value = ''; // vide visuellement le champ
            select.dispatchEvent(new Event('input')); // force Livewire à prendre en compte le changement
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        initializeToggleButton();
        setupSelectListeners();
    });

    function setupSelectListeners() {
        // Écouter tous les selects qui déclenchent chercherConsommations
        const selects = document.querySelectorAll('select[wire\\:change="chercherConsommations"]');

        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Petit délai pour laisser Livewire mettre à jour le DOM
                setTimeout(() => {
                    initializeToggleButton();
                }, 500);
            });
        });
    }

    function viderInput() {
        const input = document.getElementById('select_medicament');
        if (input) {
            input.value = '';
        }
    }

    function initializeToggleButton() {
        const toggleButton = document.getElementById('toggle-hidden-button');
        const hiddenCards = document.querySelectorAll('.hidden-card');

        if (!toggleButton) return;

        // Activer le bouton seulement s'il y a des cartes cachées
        if (hiddenCards.length > 0) {
            toggleButton.disabled = false;
            toggleButton.textContent = 'Afficher les médicaments non commandés (' + hiddenCards.length + ')';
            toggleButton.classList.remove('bg-red-600', 'hover:bg-red-700');
            toggleButton.classList.add('bg-teal-600', 'hover:bg-teal-700');
        } else {
            toggleButton.disabled = true;
            toggleButton.textContent = 'Liste des médicaments non commandés';
        }
        hiddenCards.forEach(card => {
            card.classList.add('hidden');
        });
    }

    function toggleHiddenCards() {
        const hiddenCards = document.querySelectorAll('.hidden-card');
        const toggleButton = document.getElementById('toggle-hidden-button');

        if (!toggleButton || hiddenCards.length === 0) return;

        const isCurrentlyHidden = hiddenCards[0].classList.contains('hidden');

        hiddenCards.forEach(card => {
            if (isCurrentlyHidden) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        // Mettre à jour le texte et la couleur du bouton
        if (isCurrentlyHidden) {
            toggleButton.textContent = 'Masquer les médicaments non commandés';
            toggleButton.classList.remove('bg-teal-600', 'hover:bg-teal-700');
            toggleButton.classList.add('bg-red-600', 'hover:bg-red-700');
        } else {
            toggleButton.textContent = 'Afficher les médicaments non commandés (' + hiddenCards.length + ')';
            toggleButton.classList.remove('bg-red-600', 'hover:bg-red-700');
            toggleButton.classList.add('bg-teal-600', 'hover:bg-teal-700');
        }
    }




    document.addEventListener('click', function(e) {
        if (!e.target.closest('.toggle-button')) return;

        const button = e.target.closest('.toggle-button');
        const card = button.closest('[wire\\:key^="consommation-"]');
        const details = card.querySelector('.details');
        const labelOpen = button.querySelector('.label-open');
        const labelClose = button.querySelector('.label-close');
        details.classList.toggle('hidden');
        labelOpen.classList.toggle('hidden');
        labelClose.classList.toggle('hidden');
    });

    function chercherMedicament() {
        const input = document.getElementById('search-medicament');
        const searchValue = input.value.trim().toLowerCase();
        const allCards = document.querySelectorAll('[id^="formulaire_"], [wire\\:key^="consommation-"]');
        if (!searchValue) {
            allCards.forEach(card => card.classList.remove('hidden'));
            return;
        }

        let found = false;

        allCards.forEach(card => {
            const title = card.querySelector('h2');
            if (title) {
                const medName = title.textContent.trim().toLowerCase();
                if (medName.includes(searchValue)) {
                    card.classList.remove('hidden');
                    found = true;
                } else {
                    card.classList.add('hidden');
                }
            }
        });

        if (!found) {
            console.log("Aucun médicament trouvé pour :", searchValue);
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const selector = document.getElementById('medicamentSelector');
        selector.addEventListener('change', function() {
            const selectedIndex = parseInt(this.value);
            if (!isNaN(selectedIndex)) {
                @this.set('currentSlide', selectedIndex);
            }
        });
    });













    function calculerStock(index) {
        console.log('Pourquoi?');
        const stockDebutInput = document.getElementById(`stock_debut_${index}`);
        const qteRecuInput = document.getElementById(`qte_recu_${index}`);
        const qteEnStockInput = document.getElementById(`qte_en_stock_${index}`);
        const qteUsedInput = document.getElementById(`qte_used_${index}`);

        if (!stockDebutInput || !qteRecuInput || !qteEnStockInput || !qteUsedInput) {
            return;
        }

        const stockDebut = parseFloat(stockDebutInput.value) || 0;
        const qteRecu = parseFloat(qteRecuInput.value) || 0;

        // Calcul du stock en stock
        if (stockDebutInput.value && !qteRecuInput.value) {
            qteEnStockInput.value = stockDebut;
        } else if (!stockDebutInput.value && qteRecuInput.value) {
            qteEnStockInput.value = '';
        } else if (stockDebutInput.value && qteRecuInput.value) {
            qteEnStockInput.value = stockDebut + qteRecu;
        } else {
            qteEnStockInput.value = '';
        }


        const qteUsed = parseFloat(qteUsedInput.value) || 0;
        const qteEnStock = parseFloat(qteEnStockInput.value) || 0;
        if (qteUsed && qteEnStock && qteUsed > qteEnStock) {
            alert("La quantité utilisée ne peut pas être supérieure à la quantité en stock.");
            qteUsedInput.value = '';
            qteUsedInput.focus();
            return false;
        }
    }

    function calculerStockSecurite(index) {

        const stockDebutInput = document.getElementById(`stock_debut_${index}`);
        const qteUsedInput = document.getElementById(`qte_used_${index}`);
        const qteEnStockInput = document.getElementById(`qte_en_stock_${index}`);
        const nbJourRuptureInput = document.getElementById(`nb_jour_rupture_${index}`);
        const stkSecuriteInput = document.getElementById(`stk_de_securite_${index}`);
        const ccmaInput = document.getElementById(`ccma_${index}`);
        const cmdTrimSvtInput = document.getElementById(`cmd_trim_svt_${index}`);
        const qteStockFinTrimInput = document.getElementById(`qte_stock_fin_trim_${index}`);


        if (
            !stockDebutInput || !qteUsedInput || !nbJourRuptureInput ||
            !stkSecuriteInput || !ccmaInput || !cmdTrimSvtInput || !qteStockFinTrimInput || !qteEnStockInput
        ) {
            return;
        }
        const stockDebut = parseFloat(stockDebutInput.value) || 0;
        const qteUsed = parseFloat(qteUsedInput.value) || 0;
        const nbJourRupture = parseFloat(nbJourRuptureInput.value) || 0;
        const qteStockFinTrim = parseFloat(qteStockFinTrimInput.value) || 0;
        const qteEnStock = parseFloat(qteEnStockInput.value) || 0;
        if (!stockDebutInput.value || stkSecuriteInput.value == '') {
            stkSecuriteInput.value = 0;
            ccmaInput.value = 0;
            cmdTrimSvtInput.value = 0;
            return;
        }

        if (nbJourRupture === 0) {
            stkSecuriteInput.value = Math.ceil(qteUsed); // sécurité = conso brute
            const cmma = (qteUsed / 90) * 30;
            ccmaInput.value = Math.ceil(cmma);
            cmdTrimSvtInput.value = Math.ceil((qteUsed + Math.ceil(qteUsed)) -
            qteStockFinTrim); //Dans ce cas qteUsed est ég&ale à stkSecurite
        } else if (nbJourRupture > 0 && nbJourRupture < 90) {
            const denom = 90 - nbJourRupture;
            const stkSecurite = (qteUsed * 90) / denom;
            const cmma = (qteUsed / denom) * 30;

            stkSecuriteInput.value = Math.ceil(stkSecurite);
            ccmaInput.value = Math.ceil(cmma);
            cmdTrimSvtInput.value = Math.ceil((Math.ceil(cmma) * 6) - qteStockFinTrim);
        } else {
            stkSecuriteInput.value = 0;
            ccmaInput.value = 0;
            cmdTrimSvtInput.value = 0;
        }
    }

    function checkInput(index) {
        const inputs = {
            stockDebut: document.getElementById(`stock_debut_${index}`),
            qteRecu: document.getElementById(`qte_recu_${index}`),
            qteEnStock: document.getElementById(`qte_en_stock_${index}`),
            qteUsed: document.getElementById(`qte_used_${index}`),
            nbBeneficaire: document.getElementById(`nb_beneficiaire_${index}`),
            perimee: document.getElementById(`perimee_${index}`),
            perteAvarie: document.getElementById(`perte_avarie_${index}`),
            qteRetCameg: document.getElementById(`qte_ret_cameg_${index}`),
            qteEnStock_fin_att: document.getElementById(`qte_stock_fin_trim_attendu_${index}`),
            nbJourRupture: document.getElementById(`nb_jour_rupture_${index}`),
            stkSecurite: document.getElementById(`stk_de_securite_${index}`),
            ccma: document.getElementById(`ccma_${index}`),
            cmdTrimSvt: document.getElementById(`cmd_trim_svt_${index}`),
            qteStockFinTrim: document.getElementById(`qte_stock_fin_trim_${index}`)
        };

        const isValidDigit = (value) => {
            if (value === '') return true;
            // Vérifier que chaque caractère est un chiffre entre 0 et 9
            for (let i = 0; i < value.length; i++) {
                if (value[i] < '0' || value[i] > '9') {
                    return false;
                }
            }
            return true;
        };

        // Validation des champs numériques
        for (const [key, input] of Object.entries(inputs)) {
            if (!input) continue;

            // Nettoyer la valeur en supprimant les caractères non numériques
            input.value = input.value.replace(/[^0-9]/g, '');

            if (!isValidDigit(input.value)) {
                alert(`Le champ "${key}" doit contenir uniquement des chiffres ou être vide.`);
                input.value = '';
                input.focus();
                return false;
            }
        }
        // Vérification : nombre de jours de rupture
        const nbJourRupture = parseInt(inputs.nbJourRupture.value, 10) || 0;
        const qteEnStock = parseInt(inputs.qteEnStock.value, 10) || 0;
        const qteUsed = parseInt(inputs.qteUsed.value, 10) || 0;
        const perteAvarie = parseInt(inputs.perteAvarie.value, 10) || 0;
        const perimee = parseInt(inputs.perimee.value, 10) || 0;
        const qteRetCameg = parseInt(inputs.qteRetCameg.value, 10) || 0;
        const qteEnStockFinTrim = parseInt(inputs.qteStockFinTrim.value, 10) || 0;
        const stockDebut = parseInt(inputs.stockDebut.value, 10) || 0;
        const qteRecu = parseInt(inputs.qteRecu.value, 10) || 0;
        const totalSorties = qteUsed + perteAvarie + perimee + qteRetCameg;
        let total;

        if (stockDebut && !qteRecu) {
            total = stockDebut
        } else if (!stockDebut && qteRecu) {
            total = null;
        } else if (stockDebut && qteRecu) {
            total = stockDebut + qteRecu;
        } else {
            total = null;
        }
        let reste = total - totalSorties;
        inputs.qteEnStock_fin_att.value = reste
        if (totalSorties > qteEnStock) {
            inputs.perteAvarie.value = null;
            inputs.perimee.value = null;
            inputs.qteRetCameg.value = null;
            inputs.qteEnStock_fin_att.value = total - qteUsed;
            alert(
                `Les quantités saisies dépassent le stock disponible pour ce médicament. Les champs ont été réinitialisés.`);
        }
        if (qteEnStockFinTrim && qteEnStockFinTrim > reste) {
            alert(
                'Info : Votre quantité restante réelle saisie est supérieur à la quantité restante théorique. Cliquez Ok pour continuez.');
        };
        if (nbJourRupture < 0 || nbJourRupture > 89) {
            alert("Le nombre de jours de rupture doit être compris entre 0 et 89.");
            inputs.nbJourRupture.value = '';
            calculerStockSecurite(index);
            console.log('valeur cmma = ' + inputs.ccma.value)
            inputs.nbJourRupture.focus();
            return false;
        }

        return true;
    }

    document.getElementById('search-medicament').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const cards = document.querySelectorAll('.card-wrapper');

        cards.forEach(card => {
            const medName = card.querySelector('h2').textContent.toLowerCase();

            if (medName.includes(search)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        // Si recherche vide → recacher les cartes des médicaments non commandés
        if (search.trim() === '') {
            document.querySelectorAll('.hidden-card').forEach(card => {
                card.classList.add('hidden');
            });
        }
    });
</script>
</div>
