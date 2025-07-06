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

    <div wire:loading wire:target="afficherFormulaire,afficherEdition,afficherTableau,create,updateMedicament,delete"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20"></div>

    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center relative">
            <h2 class="text-2xl font-semibold text-teal-600">
                @if (!$showCreateForm && !$showEditForm)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-capsule"></i>
                        </div>
                        <p>Liste des Médicaments</p>
                    </span>
                @elseif ($showCreateForm)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-plus"></i>
                        </div>
                        <p>Ajouter un Médicament</p>
                    </span>
                @elseif ($showEditForm)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-pen-fill"></i>
                        </div>
                        <p>Modifier un Médicament</p>
                    </span>
                @endif
            </h2>

            @if (!$showCreateForm && !$showEditForm)
                <div>
                    <input type="text" wire:model.live="search" name="search_medicament"
                        placeholder="Rechercher un médicament..."
                        class="w-96 rounded-full px-4 py-2 border focus:outline-none focus:ring-teal-500" />
                </div>
            @endif

            @if ($showCreateForm || $showEditForm)
                <button wire:click="afficherTableau"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white/80 text-blue-500 shadow">
                            <i class="bi bi-eye"></i>
                        </div>Voir la liste
                    </span>
                </button>
            @endif

            @if (!$showCreateForm && !$showEditForm)
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>Ajouter un Médicament
                    </span>
                </button>
            @endif
        </div>

        {{-- Tableau des médicaments --}}
        @if (!$showCreateForm && !$showEditForm)
            <div class="bg-white shadow-lg rounded-lg overflow-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Code</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Composition</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Réservé FS</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicaments as $medicament)
                            <tr wire:key="{{ $medicament->id }}" class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-blue-900">{{ $medicament->nom }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ $medicament->code }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ $medicament->composition ?? '-' }}</td>
                                <td class="px-6 py-4 text-blue-900 text-center">
                                    @if($medicament->fs_only)
                                        <i class="bi bi-check-lg text-green-600"></i>
                                    @else
                                        <i class="bi bi-x-lg text-red-600"></i>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button wire:click="afficherEdition({{ $medicament->id }})"
                                        class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md"
                                        title="Modifier">
                                        <i class="bi bi-pen-fill"></i>
                                    </button>
                                    <button wire:click="delete({{ $medicament->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce médicament ?"
                                        class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md"
                                        title="Supprimer">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucun médicament trouvé.</td>
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
                        <label for="nom" class="block text-sm font-medium text-blue-900">Nom du Médicament</label>
                        <input type="text" id="nom" wire:model.live="nom"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nom') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-blue-900">Code</label>
                        <input type="text" id="code" wire:model.live="code"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('code') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="composition" class="block text-sm font-medium text-blue-900">Composition</label>
                        <input type="text" id="composition" wire:model.live="composition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('composition') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="fs_only" class="block text-sm font-medium text-blue-900">Uniquement pour Formations
                            Sanitaires</label>
                        <select id="fs_only" wire:model.live="fs_only"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        @error('fs_only') <span class="text-red-600">{{ $message }}</span> @enderror
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
                <form wire:submit.prevent="updateMedicament">
                    <div class="mb-4">
                        <label for="edit_nom" class="block text-sm font-medium text-blue-900">Nom du Médicament</label>
                        <input type="text" id="edit_nom" wire:model.live="editNom"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('editNom') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="edit_code" class="block text-sm font-medium text-blue-900">Code</label>
                        <input type="text" id="edit_code" wire:model.live="editCode"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('editCode') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="edit_composition" class="block text-sm font-medium text-blue-900">Composition</label>
                        <input type="text" id="edit_composition" wire:model.live="editComposition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('editComposition') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="edit_fs_only" class="block text-sm font-medium text-blue-900">Uniquement pour Formations
                            Sanitaires</label>
                        <select id="edit_fs_only" wire:model.live="editFsOnly"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                        @error('editFsOnly') <span class="text-red-600">{{ $message }}</span> @enderror
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