<div x-data="{ showForm: false }" class="flex flex-col gap-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-teal-600">
            <span x-show="!showForm" x-cloak>Liste des Régions</span>
            <span x-show="showForm" x-cloak>Ajouter une Région</span>
        </h2>

        <!-- Bouton bascule -->
        <button @click="showForm = !showForm"
            class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
            <template x-if="!showForm">
                <span class="flex items-center gap-2">
                    <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                        <i class="bi bi-plus"></i>
                    </div>Ajouter une Région
                </span>
            </template>
            <template x-if="showForm">
                <span class="flex items-center gap-2">
                    <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                        <i class="bi bi-eye"></i>
                    </div>Voir la liste
                </span>
            </template>
        </button>
    </div>

    <!-- Tableau -->
    <div x-show="!showForm" x-cloak x-transition class="bg-white shadow-lg rounded-lg">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 border-b">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-blue-900">Nom de la Région</td>
                    <td class="px-6 py-4 flex gap-4">
                        <a href="#"
                            class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md">
                            <!-- Edit Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 4.232l4.536 4.536-10.536 10.536H4v-4.536L15.232 4.232z" />
                            </svg>
                        </a>
                        <button type="submit"
                            class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md">
                            <!-- Delete Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Formulaire -->
    <div x-show="showForm" x-cloak x-transition class="flex flex-col gap-6">
        <form wire:submit.prevent="save" class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la Région</label>
                <input type="text" id="name" wire:model="name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                    required>
                @error('name')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit"
                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                Enregistrer
            </button>
        </form>
    </div>
</div>
