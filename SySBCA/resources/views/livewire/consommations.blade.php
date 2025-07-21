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
        wire:target="afficherFormulaire,afficherTableau,filtrerParPériode,ajouterConsommation,chargerMedicaments"
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
                    <p>Consommations des médicaments <span class="font-semibold text-[18px] text-gray-500"> |
                            {{ Auth::user()->entity['nom'] }}</span></p>
                </span>
            @elseif ($formulaireVisible)
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-plus"></i>
                    </div>
                    <p>Ajouter une consommation<span class="font-semibold text-[18px] text-gray-500"> |
                            {{ Auth::user()->entity['nom'] }}</span></p>
                </span>
            @endif
        </h2>
        <div>
            <!-- Boutons de navigation -->
            @if ($tableauVisible)
                <button wire:click="afficherFormulaire()">
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
            <div id="instructionsDiv" class="overflow-x-auto transition-all duration-300 ease-in-out mb-1"
                style="max-height: 200px; opacity: 1">
                <div class="gap-2 items-center bg-gray-50 border border-gray-200 px-4 py-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-700">
                        * Filtrer les données de la table en changeant la période et le type de structure entre FS et
                        ASC
                    </p>
                </div>
            </div>
            <div class="mb-1 flex justify-between">
                <div class="">
                    <p class="text-[15px] text-gray-600">Consommation de la période : <span
                            class='text-blue-900 font-semibold'>{{ $periode_actuelle->nom }}</span></p>
                    <p class="text-[15px] text-gray-600">Type de structure : <span
                            class='text-blue-900 font-semibold'>{{ $structure_defaut }}</span> </p>
                </div>
                <div class="flex justify-between gap-2">
                    <select wire:model.live="structure_defaut"
                        class="w-25 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-600">
                        <option value="FS">FS</option>
                        <option value="ASC">ASC</option>
                    </select>
                    <select wire:model.live="periode_actuelle"
                        class="w-72 bg-blue-100 rounded-full border font-bold border-gray-400 text-blue-900 focus:outline-none focus:ring-teal-600">
                        @foreach ($periodes_all as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->nom }} :
                                {{ $periode->mois_debut }}-{{ $periode->mois_fin }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="rounded-lg overflow-auto max-h-[650px]">
                <table class="min-w-full table-auto border border-gray-300 text-sm text-blue-900">
                    <thead
                        class="bg-gray-100 text-[14px] bg-blue-100 text-blue-900 text-justify sticky top-0 bg-white z-10">
                        <tr class="bg-blue-100 text-center">
                            <th class="border border-gray-300 p-1 bg-white text-black">Désignation du produit</th>
                            <th class="border border-gray-300 p-1">Stock disponible en début de trimestre</th>
                            <th class="border border-gray-300 p-1">Quantité reçue dans le trimestre</th>
                            <th class="border border-gray-300 p-1 bg-white text-red-600">Quantité en Stock</th>
                            <th class="border border-gray-300 p-1">Quantité utilisé</th>
                            <th class="border border-gray-300 p-1">Nombre de bénéficiaire</th>
                            <th class="border border-gray-300 p-1">Périmé</th>
                            <th class="border border-gray-300 p-1">Pertes et avariées</th>
                            <th class="border border-gray-300 p-1">Quantitée retournée à la CAMEG</th>
                            <th class="border border-gray-300 p-1">Nombre de jour de rupture</th>
                            <th class="border border-gray-300 p-1">Quantité restante en Stock en fin du trimestre</th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Stock de sécurité pour le
                                trimestre
                                à venir</th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">CMM ajustéé (CMMa)</th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Qantitée commandée pour le
                                trimestre à venir</th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Quantitée accordée par le PF
                                district</th>
                        </tr>
                    </thead>
                    <tbody class="text-[16px]">
                        @forelse ($consommations_all as $consommation)
                            <tr class="odd:bg-white even:bg-blue-50 hover:bg-blue-100 transition-colors font-semibold">
                                <td class="border p-2 text-[14px] font-semibold">{{ $consommation->medicament->nom }}
                                </td>
                                <td class="border p-2 text-center">{{ $consommation->qte_dispo_deb_periode ?? '--' }}
                                </td>
                                <td class="border p-2 text-center">{{ $consommation->qte_recu ?? '--' }}</td>
                                <td class="border p-2 text-center text-red-600">
                                    {{ $consommation->qte_en_stock ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->qte_utilisee ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->nb_beneficiaire ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->perimee ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->perte_avarie ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->qte_retour_cameg ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->nb_jour_rupture ?? '--' }}</td>
                                <td class="border p-2 text-center">{{ $consommation->qte_restante ?? '--' }}</td>
                                <td class="border p-2 text-center text-red-600">
                                    {{ $consommation->stock_securite ?? '--' }}</td>
                                <td class="border p-2 text-center text-red-600">{{ $consommation->cmma ?? '--' }}</td>
                                <td class="border p-2 text-center text-red-600">
                                    {{ $consommation->cmd_trim_svt ?? '--' }}</td>
                                <td class="border p-2 text-center text-red-600">
                                    {{ $consommation->qte_accordee_pf_district ?? '--' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center p-4 text-gray-500">Aucune consommation
                                    enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif ($formulaireVisible)
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

            <div class="flex justify-between p-2 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <p class="text-blue-900">Choisir entre FS et ASC pour continuer:</p>
                    <div>
                        <select wire:model.live="type_structure" wire:change="chargerMedicaments"
                            onchange="viderTousLesInputs()"
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
                        <select wire:model.live="periode_choisie"
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
                <form wire:submit.prevent="ajouterConsommation">
                    <table class="min-w-full table-auto text-sm text-blue-900">
                        <thead
                            class="bg-gray-100 text-[14px] bg-blue-100 text-blue-900 text-justify sticky top-0 bg-white z-10">
                            <tr class="bg-blue-100 text-center">
                                <th class="border border-gray-300 p-1 bg-white text-black">Désignation du produit</th>
                                <th class="border border-gray-300 p-1">Stock disponible en début de trimestre</th>
                                <th class="border border-gray-300 p-1">Quantité reçue dans le trimestre</th>
                                <th class="border border-gray-300 p-1 bg-white text-red-600">Quantité en Stock</th>
                                <th class="border border-gray-300 p-1">Quantité utilisé</th>
                                <th class="border border-gray-300 p-1">Nombre de bénéficiaire</th>
                                <th class="border border-gray-300 p-1">Périmé</th>
                                <th class="border border-gray-300 p-1">Pertes et avariées</th>
                                <th class="border border-gray-300 p-1">Quantitée retournée à la CAMEG</th>
                                <th class="border border-gray-300 p-1">Nombre de jour de rupture</th>
                                <th class="border border-gray-300 p-1">Quantité restante en Stock en fin du trimestre
                                </th>
                                <th class="border border-gray-300 bg-white text-red-600 p-1">Stock de sécurité pour le
                                    trimestre à venir</th>
                                <th class="border border-gray-300 bg-white text-red-600 p-1">CMM ajustéé (CMMa)</th>
                                <th class="border border-gray-300 bg-white text-red-600 p-1">Qantitée commandée pour le
                                    trimestre à venir</th>
                            </tr>
                        </thead>
                        <tbody class="text-[16px] font-semibold">
                            @foreach ($medicaments as $index => $medicament)
                                <tr class="odd:bg-white even:bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <td class="border border-gray-200 font-medium text-blue-900">
                                        {{ $medicament->nom }}
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="stock_debut_{{ $index }}"
                                            oninput="calculerStock({{ $index }}), calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.stk_dsp_deb_trim"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.stk_dsp_deb_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="qte_recu_{{ $index }}"
                                            oninput="calculerStock({{ $index }}), checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.qte_get_in_trim"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.qte_get_in_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="border border-gray-200 p-0 m-0 bg-gray-100">
                                        <input type="number" readonly id="qte_en_stock_{{ $index }}"
                                            wire:model.live="consommations.{{ $index }}.qte_en_stock"
                                            class="w-full h-full bg-white border-none text-sm text-blue-900 text-center text-[17px] 
                                                                   font-semibold text-red-500" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="qte_used_{{ $index }}"
                                            oninput="calculerStockSecurite({{ $index }}), calculerStock({{ $index }}), checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.qte_used"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.qte_used') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="nb_beneficiaire_{{ $index }}"
                                            oninput="checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.nb_beneficiaire"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.nb_beneficiaire') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="perimee_{{ $index }}"
                                            oninput="checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.perimee"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.perimee') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="perte_avarie_{{ $index }}"
                                            oninput="checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.perte_avarie"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.perte_avarie') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="qte_ret_cameg_{{ $index }}"
                                            oninput="checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.qte_ret_cameg"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.qte_ret_cameg') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="nb_jour_rupture_{{ $index }}"
                                            oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.nb_jour_rupture"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.nb_jour_rupture') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="p-0 m-0 border border-gray-200">
                                        <input type="number" id="qte_stock_fin_trim_{{ $index }}"
                                            oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.qte_stock_fin_trim"
                                            class="w-full h-full bg-transparent border-none text-sm text-blue-900 placeholder:text-gray-400 
                                                                   focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600
                                                                   @error('consommations.' . $index . '.qte_stock_fin_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                            placeholder="Saisir..." min="0" step="1" />
                                    </td>

                                    <td class="border border-gray-200 p-0 m-0 bg-gray-100">
                                        <input type="number" readonly id="stk_de_securite_{{ $index }}"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.stk_de_securite"
                                            class="w-full h-full bg-white border-none text-sm text-blue-900 text-center text-[17px] 
                                                                   font-semibold text-red-500" />
                                    </td>

                                    <td class="border border-gray-200 p-0 m-0 bg-gray-100">
                                        <input type="number" readonly id="ccma_{{ $index }}"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.ccma"
                                            class="w-full h-full bg-white border-none text-sm text-blue-900 text-center text-[17px] 
                                                                   font-semibold text-red-500" />
                                    </td>

                                    <td class="border border-gray-200 p-0 m-0 bg-gray-100">
                                        <input type="number" readonly id="cmd_trim_svt_{{ $index }}"
                                            wire:model.debounce.500ms="consommations.{{ $index }}.cmd_trim_svt"
                                            class="w-full h-full bg-white border-none text-sm text-blue-900 text-center text-[17px] 
                                                                   font-semibold text-red-500" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

    // Fonction pour vider tous les inputs numériques de la table
    function viderTousLesInputs() {
        // Obtenir tous les inputs de type number sauf ceux en readonly
        const inputs = document.querySelectorAll('table input[type="number"]:not([readonly])');

        // Vider chaque input utilisateur
        inputs.forEach(function(input) {
            input.value = null;

            // Déclencher un événement 'input' pour informer Livewire
            input.dispatchEvent(new Event('input', {
                bubbles: true
            }));
        });

        // Vider aussi les champs readonly (calculés)
        const readonlyInputs = document.querySelectorAll('table input[type="number"][readonly]');
        readonlyInputs.forEach(function(input) {
            input.value = null;
        });
    }
</script>

</div>
