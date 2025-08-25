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
        wire:target="afficherFormulaire,afficherDetails,chercherMedicament,exporterPDF,exporterPdfDepuisTable, exporterExcel,previousSlide,afficherTableau,nextSlide,afficherTableConsommations, validerConsommation, filtrerParPériode,ajouterConsommation,renitialiserMedicament,toggleHiddenCards,ajouterMedicament,showEditInput,activerEdition,choix,chargerDepuisSession,chargerMedicaments,chercherConsommations,soumettreConsommation,enregistrerQteAccorde"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>
    <!-- En-tête -->
    <div class="flex justify-between items-center relative mb-4">
        <h2 class="text-2xl font-semibold text-teal-600">
            @if ($tableauVisible)
                <span class="flex justify-between">
                    <div class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-box-fill"></i>
                        </div>
                        <p>Consommations des médicaments
                            @if (in_array(auth()->user()->role->nom_role, ['Formation sanitaire', 'District']))
                                <span class="font-semibold text-[18px] text-gray-500"> |
                                    {{ Auth::user()->entity['nom'] }}</span>
                            @endif
                        </p>
                    </div>
                </span>
            @elseif ($formulaireVisible)
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-plus"></i>
                    </div>
                    <p>Ajouter une consommation
                        @if (in_array(auth()->user()->role->nom_role, ['Formation sanitaire', 'District']))
                            <span class="font-semibold text-[18px] text-gray-500"> |
                                {{ Auth::user()->entity['nom'] }}</span>
                        @endif
                    </p>
                </span>
            @endif
        </h2>
        <div class="flex justify-center gap-2">
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
            @if ($voirDetails || $formulaireVisible)
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
            @if ($afficherTable)
                <!-- Champ de recherche - À placer AVANT le tableau -->
                <div class="flex justify-between">
                    <div class="search-container mb-4 min-w-[500px]">
                        <div
                            class="flex justify-between items-center gap-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <div class="search-input-wrapper relative flex-1 max-w-md">
                                <i
                                    class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="searchInput"
                                    class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-gray-50 focus:bg-white transition-colors"
                                    placeholder="Filtrez dans tous les champs...">
                                <button
                                    class="clear-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 rounded hidden"
                                    id="clearSearch" type="button">
                                    <i class="bi bi-x-lg text-sm"></i>
                                </button>
                            </div>
                            <div class="search-results text-sm text-gray-600" id="searchResults">
                                <span id="resultsText">Tous les résultats affichés</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center items-center gap-3 mb-2">
                        <i class="bi bi-funnel text-teal-600 text-lg"></i>
                        <span class="text-sm font-medium text-gray-700">Filtrez par période</span>

                        <select wire:model="conso_liste_filter" wire:change="afficherTableConsommations"
                            class="w-64 px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            @foreach ($periodes_lists as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <style>
                    /* Style pour les lignes cachées par la recherche */
                    .table-row-hidden {
                        display: none !important;
                    }

                    /* Style pour surligner les termes de recherche */
                    .highlight {
                        background-color: #fef08a;
                        padding: 1px 2px;
                        border-radius: 2px;
                        font-weight: 600;
                    }

                    /* Animation pour les résultats */
                    .fade-in {
                        animation: fadeIn 0.3s ease-in;
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                        }

                        to {
                            opacity: 1;
                        }
                    }
                </style>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="table-container">
                        <table class="min-w-full divide-y rounded-xl divide-gray-200" id="dataTable">
                            <thead class="bg-gradient-to-r from-gray-50 rounded-xl to-gray-100">
                                <tr class="text-center">
                                    <th
                                        class="text-center col-num px-3 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        N°
                                    </th>
                                    <th
                                        class="text-center col-formation px-3 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        FS
                                    </th>
                                    <th
                                        class="col-structure px-3 py-3 center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Structure
                                    </th>
                                    <th
                                        class="col-periode px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Période
                                    </th>
                                    <th
                                        class="col-district px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        District
                                    </th>
                                    <th
                                        class="col-etat px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        État
                                    </th>
                                    <th
                                        class="col-soumission px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Date soumission
                                    </th>
                                    <th
                                        class="col-validation px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Date validation
                                    </th>
                                    <th
                                        class="col-export px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Export
                                    </th>
                                    <th
                                        class="col-detail px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100" id="tableBody">
                                @forelse ($user_consommations as $consommation)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 table-row"
                                        data-row-index="{{ $loop->iteration }}">
                                        <td
                                            class="col-num px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="col-formation px-3 py-3 text-center" data-search-field="formation">
                                            <div class="text-sm font-medium text-gray-900 truncate"
                                                title="{{ $consommation->formationSanitaire->nom }}">
                                                {{ $consommation->formationSanitaire->nom }}
                                            </div>
                                        </td>
                                        <td class="col-structure px-3 py-2 whitespace-nowrap text-center"
                                            data-search-field="structure">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $consommation->acteur == 'FS' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $consommation->acteur }}
                                            </span>
                                        </td>
                                        <td class="col-periode text-center px-3 py-2 whitespace-nowrap text-sm text-gray-900"
                                            data-search-field="periode">
                                            {{ $consommation->periode->nom }}
                                        </td>
                                        <td class="col-district text-center px-3 py-2" data-search-field="district">
                                            <div class="text-sm text-gray-900 truncate"
                                                title="{{ $consommation->formationSanitaire->district->nom }}">
                                                {{ $consommation->formationSanitaire->district->nom }}
                                            </div>
                                        </td>
                                        <td class="col-etat px-3 text-center py-2 whitespace-nowrap"
                                            data-search-field="etat">
                                            <span
                                                class="inline-flex text-center items-center px-2 py-1 rounded-full text-xs font-medium
                                @switch($consommation->etat)
                                    @case('en_cours')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('soumis')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('valide')
                                        bg-green-100 text-green-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                                @switch($consommation->etat)
                                                    @case('en_cours')
                                                        <i class="bi bi-clock-fill mr-1"></i>Non soumis
                                                    @break

                                                    @case('soumis')
                                                        <i class="bi bi-hourglass-split mr-1"></i>Soumis
                                                    @break

                                                    @case('valide')
                                                        <i class="bi bi-check-circle-fill mr-1"></i>Validé
                                                    @break

                                                    @default
                                                        {{ $consommation->etat }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="col-soumission text-center px-3 py-2 whitespace-nowrap"
                                            data-search-field="soumission">
                                            @if ($consommation->etat == 'en_cours')
                                                <div class="flex items-center">
                                                    <i class="bi bi-exclamation-triangle text-amber-500 mr-1"></i>
                                                    <span class="text-xs text-amber-700">À soumettre</span>
                                                </div>
                                            @elseif($consommation->etat == 'soumis' || $consommation->etat == 'valide')
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($consommation->submitted_at)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($consommation->submitted_at)->format('H:i') }}
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400">--</span>
                                            @endif
                                        </td>
                                        <td class="col-validation text-center px-3 py-2 whitespace-nowrap"
                                            data-search-field="validation">
                                            @if ($consommation->etat == 'valide')
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($consommation->updated_at)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-green-600">
                                                    {{ \Carbon\Carbon::parse($consommation->updated_at)->format('H:i') }}
                                                </div>
                                            @elseif($consommation->etat == 'soumis')
                                                @if (auth()->check() && auth()->user()->role->nom_role === 'District')
                                                    <div class="flex items-center text-center">
                                                        <i
                                                            class="bi bi-exclamation-triangle text-amber-500 mr-1 text-center"></i>
                                                        <span class="text-xs text-amber-700 text-center">À
                                                            valider</span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-center">
                                                        <div
                                                            class="animate-spin rounded-full h-3 w-2 border-b-2 border-blue-500 mr-1">
                                                        </div>
                                                        <span class="text-xs text-blue-600">En cours</span>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">--</span>
                                            @endif
                                        </td>
                                        <td class="col-export text-center px-3 py-3 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-4">
                                                <!-- Bouton PDF -->
                                                <button
                                                    wire:key="pdf-{{ $consommation->periode->id }}-{{ $consommation->acteur }}-{{ $consommation->formation_sanitaire_id }}"
                                                    wire:click="exporterPdfDepuisTable({{ $consommation->id }}, {{ $consommation->formationSanitaire->id }}, {{ $consommation->periode->id }}, '{{ $consommation->acteur }}')"
                                                    class="flex flex-col items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors"
                                                    title="Exporter en PDF">
                                                    <i class="bi bi-file-earmark-pdf text-2xl"></i>
                                                    <span class="text-xs">PDF</span>
                                                </button>

                                                <!-- Bouton Excel -->
                                                <button
                                                    wire:key="excel-{{ $consommation->periode->id }}-{{ $consommation->acteur }}-{{ $consommation->formation_sanitaire_id }}"
                                                    wire:click="exporterExcel({{ $consommation->formationSanitaire->id }}, {{ $consommation->periode->id }}, '{{ $consommation->acteur }}')"
                                                    class="flex flex-col items-center p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition-colors"
                                                    title="Exporter en Excel">
                                                    <i class="bi bi-file-earmark-excel text-2xl"></i>
                                                    <span class="text-xs">Excel</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="col-detail text-center px-3 py-3 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-4">
                                                @if (auth()->check() && auth()->user()->role->nom_role === 'Formation sanitaire' && $consommation->etat == 'en_cours')
                                                    <button
                                                        wire:key="soumettre-{{ $consommation->periode->id }}-{{ $consommation->acteur }}-{{ $consommation->formation_sanitaire_id }}"
                                                        wire:click="soumettreConsommation({{ $consommation->id }})"
                                                        class="flex flex-col items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors"
                                                        title="Soumettre la consommation">
                                                        <i class="bi bi-send-fill text-xl"></i>
                                                        <span class="text-xs">Soumettre</span>
                                                    </button>
                                                @endif
                                                <button
                                                    wire:key="details-{{ $consommation->periode->id }}-{{ $consommation->acteur }}-{{ $consommation->formation_sanitaire_id }}"
                                                    wire:click="afficherDetails({{ $consommation->periode->id }}, '{{ $consommation->acteur }}', {{ $consommation->formation_sanitaire_id }})"
                                                    class="flex flex-col items-center p-2 text-teal-600 hover:text-teal-800 hover:bg-teal-50 rounded transition-colors"
                                                    title="Voir détails">
                                                    <i class="bi bi-eye text-lg"></i>
                                                    <span class="text-xs">Détails</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="10">
                                                <div class="flex flex-col items-center justify-center py-10 text-center">
                                                    <i class="bi bi-inbox text-4xl text-gray-400 mb-2"></i>
                                                    <p class="text-sm text-gray-500">Aucune consommation enregistrée pour
                                                        le moment.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr id="noResultsRow" style="display: none;">
                                        <td colspan="10">
                                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                                <i class="bi bi-search text-5xl text-gray-300 mb-3"></i>
                                                <h3 class="text-lg font-medium text-gray-500 mb-2">Aucun résultat trouvé
                                                </h3>
                                                <p class="text-sm text-gray-400 mb-4">Votre recherche "<span
                                                        id="searchTermDisplay" class="font-semibold"></span>" ne
                                                    correspond à aucun élément.</p>
                                                <button id="clearSearchFromMessage"
                                                    class="px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500">
                                                    <i class="bi bi-arrow-counterclockwise mr-1"></i>
                                                    Effacer la recherche
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Fonction d'initialisation de la recherche
                            function initializeSearch() {
                                const searchInput = document.getElementById('searchInput');
                                const clearSearch = document.getElementById('clearSearch');
                                const resultsText = document.getElementById('resultsText');

                                // Vérifier que les éléments existent
                                if (!searchInput || !clearSearch || !resultsText) {
                                    console.log('Éléments de recherche non trouvés, tentative de réinitialisation...');
                                    return false;
                                }

                                const tableRows = document.querySelectorAll('.table-row');
                                const emptyRow = document.getElementById('emptyRow');
                                const noResultsRow = document.getElementById('noResultsRow');
                                const searchTermDisplay = document.getElementById('searchTermDisplay');
                                const clearSearchFromMessage = document.getElementById('clearSearchFromMessage');
                                const totalRows = tableRows.length;

                                // Fonction pour normaliser le texte (supprimer accents, convertir en minuscules)
                                function normalizeText(text) {
                                    return text.toLowerCase()
                                        .normalize('NFD')
                                        .replace(/[\u0300-\u036f]/g, '')
                                        .trim();
                                }

                                // Fonction pour surligner le texte recherché
                                function highlightText(text, searchTerm) {
                                    if (!searchTerm) return text;
                                    const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                                    return text.replace(regex, '<span class="highlight">$1</span>');
                                }

                                // Fonction pour supprimer le surlignage
                                function removeHighlight() {
                                    const highlighted = document.querySelectorAll('.highlight');
                                    highlighted.forEach(el => {
                                        const parent = el.parentNode;
                                        parent.replaceChild(document.createTextNode(el.textContent), el);
                                        parent.normalize();
                                    });
                                }

                                // Fonction de recherche
                                function performSearch() {
                                    const searchTerm = normalizeText(searchInput.value);
                                    let visibleCount = 0;
                                    removeHighlight();

                                    tableRows.forEach(row => {
                                        let rowVisible = false;

                                        if (searchTerm === '') {
                                            rowVisible = true;
                                        } else {
                                            const allTextContent = row.textContent;
                                            if (normalizeText(allTextContent).includes(searchTerm)) {
                                                rowVisible = true;

                                                // Surligner le terme dans les champs spécifiques
                                                const searchFields = row.querySelectorAll('[data-search-field]');
                                                searchFields.forEach(field => {
                                                    const textNodes = [];
                                                    const walker = document.createTreeWalker(
                                                        field,
                                                        NodeFilter.SHOW_TEXT,
                                                        null,
                                                        false
                                                    );

                                                    let node;
                                                    while (node = walker.nextNode()) {
                                                        textNodes.push(node);
                                                    }

                                                    textNodes.forEach(textNode => {
                                                        if (normalizeText(textNode.textContent).includes(
                                                                searchTerm)) {
                                                            const span = document.createElement('span');
                                                            span.innerHTML = highlightText(textNode
                                                                .textContent, searchInput.value);
                                                            textNode.parentNode.replaceChild(span,
                                                                textNode);
                                                        }
                                                    });
                                                });
                                            }
                                        }

                                        // Afficher/masquer la ligne
                                        if (rowVisible) {
                                            row.classList.remove('table-row-hidden');
                                            row.classList.add('fade-in');
                                            visibleCount++;
                                        } else {
                                            row.classList.add('table-row-hidden');
                                            row.classList.remove('fade-in');
                                        }
                                    });

                                    // Gérer l'affichage des messages
                                    if (searchTerm === '') {
                                        // Pas de recherche : afficher la ligne vide originale si pas de données
                                        if (emptyRow) {
                                            emptyRow.style.display = totalRows === 0 ? '' : 'none';
                                        }
                                        if (noResultsRow) {
                                            noResultsRow.style.display = 'none';
                                        }
                                    } else {
                                        // Recherche active : masquer la ligne vide originale
                                        if (emptyRow) {
                                            emptyRow.style.display = 'none';
                                        }

                                        // Afficher le message "Aucun résultat" si besoin
                                        if (noResultsRow) {
                                            if (visibleCount === 0) {
                                                noResultsRow.style.display = '';
                                                if (searchTermDisplay) {
                                                    searchTermDisplay.textContent = searchInput.value;
                                                }
                                            } else {
                                                noResultsRow.style.display = 'none';
                                            }
                                        }
                                    }

                                    // Mettre à jour le compteur de résultats
                                    updateResultsCount(visibleCount);

                                    // Afficher/masquer le bouton de suppression
                                    if (clearSearch) {
                                        clearSearch.style.display = searchInput.value ? 'block' : 'none';
                                    }
                                }

                                // Fonction pour mettre à jour le compteur de résultats
                                function updateResultsCount(visibleCount) {
                                    if (!resultsText) return;

                                    if (searchInput.value === '') {
                                        resultsText.textContent = totalRows > 0 ? 'Tous les résultats affichés' : 'Aucune donnée';
                                    } else if (visibleCount === 0) {
                                        resultsText.textContent = 'Aucun résultat trouvé';
                                    } else if (visibleCount === 1) {
                                        resultsText.textContent = '1 résultat trouvé';
                                    } else {
                                        resultsText.textContent = `${visibleCount} résultats trouvés`;
                                    }
                                }

                                // Supprimer les anciens écouteurs s'ils existent
                                if (searchInput._searchInitialized) {
                                    return true;
                                }

                                // Marquer comme initialisé
                                searchInput._searchInitialized = true;

                                // Écouteur d'événements pour la saisie
                                searchInput.addEventListener('input', function() {
                                    clearTimeout(this.searchTimeout);
                                    this.searchTimeout = setTimeout(performSearch, 150);
                                });

                                // Écouteur pour le bouton de suppression depuis le message
                                if (clearSearchFromMessage) {
                                    clearSearchFromMessage.addEventListener('click', function() {
                                        searchInput.value = '';
                                        performSearch();
                                        searchInput.focus();
                                    });
                                }

                                // Écouteur pour le bouton de suppression
                                if (clearSearch) {
                                    clearSearch.addEventListener('click', function() {
                                        searchInput.value = '';
                                        performSearch();
                                        searchInput.focus();
                                    });
                                }

                                // Écouteur pour la touche Échap
                                searchInput.addEventListener('keydown', function(e) {
                                    if (e.key === 'Escape') {
                                        if (this.value) {
                                            this.value = '';
                                            performSearch();
                                        }
                                    }
                                });

                                // Initialiser l'affichage
                                updateResultsCount(totalRows);
                                return true;
                            }

                            // Initialiser au chargement
                            initializeSearch();

                            // Réinitialiser après les mises à jour Livewire
                            document.addEventListener('livewire:updated', function() {
                                setTimeout(() => {
                                    initializeSearch();
                                }, 100);
                            });
                            // Gestion pour Livewire v2
                            window.addEventListener('livewire:load', function() {
                                setTimeout(() => {
                                    initializeSearch();
                                }, 100);
                            });
                        });
                    </script>

                @endif
                @if ($voirDetails)
                    <div
                        class="relative bg-gradient-to-r from-white via-blue-50 to-indigo-50 border border-blue-100 px-6 py-4  shadow-lg backdrop-blur-sm">
                        <!-- Ligne décorative supérieure -->
                        <div
                            class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-600">
                        </div>
                        <div class="flex justify-between">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex items-center gap-3 text-sm font-medium text-gray-700">
                                        @if (auth()->check() && in_array(auth()->user()->role->nom_role, ['District', 'Administrateur']))
                                            <div
                                                class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-lg border border-blue-200/50 shrink-0">
                                                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>

                                                <span class="text-blue-800 font-bold truncate max-w-[150px]"
                                                    title="{{ $fs_choisie->nom }}">{{ $fs_choisie->nom }}</span>
                                            </div>
                                            <div
                                                class="w-px h-6 bg-gradient-to-b from-transparent via-blue-300 to-transparent shrink-0">
                                            </div>
                                        @endif

                                        <div
                                            class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-lg border border-blue-200/50 shrink-0">
                                            <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                                </path>
                                            </svg>
                                            <span class="text-gray-600 whitespace-nowrap">Structure :</span>
                                            <span class="text-indigo-800 font-bold">{{ $structure_defaut }}</span>
                                        </div>

                                        <div
                                            class="w-px h-6 bg-gradient-to-b from-transparent via-blue-300 to-transparent shrink-0">
                                        </div>

                                        <div
                                            class="flex items-center gap-2 bg-white/70 px-3 py-1.5 rounded-lg border border-blue-200/50 shrink-0">
                                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-gray-600 whitespace-nowrap">Période :</span>
                                            <span class="text-green-800 font-bold truncate max-w-[120px]"
                                                title="{{ $periode_actuelle->nom }}">{{ $periode_actuelle->nom }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deuxième ligne : Contrôles de filtrage -->
                            <div class="flex items-center justify-end">
                                <div class="flex items-center gap-1">

                                    @if (auth()->check() && in_array(auth()->user()->role->nom_role, ['District', 'Administrateur']))
                                        <div class="">
                                            <select wire:model="fs" wire:change="chercherConsommations"
                                                class="w-32 bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                                @foreach ($formation_sanitaire as $fs)
                                                    <option value="{{ $fs->id }}">{{ $fs->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="">
                                        <select wire:model="structure_defaut" wire:change="chercherConsommations"
                                            class="w-20 bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer text-center">
                                            <option value="FS">FS</option>
                                            <option value="ASC">ASC</option>
                                        </select>
                                    </div>

                                    <div class="">
                                        <select wire:model="periode_search" wire:change="chercherConsommations"
                                            class="w-64 bg-white border border-blue-300 text-blue-900 text-sm rounded-lg shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                                            @foreach ($periodes_all as $periode)
                                                <option value="{{ $periode->id }}">
                                                    {{ $periode->nom }} :
                                                    {{ $periode->mois_debut }}-{{ $periode->mois_fin }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Effet de brillance subtil -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent rounded-xl pointer-events-none">
                        </div>
                    </div>
                    @if ($this->conso)
                        <div class="flex justify-between items-center mb-4 mt-4"
                            wire:key="header-buttons-{{ $this->conso->id }}-{{ $this->conso->etat ?? 'inconnu' }}">
                            <div
                                class="flex border border-gray-300 rounded-full overflow-hidden min-w-[300px]
                              focus-within:border-teal-500 focus-within:ring-1 focus-within:ring-teal-500">
                                <!-- Input lié à la propriété Livewire -->
                                <input list="medicamentListe" id="medicamentSelector" name="medicament"
                                    placeholder="Nom du médicament..."
                                    class="flex-1 px-4 py-2 border-0 focus:outline-none "
                                    wire:model.defer="medicamentRecherche">
                                <button type="button" wire:click="chercherMedicament"
                                    class="px-3 py-2 bg-teal-600 text-white hover:bg-teal-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <datalist id="medicamentListe">
                                @foreach ($consommations_all as $consommation)
                                    @php
                                        $estCachee =
                                            $consommation->cmma == 0 &&
                                            $consommation->stock_securite == 0 &&
                                            $consommation->cmd_trim_svt == 0;
                                    @endphp
                                    @if (!$estCachee || $showHiddenCards)
                                        <option value="{{ $consommation->medicament->nom }}">
                                    @endif
                                @endforeach
                            </datalist>
                            <div class="flex justify-center items-center gap-4">
                        <div class="flex items-center gap-2">
                            @php
                                $visibleCards = $consommations_all->filter(function ($c) use ($showHiddenCards) {
                                    return !($c->cmma == 0 && $c->stock_securite == 0 && $c->cmd_trim_svt == 0) ||
                                        $showHiddenCards;
                                });
                                $totalPages = ceil($visibleCards->count() / 1);
                                $currentPageNumber = floor(($currentSlide ?? 0) / 1) + 1;
                            @endphp

                            @for ($i = 0; $i < $totalPages; $i++)
                                <button wire:click="setCurrentSlide({{ $i * 1 }})"
                                    class="w-3 h-3 rounded-full transition-all duration-300 {{ floor(($currentSlide ?? 0) / 1) == $i ? 'bg-teal-600 w-8' : 'bg-gray-300 hover:bg-gray-400' }}">
                                </button>
                            @endfor
                        </div>
                        <div class="text-sm text-gray-500 font-medium">
                            <span class="text-teal-600">{{ $currentPageNumber }}</span> / {{ $totalPages }}
                        </div>
                    </div>
                            <!-- Boutons -->
                            <div class="flex gap-2">
                                <!-- Valider (District + soumis) -->
                            </div>
                        </div>
                    @endif
                    <div class="overflow-hidden rounded-3xl">
                        <div class="flex transition-transform duration-500 ease-in-out"
                            style="transform: translateX(-{{ ($currentSlide ?? 0) * 100 }}%)">
                            @php
                                $cardIndex = 0;
                            @endphp
                            @foreach ($visibleCards->chunk(1) as $cardPair)
                                <div class="flex-shrink-0 min-w-full flex gap-4 px-2">
                                    @foreach ($cardPair as $consommation)
                                        @php
                                            $user = auth()->user();
                                            $role = $user?->role->nom_role;
                                            $accordee = $consommation->qte_accordee;
                                            $medicament_id = $consommation->medicament_id;
                                            $consommation_id = $consommation->consommation_id;
                                            $stock_theo =
                                                $consommation->qte_en_stock -
                                                $consommation->qte_utilisee -
                                                $consommation->qte_retour_cameg -
                                                $consommation->perte_avarie -
                                                $consommation->perimee;
                                            $perte_non_dec = $consommation->qte_restante - $stock_theo;

                                        @endphp

                                        <div class="bg-white rounded-3xl border border-teal-600 overflow-hidden min-w-[70%] min-h-auto mx-auto"
                                            wire:key="consommation-{{ $consommation->consommation_id }}">
                                            <!-- Header avec gradient -->
                                            <div
                                                class="bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-700 px-6 py-3 relative overflow-hidden">
                                                <div
                                                    class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24">
                                                </div>
                                                <div
                                                    class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16">
                                                </div>
                                                <div class="relative z-10">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm text-white">
                                                                {{ $consommation->medicament->code }}
                                                            </div>
                                                            <div>
                                                                <h2 class="text-xl font-bold text-white mb-1">
                                                                    {{ $consommation->medicament->nom }}</h2>
                                                                <p class="text-white text-sm mt-1">Conditionnement :
                                                                    {{ $consommation->medicament->conditionnement }} :
                                                                    {{ $consommation->medicament->qte_par_conditionnement }}
                                                                    {{ $consommation->medicament->format }}</p>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium">
                                                            @if ($this->conso->etat == 'soumis')
                                                                <i class="bi bi-clock mr-1"></i>En validation
                                                            @elseif ($this->conso->etat == 'valide')
                                                                <i class="bi bi-check-circle mr-1"></i>Validé
                                                            @elseif ($this->conso->etat == 'en_cours')
                                                                <i class="bi bi-x-circle-fill mr-1"></i>Non soumis
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4 ">
                                                <!-- Métriques principales -->
                                                <div class="grid grid-cols-3 gap-6 mb-3">
                                                    <div
                                                        class="bg-slate-100 border-l-4 border-blue-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div
                                                                class="bg-blue-100 text-blue-600 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4zM3 8a1 1 0 000 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a1 1 0 100-2H3zm8 6a1 1 0 11-2 0V9a1 1 0 112 0v5z" />
                                                                </svg>
                                                            </div>
                                                            <span class="text-blue-700 ">Stock total en
                                                                début</span>
                                                        </div>
                                                        <div class="text-3xl font-bold text-blue-700">
                                                            {{ $consommation->qte_en_stock }}
                                                        </div>
                                                    </div>

                                                    <!-- Carte CMM ajustée -->
                                                    <div
                                                        class="bg-slate-100 border-l-4 border-orange-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div
                                                                class="bg-orange-100 text-orange-600 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            <span class="text-orange-700 ">CMM
                                                                ajustée</span>
                                                        </div>
                                                        <div class="text-3xl font-bold text-orange-700">
                                                            {{ $consommation->cmma }}
                                                        </div>
                                                    </div>

                                                    <!-- Carte Stock sécurité -->
                                                    <div
                                                        class="bg-slate-100 border-l-4 border-teal-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div
                                                                class="bg-teal-100 text-teal-600 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            <span class="text-teal-700">Stock de
                                                                sécurité</span>
                                                        </div>
                                                        <div class="text-3xl font-bold text-teal-700">
                                                            {{ $consommation->stock_securite }}
                                                        </div>
                                                    </div>

                                                    <!-- Carte Qté commandée -->
                                                    <div
                                                        class="bg-slate-100 border-l-4 border-gray-500 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">
                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div
                                                                class="bg-gray-200 text-gray-700 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                                                </svg>
                                                            </div>
                                                            <span class="text-gray-700">Quantité
                                                                commandée</span>
                                                        </div>
                                                        <div class="text-3xl font-bold text-gray-700">
                                                            {{ $consommation->cmd_trim_svt }}
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="bg-slate-100 border-l-4 border-indigo-800 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center h-full text-center">

                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div
                                                                class="bg-indigo-200 text-indigo-800 w-10 h-10 flex items-center justify-center rounded-full shadow">
                                                                <i class="bi bi-check2-square text-indigo-800 text-lg"></i>
                                                            </div>
                                                            <span class="text-indigo-800">Quantité accordée</span>
                                                        </div>

                                                        @if ($role === 'Formation sanitaire' && $this->conso->etat == 'soumis')
                                                            <div class="text-indigo-800 text-lg font-medium">
                                                                <i class="bi bi-hourglass-split mr-2"></i>En cours de
                                                                validation...
                                                            </div>
                                                        @elseif ($role === 'Formation sanitaire' && $this->conso->etat == 'en_cours')
                                                            <div class="text-red-600 flex justify-center gap-2">
                                                                <i class="bi bi-exclamation-triangle"></i>
                                                                <p>Soumettre pour validation !</p>
                                                            </div>
                                                        @elseif ($role === 'Formation sanitaire' && $this->conso->etat == 'valide')
                                                            <div class="text-3xl font-bold text-indigo-800">
                                                                {{ $accordee ?? 'Non validé' }}
                                                            </div>
                                                        @elseif ($role === 'District')
                                                            @if ($not_edit[$medicament_id])
                                                                <div class="flex items-center justify-between w-full">
                                                                    <div class="text-3xl font-bold text-indigo-800">
                                                                        {{ $accordee ?? '--' }}
                                                                    </div>
                                                                    @if ($this->conso->etat == 'soumis' && $consommation->cmd_trim_svt >= 1)
                                                                        <button type="button"
                                                                            wire:click="showEditInput({{ $medicament_id }}, {{ $consommation_id }})"
                                                                            class="w-10 h-10 flex items-center justify-center bg-white rounded-lg hover:bg-gray-100 border border-gray-300 transition-colors">
                                                                            <i class="bi bi-pen-fill text-indigo-800"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @endif

                                                            @if ($edit[$medicament_id])
                                                                <form
                                                                    wire:submit.prevent="enregistrerQteAccorde({{ $consommation_id }}, {{ $consommation->medicament_id }})"
                                                                    class="mt-3 w-full">
                                                                    <div class="flex gap-3 items-center">
                                                                        <input type="number"
                                                                            wire:model="quantites_accordees.{{ $medicament_id }}"
                                                                            placeholder="Saisir quantité"
                                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm"
                                                                            min="0" step="1" required>
                                                                        <button type="submit"
                                                                            class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 text-sm font-medium border border-gray-300 transition-colors">
                                                                            OK
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        @endif
                                                        @if ($role === 'Administrateur' && $this->conso->etat == 'valide')
                                                            <div class="text-3xl font-bold text-indigo-800">
                                                                {{ $accordee ?? 'Non validé' }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Différence ajout/retrait -->
                                                    @if (
                                                        !is_null($accordee) &&
                                                            $accordee != $consommation->cmd_trim_svt &&
                                                            !($role === 'Formation sanitaire' && $this->conso->etat !== 'valide'))
                                                        <div
                                                            class="flex-1 p-4 bg-gradient-to-br from-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-50 to-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-100 rounded-xl border border-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-200">
                                                            <div class="flex items-center gap-2 mb-3">
                                                                <i
                                                                    class="bi bi-{{ $accordee > $consommation->cmd_trim_svt ? 'arrow-up-circle' : 'arrow-down-circle' }} text-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-600 text-xl"></i>
                                                                <h3
                                                                    class="text-lg font-bold text-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-800">
                                                                    {{ $accordee > $consommation->cmd_trim_svt ? 'Ajout' : 'Retiré' }}
                                                                </h3>
                                                            </div>
                                                            <div
                                                                class="text-3xl font-bold text-{{ $accordee > $consommation->cmd_trim_svt ? 'emerald' : 'red' }}-800">
                                                                {{ $accordee > $consommation->cmd_trim_svt ? '+' : '' }}{{ $accordee - $consommation->cmd_trim_svt }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div x-data="{ open: false }" class="flex flex-col" wire:ignore>
                                                    <div class="flex justify-center">
                                                        <button type="button" @click="open = !open"
                                                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm rounded-lg transition-colors">
                                                            <span x-text="open ? 'Voir moins' : 'Voir plus'"></span>
                                                        </button>
                                                    </div>
                                                    <div x-show="open" x-transition
                                                        class="details-section bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200 mt-2">
                                                        <h3
                                                            class="text-gray-800 font-bold text-lg mb-4 flex items-center gap-2">
                                                            <div class="w-2 h-2 bg-teal-600 rounded-full"></div>
                                                            Informations détaillées du trimestre
                                                        </h3>
                                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Stock
                                                                    en début</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->qte_dispo_deb_periode }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Quantité reçue
                                                                    durant</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->qte_recu }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Quantité
                                                                    utilisée</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->qte_utilisee }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Nombre de
                                                                    bénéficiaires</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->nb_beneficiaire }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Périmé</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->perimee }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Pertes et
                                                                    avariées</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->perte_avarie }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Quantité retourné à
                                                                    la
                                                                    CAMEG</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->qte_retour_cameg }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Nombre de jours de
                                                                    rupture</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->nb_jour_rupture }}</span>
                                                            </div>

                                                        </div>
                                                        <div class="grid grid-cols-3 gap-3 text-sm mt-4">
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Stock réel en
                                                                    fin</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $consommation->qte_restante }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-gray-600 font-medium">Stock
                                                                    théorique</span>
                                                                <span
                                                                    class="font-bold text-gray-800">{{ $stock_theo }}</span>
                                                            </div>
                                                            <div
                                                                class="flex justify-between items-center p-3 bg-white rounded-lg border {{ $perte_non_dec > 0 ? 'border-yellow-300' : 'border-red-200' }}">
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
                                                        <div
                                                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 relative group mt-4">
                                                            <span class="text-gray-600 font-medium">Écart de stock entre la
                                                                fin
                                                                du trimestre passé et le début de ce trimestre</span>
                                                            <div class="text-right">
                                                                @if ($consommation->a_periode_precedente)
                                                                    @php
                                                                        $ecartClass = match (
                                                                            $consommation->type_ecart
                                                                        ) {
                                                                            'positif' => 'text-green-600',
                                                                            'negatif' => 'text-red-600',
                                                                            default => 'text-gray-800',
                                                                        };
                                                                        $badgeClass = match (
                                                                            $consommation->type_ecart
                                                                        ) {
                                                                            'positif' => 'bg-green-100 text-green-800',
                                                                            'negatif' => 'bg-red-100 text-red-800',
                                                                            default => 'bg-gray-100 text-gray-800',
                                                                        };
                                                                    @endphp
                                                                    <span class="font-bold {{ $ecartClass }}">
                                                                        @if ($consommation->ecart_stock > 0)
                                                                            +
                                                                        @endif
                                                                        {{ $consommation->ecart_stock }}
                                                                    </span>
                                                                @else
                                                                    <span class="font-bold text-gray-400">0</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        @if ($visibleCards->isEmpty())
                            <div class="w-full flex-shrink-0">
                                <div
                                    class="text-center text-gray-500 py-32 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                    <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-2xl font-medium text-gray-400 mb-2">Aucune commande
                                        enregistrée</p>
                                    <p class="text-gray-400">Si vous avez des données enrégistrées cliquer sur
                                        "afficher
                                        les médicaments non commandés"</p>
                                </div>
                            </div>
                        @endif
                        <!-- Boutons de navigation -->
                        <div class="flex justify-between items-center mt-8">
                            <button wire:click="previousSlide"
                                class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ ($currentSlide ?? 0) <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-chevron-left"></i>
                                Précédent
                            </button>
                            @if ($conso)
                                <div class="flex flex-wrap items-center gap-2" wire:key="conso--{{ $conso->id }}">

                                    {{-- Bouton Exporter --}}
                                    <button wire:click="exporterPDF({{ $this->conso->id ?? 0 }})"
                                        class="group inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-slate-900 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-1 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="bi bi-download text-slate-500 group-hover:text-slate-700"></i>
                                        <span>Exporter</span>
                                    </button>

                                    {{-- Bouton Valider --}}
                                    @if ($this->conso->etat === 'soumis' && auth()->check() && optional(auth()->user()->role)->nom_role === 'District')
                                        <button wire:click="validerConsommation({{ $this->conso->id }})"
                                            class="group inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 hover:text-emerald-900 hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i
                                                class="bi bi-check-circle text-emerald-600 group-hover:text-emerald-700"></i>
                                            <span>Valider</span>
                                        </button>
                                    @endif

                                    {{-- Boutons pour Formation sanitaire --}}
                                    @if ($this->conso->etat === 'en_cours' && auth()->check() && auth()->user()->role->nom_role === 'Formation sanitaire')
                                        {{-- Bouton Modifier --}}
                                        <button wire:click="activerEdition({{ $this->conso->id }})"
                                            class="group inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 hover:text-amber-900 hover:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-pencil-square text-amber-600 group-hover:text-amber-700"></i>
                                            <span>Modifier</span>
                                        </button>

                                        {{-- Bouton Soumettre --}}
                                        <button wire:click="soumettreConsommation({{ $this->conso->id }})"
                                            class="group inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-800 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-900 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="bi bi-send-fill text-blue-600 group-hover:text-blue-700"></i>
                                            <span>Soumettre</span>
                                        </button>
                                    @endif
                                </div>
                            @endif
                            <button wire:click="nextSlide"
                                class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                @disabled(floor($currentSlide ?? 0) >= $totalPages - 1)>
                                Suivant
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <!-- Bouton pour médicaments cachés -->
                        
                    </div>
                    @push('scripts')
                        <script>
                            // Variable globale pour l'état des détails
                            let detailsVisible = true;

                            // Fonction pour toggle les détails
                            function toggleDetails() {
                                detailsVisible = !detailsVisible;
                                const detailsSections = document.querySelectorAll('.details-section');
                                const toggleBtn = document.getElementById('toggle-details-btn');

                                detailsSections.forEach(section => {
                                    if (detailsVisible) {
                                        section.style.display = 'block';
                                        section.style.opacity = '0';
                                        setTimeout(() => {
                                            section.style.transition = 'opacity 0.3s ease';
                                            section.style.opacity = '1';
                                        }, 10);
                                    } else {
                                        section.style.transition = 'opacity 0.3s ease';
                                        section.style.opacity = '0';
                                        setTimeout(() => {
                                            section.style.display = 'none';
                                        }, 300);
                                    }
                                });
                                if (detailsVisible) {
                                    toggleBtn.innerHTML = '<i class="bi bi-eye-slash mr-2"></i>Masquer les détails';
                                } else {
                                    toggleBtn.innerHTML = '<i class="bi bi-eye mr-2"></i>Afficher les détails';
                                }
                            }

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
                        </script>
                    @endpush
                @endif
            @endif
            @if ($formulaireVisible)
                <div class="flex justify-between items-center relative mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @php
                                // Conditions pour gérer le disabled visuel
                                $griserTout = empty($type_structure);
                                $griserMedicaments = !$griserTout && empty($periode_choisie);
                            @endphp

                            <div class="flex items-center gap-3 text-sm font-medium text-gray-700">

                                <!-- Structure -->
                                <div
                                    class="flex items-center gap-2 bg-white/70 px-3 py-1 rounded-lg border border-blue-200/50 shrink-0">
                                    <!-- Icône SVG -->
                                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <!-- path ... -->
                                    </svg>
                                    <span class="text-gray-600 whitespace-nowrap">Structure :</span>
                                    <select wire:model.live="type_structure" wire:change="chargerMedicaments"
                                        onchange="viderInput()" id="structure"
                                        class="bg-transparent border-0 text-indigo-800 font-bold focus:outline-none focus:ring-0 cursor-pointer">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="FS">FS</option>
                                        <option value="ASC">ASC</option>
                                    </select>
                                </div>

                                <div
                                    class="w-px h-6 bg-gradient-to-b from-transparent via-blue-300 to-transparent shrink-0">
                                </div>

                                <!-- Médicament -->
                                <div
                                    class="flex items-center gap-2 bg-white/70 px-3 py-1 rounded-lg border border-blue-200/50 shrink-0">
                                    <!-- Icône SVG -->
                                    <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <!-- path ... -->
                                    </svg>
                                    <span class="text-gray-600 whitespace-nowrap">Médicament :</span>
                                    <select id="select_medicament" onchange="afficherFormulaire(this)"
                                        class="bg-transparent border-0 text-green-800 font-bold focus:outline-none focus:ring-0 cursor-pointer max-w-auto
            {{ $griserTout || $griserMedicaments ? 'opacity-50 pointer-events-none select-none' : '' }}">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="all">Tous les produits</option>
                                        @foreach ($medicaments as $index => $medicament)
                                            <option value="{{ $index }}" data-id="{{ $medicament->id }}">
                                                {{ $medicament->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div
                                    class="w-px h-6 bg-gradient-to-b from-transparent via-blue-300 to-transparent shrink-0">
                                </div>

                                <!-- Période -->
                                <div
                                    class=" flex items-center gap-2 bg-white/70 px-3 py-1 rounded-lg border border-blue-200/50 shrink-0">
                                    <!-- Icône SVG -->
                                    <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <!-- path ... -->
                                    </svg>
                                    <span class="text-gray-600 whitespace-nowrap">Période :</span>
                                    <select wire:model.live="periode_choisie" id="periode_search" onchange="viderInput()"
                                        class="bg-transparent border-0 text-purple-800 font-bold focus:outline-none focus:ring-0 cursor-pointer max-w-auto
            {{ $griserTout ? 'opacity-50 pointer-events-none select-none' : '' }}">
                                        @foreach ($periodes_disponibles as $periode)
                                            <option value="{{ $periode->id }}">
                                                {{ $periode->nom }} :
                                                {{ $periode->mois_debut }}-{{ $periode->mois_fin }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Effet de brillance subtil -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent rounded-xl pointer-events-none">
                    </div>
                </div>
                <div
                    class="bg-white transition-opacity duration-200 overflow-auto max-h-[650px] {{ empty($type_structure) ? 'bg-gray-500 opacity-50 pointer-events-none select-none' : '' }}">
                    <div class="w-full flex justify-end pr-4 hidden mb-1" id="close_form">
                        <div onclick="masquerToutFormulaire()" title="Masquer tout les formulaire"
                            class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-700 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-md border-0">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                    </div>
                    <form wire:submit.prevent="ajouterConsommation">
                        <div class="space-y-8">
                            @foreach ($medicaments as $index => $medicament)
                                <div wire:key="consommation-{{ $type_structure }}-{{ $periode_choisie }}-{{ $index }}"
                                    class="relative overflow-hidden rounded-2xl shadow-2xl border-0 p-8 hidden glass-effect card-hover bg-white"
                                    id="formulaire_{{ $index }}" wire:key="medicament-{{ $medicament->id }}">

                                    <!-- Header avec gradient -->
                                    <div class="absolute top-0 left-0 right-0 h-2 gradient-bg"></div>

                                    <!-- En-tête du médicament -->
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                                <p class="text-white text-xl">{{ $medicament->code }}</p>
                                            </div>
                                            <div>
                                                <button type="button" onclick="toggleForm({{ $index }})"
                                                    class="group flex items-center gap-3 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 hover:from-purple-600 hover:to-blue-600 font-bold text-xl transition-all duration-300">
                                                    <h3 class="text-lg">{{ $medicament->nom }}</h3>
                                                    <svg class="w-5 h-5 transition-all duration-300 group-hover:rotate-180"
                                                        id="arrowIcon_{{ $index }}" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                                <p class="text-gray-500 text-sm mt-1">Conditionnement :
                                                    {{ $medicament->conditionnement }} :
                                                    {{ $medicament->qte_par_conditionnement }} {{ $medicament->format }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-3 items-center">
                                            <span
                                                class="bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 px-4 py-1 rounded-full text-sm font-bold shadow-md border border-blue-200">
                                                Médicament #{{ $index + 1 }}
                                            </span>
                                            <div>
                                                <button title="Masquer ce formulaire" type="button"
                                                    onclick="masquerFormulaire({{ $index }})"
                                                    class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-700 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-md border-0">
                                                    <i class="bi bi-x-circle-fill text-xl cursor-pointer"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Message de sauvegarde -->
                                    <div class="transition-all duration-300 ease-in-out">
                                        @if (session()->has("message_sauvegarde_$index"))
                                            <div
                                                class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 rounded-lg shadow-md">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-green-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <span
                                                        class="text-green-800 font-medium">{{ session("message_sauvegarde_$index") }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Formulaire avec grille moderne -->
                                    <div id="form_{{ $index }}"
                                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">

                                        <!-- Stock théorique début -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-green-700 mb-3">
                                                <i class="bi bi-calculator text-emerald-500"></i>
                                                <span class="font-bold">Stock théorique initiale</span>
                                            </label>
                                            <input type="number" readonly id="stock_debut_attendu_{{ $index }}"
                                                wire:model.live="consommations.{{ $index }}.stock_debut_attendu"
                                                class="w-full px-3 py-2 border-2 border-emerald-200 rounded-lg bg-gradient-to-r from-emerald-50 to-green-50 text-green-700 font-bold text-center text-sm shadow-inner" />
                                        </div>

                                        <!-- Stock réel début -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-box-seam text-blue-500"></i>
                                                Stock réel initiale
                                            </label>
                                            <input type="number" id="stock_debut_{{ $index }}"
                                                oninput="calculerStock({{ $index }}), calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.stk_dsp_deb_trim"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.stk_dsp_deb_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Quantité reçue -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-arrow-down-circle text-blue-500"></i>
                                                Qte reçue dans le trimestre
                                            </label>
                                            <input type="number" id="qte_recu_{{ $index }}"
                                                oninput="calculerStock({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_get_in_trim"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.qte_get_in_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Quantité en stock -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-red-600 mb-3">
                                                <i class="bi bi-archive text-red-500"></i>
                                                <span class="font-bold">Quantité en Stock</span>
                                            </label>
                                            <input type="number" readonly id="qte_en_stock_{{ $index }}"
                                                wire:model.live="consommations.{{ $index }}.qte_en_stock"
                                                class="w-full px-3 py-2 border-2 border-red-200 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 text-red-500 font-bold text-center text-[17px] shadow-inner" />
                                        </div>

                                        <!-- Quantité utilisée -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-arrow-up-circle text-blue-500"></i>
                                                Quantité utilisée
                                            </label>
                                            <input type="number" id="qte_used_{{ $index }}"
                                                oninput="calculerStockSecurite({{ $index }}), calculerStock({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_used"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.qte_used') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Nombre de bénéficiaires -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-people text-blue-500"></i>
                                                Nombre de bénéficiaires
                                            </label>
                                            <input type="number" id="nb_beneficiaire_{{ $index }}"
                                                oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.nb_beneficiaire"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.nb_beneficiaire') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Périmé -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-exclamation-triangle text-yellow-500"></i>
                                                Périmé
                                            </label>
                                            <input type="number" id="perimee_{{ $index }}"
                                                oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.perimee"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.perimee') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Pertes et avariées -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-trash text-orange-500"></i>
                                                Pertes et avariées
                                            </label>
                                            <input type="number" id="perte_avarie_{{ $index }}"
                                                oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.perte_avarie"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.perte_avarie') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Quantité retournée CAMEG -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-arrow-return-left text-purple-500"></i>
                                                Qté retournée à la CAMEG
                                            </label>
                                            <input type="number" id="qte_ret_cameg_{{ $index }}"
                                                oninput="checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_ret_cameg"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.qte_ret_cameg') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Jours de rupture -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-calendar-x text-red-500"></i>
                                                Nombre de jours de rupture
                                            </label>
                                            <input type="number" id="nb_jour_rupture_{{ $index }}"
                                                oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.nb_jour_rupture"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.nb_jour_rupture') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Stock théorique fin -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-green-700 mb-3">
                                                <i class="bi bi-calculator text-emerald-500"></i>
                                                <span class="font-bold">Stock théorique en fin de trimestre</span>
                                            </label>
                                            <input type="number" readonly
                                                id="qte_stock_fin_trim_attendu_{{ $index }}"
                                                wire:model.live="consommations.{{ $index }}.qte_stock_fin_trim_attendu"
                                                class="w-full px-3 py-2 border-2 border-emerald-200 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 text-green-700 font-bold text-center text-[17px] shadow-inner" />
                                        </div>

                                        <!-- Stock réel fin -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-3">
                                                <i class="bi bi-box text-blue-500"></i>
                                                Stock réel en fin de trimestre
                                            </label>
                                            <input type="number" id="qte_stock_fin_trim_{{ $index }}"
                                                oninput="calculerStockSecurite({{ $index }}), checkInput({{ $index }})"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.qte_stock_fin_trim"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-600 text-blue-900 font-medium input-focus hover:border-teal-400 transition-all duration-300 @error('consommations.' . $index . '.qte_stock_fin_trim') !bg-red-100 !border-red-500 !border-2 @enderror"
                                                placeholder="Saisir..." min="0" step="1" />
                                        </div>

                                        <!-- Stock de sécurité -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-red-600 mb-3">
                                                <i class="bi bi-shield-check text-red-500"></i>
                                                <span class="font-bold">Stock de sécurité pour le trimestre à venir</span>
                                            </label>
                                            <input type="number" readonly id="stk_de_securite_{{ $index }}"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.stk_de_securite"
                                                class="w-full px-3 py-2 border-2 border-red-200 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 text-red-500 font-bold text-center text-[17px] shadow-inner" />
                                        </div>

                                        <!-- CCMA -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-red-600 mb-3">
                                                <i class="bi bi-graph-up text-red-500"></i>
                                                <span class="font-bold">Consommation Moyenne Mentuelle ajustée
                                                    (CMMa)
                                                </span>
                                            </label>
                                            <input type="number" readonly id="ccma_{{ $index }}"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.ccma"
                                                class="w-full px-3 py-2 border-2 border-red-200 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 text-red-500 font-bold text-center text-[17px] shadow-inner" />
                                        </div>

                                        <!-- Quantité commandée -->
                                        <div class="form-group">
                                            <label class="flex items-center gap-2 text-sm font-medium text-red-600 mb-3">
                                                <i class="bi bi-cart-plus text-red-500"></i>
                                                <span class="font-bold">Quantité commandée pour le trimestre à venir</span>
                                            </label>
                                            <input type="number" readonly id="cmd_trim_svt_{{ $index }}"
                                                wire:model.debounce.500ms="consommations.{{ $index }}.cmd_trim_svt"
                                                class="w-full px-3 py-2 border-2 border-red-200 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 text-red-500 font-bold text-center text-[17px] shadow-inner" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Boutons d'action -->
                        <div class="mt-4 flex justify-end gap-4 pt-4 border-t border-gray-200">
                            @if ($modifierConso)
                                <button type="submit" @disabled(empty($periode_choisie))
                                    class="group relative px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="relative z-10 flex items-center gap-2">
                                        <i class="bi bi-pencil-square"></i>
                                        Modifier
                                    </span>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                                    </div>
                                </button>
                            @else
                                <button type="submit" @disabled(empty($periode_choisie))
                                    class="group relative px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="relative z-10 flex items-center gap-2">
                                        <i class="bi bi-check-circle"></i>
                                        Enregistrer
                                    </span>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                                    </div>
                                </button>
                            @endif


                            <button type="reset"
                                class="group relative px-6 py-2 bg-gradient-to-r from-gray-400 to-gray-500 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="bi bi-x-circle"></i>
                                    Annuler
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-gray-500 to-gray-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                                </div>
                            </button>
                        </div>
                    </form>

                    <style>
                        .gradient-bg {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        }

                        .glass-effect {
                            backdrop-filter: blur(10px);
                            background: rgba(255, 255, 255, 0.95);
                            border: 1px solid rgba(255, 255, 255, 0.2);
                        }

                        .input-focus {
                            transition: all 0.3s ease;
                        }

                        .input-focus:focus {
                            transform: translateY(-2px);
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                        }

                        .card-hover {
                            transition: all 0.3s ease;
                        }

                        .card-hover:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                        }
                    </style>
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
                    `Les quantités saisies dépassent le stock disponible pour ce médicament. Les champs ont été réinitialisés.`
                );
            }
            if (qteEnStockFinTrim && qteEnStockFinTrim > reste) {
                alert(
                    'Info : Votre quantité restante réelle saisie est supérieur à la quantité restante théorique. Vérifiez si ce n\'est pas une erreur.'
                );
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
            if (search.trim() === '') {
                document.querySelectorAll('.hidden-card').forEach(card => {
                    card.classList.add('hidden');
                });
            }
        });
    </script>
    </div>
