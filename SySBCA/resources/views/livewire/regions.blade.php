<div>
    @if (session('message'))
        <div id="alert"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
            role="alert">
            <span>{{ session('message') }}</span>
            <button onclick="document.getElementById('alert').remove()" class="text-green-500">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif
    <div wire:loading wire:target="afficherFormulaire,afficherEdition,afficherTableau,create,updateRegion,delete"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20"></div>
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center relative">
            <h2 class="text-2xl font-semibold text-teal-600">
                @if (!$showCreateForm && !$showEditForm)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-globe"></i>
                        </div>
                        <p>Liste des Régions</p>
                    </span>
                @elseif ($showCreateForm)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-plus"></i>
                        </div>
                        <p>Ajouter une Région</p>
                    </span>
                @elseif ($showEditForm)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-pen-fill"></i>
                        </div>
                        <p>Modifier une Région</p>
                    </span>
                @endif
            </h2>

            @if (!$showCreateForm && !$showEditForm)
                <div>
                    <input type="text" wire:model.live="search" name="search_region"
                        placeholder="Rechercher une région..."
                        class="w-96 rounded-full px-4 py-2 border focus:outline-none focus:ring-teal-500" />
                </div>
            @endif

            @if ($showCreateForm || $showEditForm)
                <button wire:click="afficherTableau"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-white/80 text-blue-500 shadow">
                            <i class="bi bi-eye"></i>
                        </div>Voir la liste
                    </span>
                </button>
            @endif

            @if (!$showCreateForm && !$showEditForm)
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>Ajouter une Région
                    </span>
                </button>
            @endif
        </div>

        @if (!$showCreateForm && !$showEditForm)
            <div class="bg-white shadow-lg rounded-lg">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($regions as $region)
                            <tr wire:key="{{ $region->id }}" class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-blue-900">{{ $region->nom }}</td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button wire:click="afficherEdition({{ $region->id }})"
                                        class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md">
                                        <i class="bi bi-pen-fill"></i>
                                    </button>
                                    <button wire:click="delete({{ $region->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer cette région ?"
                                        class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">Aucune région trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if ($showCreateForm)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="create">
                    <div class="mb-4">
                        <label for="nom" class="block text-sm font-medium text-blue-900">Nom de la Région</label>
                        <input type="text" id="nom" wire:model.live="nom"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nom')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                        Enregistrer
                    </button>
                </form>
            </div>
        @endif

        @if ($showEditForm)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="updateRegion">
                    <div class="mb-4">
                        <label for="edit_region_name" class="block text-sm font-medium text-blue-900">Nom de la
                            Région</label>
                        <input type="text" id="edit_region_name" wire:model.live="editName"
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
        @endif
    </div>
</div>
