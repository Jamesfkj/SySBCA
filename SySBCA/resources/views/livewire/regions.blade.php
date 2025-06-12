<div x-data="{ showCreateForm: false, showEditForm: false, editData: null }" class="flex flex-col gap-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-teal-600">
            <span x-show="!showCreateForm && !showEditForm" class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-globe"></i>
                </div>
                <p>Liste des Régions</p>
            </span>
            <span x-show="showCreateForm" class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-plus"></i>
                </div>
                <p>Ajouter une region</p>
            </span>
            <span x-show="showEditForm" class="flex items-center gap-2">
                <div class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-pen-fill"></i>
                </div>
                <p>Modifier une région</p>
            </span>
        </h2>

        <div x-show="!showCreateForm && !showEditForm">
            <input type="text" wire:model.debounce.300ms="search" name="search_region"
                placeholder="Rechercher une region..."
                class="w-96 rounded-full px-4 py-2 border focus:outline-none focus:ring-teal-500" />
        </div>

        <!-- Bouton Ajouter ou Voir la liste -->
        <button 
            @click="showCreateForm = false; showEditForm = false; editData = null"
            x-show="showCreateForm || showEditForm"
            x-cloak
            class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
            <span class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white/80 text-blue-500 shadow">
                    <i class="bi bi-eye"></i>
                </div>Voir la liste
            </span>
        </button>

        <button 
            @click="showCreateForm = true; showEditForm = false; editData = null"
            x-show="!showCreateForm && !showEditForm"
            class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
            <span class="flex items-center gap-2">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                    <i class="bi bi-plus"></i>
                </div>Ajouter une Région
            </span>
        </button>
    </div>

    <!-- Tableau -->
    <div x-show="!showCreateForm && !showEditForm" x-cloak x-transition class="bg-white shadow-lg rounded-lg">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 border-b">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="region in [{ id: 1, name: 'Région Maritime' }, { id: 2, name: 'Région des Plateaux' }]">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-blue-900" x-text="region.name"></td>
                        <td class="px-6 py-4 flex gap-4">
                            <!-- Modifier -->
                            <button @click="showEditForm = true; showCreateForm = false; editData = region"
                                class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md">
                                <i class="bi bi-pen-fill"></i>
                            </button>
                            <!-- Supprimer -->
                            <button type="submit"
                                class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Formulaire d’ajout -->
    <div x-show="showCreateForm" x-cloak x-transition>
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

    <!-- Formulaire de modification -->
    <div x-show="showEditForm" x-cloak x-transition>
        <form wire:submit.prevent="update" class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nom de la Région</label>
                <input type="text" id="edit_name" wire:model="editName"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                    required>
                @error('editName')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit"
                class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-200">
                Mettre à jour
            </button>
        </form>
    </div>
</div>
