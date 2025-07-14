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
    <div wire:loading wire:target="afficherFormulaire,afficherTableau,filtrerParPériode,ajouterConsommation"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>

    <!-- En-tête -->
    <div class="flex justify-between items-center relative mb-6">
        <h2 class="text-2xl font-semibold text-teal-600">
            @if ($tableauVisible)
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-box-fill"></i>
                    </div>
                    <p>Consommations des produits</p>
                </span>
            @elseif ($formulaireVisible)
                <span class="flex items-center gap-2">
                    <div
                        class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-plus"></i>
                    </div>
                    <p>Ajouter une consommation</p>
                </span>
            @endif
        </h2>

        <!-- Filtres et boutons -->
        <div class="flex items-center gap-4">
            @if ($tableauVisible)
                <!-- Filtre par période -->
                <form wire:submit.prevent="filtrerParPériode" class="flex items-center">
                    <select wire:model="periode"
                        class="w-80 rounded-l-full px-4 py-2 border border-gray-300 text-blue-900 focus:outline-none focus:ring-teal-500">
                        <option value="">-- Choisissez une période --</option>
                        <option value="2025-T1">1er Trimestre 2025</option>
                        <option value="2025-T2">2e Trimestre 2025</option>
                        <option value="2025-T3">3e Trimestre 2025</option>
                        <option value="2025-T4">4e Trimestre 2025</option>
                    </select>
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-r-full hover:bg-teal-700 transition">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            @endif
        </div>
        <div>
            <!-- Boutons de navigation -->
            @if ($tableauVisible)
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>Nouvelle consommation
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
                        </div>Liste des consommations
                    </span>
                </button>
            @endif
        </div>
    </div>
    <div class="bg-white">
        @if ($tableauVisible)
            <table class="min-w-full table-auto border border-gray-300 text-sm text-blue-900">
                <thead class="bg-gray-100 text-[14px] bg-blue-100 text-blue-900 text-justify">
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
                        <th class="border border-gray-300 bg-white text-red-600 p-1">Quantité restante en Stock en
                            fin du
                            trimestre</th>
                        <th class="border border-gray-300 bg-white text-red-600 p-1">Stock de sécurité pour le
                            trimestre à
                            venir
                        </th>
                        <th class="border border-gray-300 bg-white text-red-600 p-1">CMM ajustéé (CMMa)</th>
                        <th class="border border-gray-300 bg-white text-red-600 p-1">Qantitée commandée pour le
                            trimestre à
                            venir
                        </th>
                        <th class="border border-gray-300 bg-white text-red-600 p-1">Quantitée accordée par le PF
                            district
                        </th>
                    </tr>
                </thead>
                <tbody class="text-[16px]">
                    @forelse ($medicaments as $medicament)
                        <tr class="odd:bg-white even:bg-blue-100">
                            <td class="border p-1 text-[14px] font-semibold">
                                {{ $medicament->nom }}
                            </td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center text-gray-500">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center ">0</td>
                            <td class="border p-1 text-center text-gray-500">0</td>
                            <td class="border p-1 text-center text-gray-500">0</td>
                            <td class="border p-1 text-center text-gray-500">0</td>
                            <td class="border p-1 text-center text-gray-500">0</td>
                            <td class="border p-1 text-center text-gray-500">0</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="border p-4 text-center text-gray-500">
                                Aucune donnée disponible pour la période sélectionnée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
@elseif ($formulaireVisible)
    <div class="overflow-x-auto">
        <div class="gap-2 items-center p-2 bg-gray-50 border-b border-gray-200">
            <p class="text-sm text-gray-700">
                Les colonnes en <span class="text-red-600 font-semibold">rouge</span> sont calculées automatiquement à
                partir de vos saisies.
            </p>
            <p class="text-sm text-gray-700">
                Veuillez remplir les colonnes contenant l’indication <em>« Saisir... »</em>.
            </p>
        </div>
        <div class="flex gap-2 items-center p-2 bg-gray-50 border-b border-gray-200">
            <p class="text-blue-900">
                Veuillez choisir entre FS et ASC :
            </p>
            <div>
                <select name="" id=""
                    class="w-48 rounded-full px-3 py-2 border border-gray-200 text-blue-900 focus:outline-none focus:ring-teal-700">
                    <option value="">-- Sélectionner --</option>
                    <option value="fs">FS</option>
                    <option value="asc">ASC</option>
                </select>
            </div>
        </div>
        <div class="bg-white">
            <form wire:submit.prevent="ajouterConsommation">
                <table class="min-w-full table-auto text-sm text-blue-900">
                    <thead class="bg-gray-100 text-[14px] bg-blue-100 text-blue-900 text-justify">
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
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Quantité restante en Stock
                                en fin du
                                trimestre</th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Stock de sécurité pour le
                                trimestre à
                                venir
                            </th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">CMM ajustéé (CMMa)</th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Qantitée commandée pour le
                                trimestre à
                                venir
                            </th>
                            <th class="border border-gray-300 bg-white text-red-600 p-1">Quantitée accordée par le
                                PF district
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicaments as $medicament)
                            <tr class="odd:bg-white even:bg-blue-50 transition-colors">
                                <!-- Nom du médicament -->
                                <td class="border border-gray-200 p-2 font-medium text-blue-900">
                                    {{ $medicament->nom }}
                                </td>
                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>
                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>
                                <!-- Champ inactif -->
                                <td class="border border-gray-200 p-2 text-center text-sm text-gray-400 bg-gray-100">
                                    —</td>
                                <!-- Autres champs éditables -->
                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>
                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>

                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>

                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>

                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />

                                <td class="p-0 m-0 border border-gray-200">
                                    <input type="number" wire:model.live=""
                                        class="w-full h-full border-0 bg-transparent text-sm text-blue-900
               focus:outline-none focus:ring-0 focus:border-b-2 focus:border-teal-600"
                                        placeholder="Saisir..." min="0" />
                                </td>

                                <!-- Champs non éditables (traits) -->
                                <td class="border border-gray-200 p-2 text-center text-sm text-gray-400 bg-gray-100">
                                    —</td>
                                <td class="border border-gray-200 p-2 text-center text-sm text-gray-400 bg-gray-100">
                                    —</td>
                                <td class="border border-gray-200 p-2 text-center text-sm text-gray-400 bg-gray-100">
                                    —</td>
                                <td class="border border-gray-200 p-2 text-center text-sm text-gray-400 bg-gray-100">
                                    —</td>
                                <td class="border border-gray-200 p-2 text-center text-sm text-gray-400 bg-gray-100">
                                    —</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

                <!-- Boutons -->
                <div class="mt-4 flex justify-end gap-2">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Enregistrer
                    </button>
                    <button type="button" wire:click="afficherListe"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
