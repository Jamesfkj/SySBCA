<div x-data="{ showCreateForm: false, showEditForm: false }" class="flex flex-col gap-6">

    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-teal-600">
            <span x-show="!showCreateForm" class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-box-fill"></i>
                </div>
                <p>Consommations des produits</p>
            </span>
            <span x-show="showCreateForm" class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <p>Ajouter une consommation</p>
            </span>
            <div class="text-[20px] font-semibold text-gray-500 mt-2">
                <div>Période : </div>
                <div>Zone sanitaire : </div>
            </div>
        </h2>

        <div x-show="!showCreateForm">
            <form wire:submit.prevent="filtrerParPeriode" class="flex items-center">
                <!-- Sélection de la période -->
                <select wire:model="periode" name="search_consumption"
                    class="w-80 rounded-l-full px-4 py-2 border border-gray-300 text-blue-900 focus:outline-none focus:ring-teal-500">
                    <option value="">-- Choisissez une période --</option>
                    <option value="2025-T1">1er Trimestre 2025</option>
                    <option value="2025-T2">2e Trimestre 2025</option>
                    <option value="2025-T3">3e Trimestre 2025</option>
                    <option value="2025-T4">4e Trimestre 2025</option>
                </select>

                <!-- Bouton de recherche -->
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-r-full hover:bg-teal-700 transition">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>




        <!-- Boutons -->
        <div class="flex gap-2">
            <!-- Voir la liste -->
            <button @click="showCreateForm = false" x-show="showCreateForm"
                class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow hover:bg-blue-700 transition">
                <i class="bi bi-eye-fill"></i> Voir la liste
            </button>

            <!-- Ajouter -->
            <button @click="showCreateForm = true" x-show="!showCreateForm"
                class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow hover:bg-blue-700 transition">
                <i class="bi bi-plus-circle-fill"></i> Nouvelle consommation
            </button>
        </div>
    </div>

    <div x-show="!showCreateForm " x-cloak x-transition class="">
        <table class="min-w-full table-auto border border-gray-300 text-sm text-blue-900">
            <thead class="bg-gray-100 text-[12px] bg-blue-100 text-blue-900 text-justify">
                <tr class="bg-blue-100 text-center">
                    <th class="border border-gray-300 p-1 bg-white text-blue-900">Désignation du produit</th>
                    <th class="border border-gray-300 p-1">Stock disponible en début de trimestre</th>
                    <th class="border border-gray-300 p-1">Quantité reçue dans le trimestre</th>
                    <th class="border border-gray-300 text-red-500 p-1">Quantité en Stock</th>
                    <th class="border border-gray-300 p-1">Quantité utilisé</th>
                    <th class="border border-gray-300 p-1">Nombre de bénéficiaire</th>
                    <th class="border border-gray-300 p-1">Périmé</th>
                    <th class="border border-gray-300 p-1">Pertes et avariées</th>
                    <th class="border border-gray-300 p-1">Quantitée retournée à la CAMEG</th>
                    <th class="border border-gray-300 p-1">Nombre de jour de rupture</th>
                    <th class="border border-gray-300 p-1 text-red-500">Quantité restante en Stock en fin du trimestre
                    </th>
                    <th class="border border-gray-300 p-1 text-red-500">Stock de sécurité pour le trimestre à venir</th>
                    <th class="border border-gray-300 p-1 text-red-500">CMM ajustéé (CMMa)</th>
                    <th class="border border-gray-300 p-1 text-red-500">Qantitée commandée pour le trimestre à venir
                    </th>
                    <th class="border border-gray-300 p-1 text-red-500">Quantitée accordée par le PF district</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-300 p-1 text-center">Artéméther-Luméfantrine 6cp (AL)</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1">0</td>
                    <td class="border border-gray-300 p-1"></td>
                </tr>
            </tbody>
        </table>

    </div>

    <!-- Formulaire de saisie croisée -->
    <div x-show="showCreateForm" x-cloak x-transition>
        <form class="p-4">
            <table class="min-w-full border text-sm text-blue-900">
                <thead class="bg-blue-100 text-[12px] text-center">
                    <tr>
                        <th class="border p-2">Produit</th>
                        <th class="border p-2">Début Trim.</th>
                        <th class="border p-2">Réception</th>
                        <th class="border p-2 text-red-500">En Stock</th>
                        <th class="border p-2">Utilisé</th>
                        <th class="border p-2">Bénéficiaires</th>
                        <th class="border p-2">Périmé</th>
                        <th class="border p-2">Pertes</th>
                        <th class="border p-2">Retournée</th>
                        <th class="border p-2">Rupture</th>
                        <th class="border p-2 text-red-500">Fin Trim.</th>
                        <th class="border p-2 text-red-500">Stock sécurité</th>
                        <th class="border p-2 text-red-500">CMMa</th>
                        <th class="border p-2 text-red-500">Commandée</th>
                        <th class="border p-2 text-red-500">Accordée</th>
                    </tr>
                </thead>
                <tbody class="text-center text-xs">
                    <template
                        x-for="product in [
                        'Artéméther-Luméfantrine 6cp',
                        'Artéméther-Luméfantrine 12cp',
                        'Paracétamol 500mg',
                        'ACT 24cp'
                    ]"
                        :key="product">
                        <tr class="bg-blue-50">
                            <td class="border p-1 text-left" x-text="product"></td>

                            <!-- Champs à remplir -->
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>

                            <!-- Calculé -->
                            <td class="border p-1 bg-gray-100 text-gray-500">--</td>

                            <!-- Champs à remplir -->
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>
                            <td class="border p-1"><input type="number" class="w-full border-gray-300 rounded-md" />
                            </td>

                            <!-- Calculé -->
                            <td class="border p-1 bg-gray-100 text-gray-500">--</td>
                            <td class="border p-1 bg-gray-100 text-gray-500">--</td>
                            <td class="border p-1 bg-gray-100 text-gray-500">--</td>
                            <td class="border p-1 bg-gray-100 text-gray-500">--</td>
                            <td class="border p-1 bg-gray-100 text-gray-500">--</td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- Boutons -->
            <div class="mt-4 flex justify-end gap-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Enregistrer
                </button>
                <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
