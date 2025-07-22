<div>
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
            role="alert">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div wire:loading wire:target="afficherFormulaire,afficherEdition,afficherTableau,create,update,delete"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>

    <div class="flex flex-col gap-6">
        <!-- En-tête -->
        <div class="flex justify-between items-center relative">
            <h2 class="text-2xl font-semibold text-teal-600">
                @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-hospital-fill"></i>
                        </div>
                        <p>Liste des formations sanitaires</p>
                    </span>
                @elseif ($afficherFormulaireCreation)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-plus"></i>
                        </div>
                        <p>Ajouter une formation sanitaire</p>
                    </span>
                @elseif ($afficherFormulaireEdition)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-pen-fill"></i>
                        </div>
                        <p>Modifier une formation sanitaire</p>
                    </span>
                @endif
            </h2>

            @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                <div>
                    <input type="text" wire:model.live="recherche" placeholder="Rechercher une formation..."
                        class="w-96 rounded-full px-4 py-2 border focus:outline-none focus:ring-teal-500" />
                </div>
            @endif

            @if ($afficherFormulaireCreation || $afficherFormulaireEdition)
                <button wire:click="afficherTableau"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white/80 text-blue-500 shadow">
                            <i class="bi bi-eye"></i>
                        </div>Voir la liste
                    </span>
                </button>
            @endif

            @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition && auth()->check() && auth()->user()->role->nom_role == 'Administrateur')
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>Ajouter une formation
                    </span>
                </button>
            @endif
        </div>

        <!-- Tableau -->
        @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
            <div class="bg-white shadow-lg rounded-lg">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Formation sanitaire
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Nombre d'ASC</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">District</th>
                            @if (auth()->check() && auth()->user()->role->nom_role == 'Administrateur')
                                <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($formations as $fs)
                            <tr wire:key="{{ $fs->id }}" class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-blue-900">{{ $fs->nom }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ $fs->nb_asc }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ $fs->district->nom }}</td>
                                @if (auth()->check() && auth()->user()->role->nom_role == 'Administrateur')
                                    <td class="px-6 py-4 flex gap-4">
                                        <button wire:click="afficherEdition({{ $fs->id }})"
                                            class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md">
                                            <i class="bi bi-pen-fill"></i>
                                        </button>
                                        <button wire:click="delete({{ $fs->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette formation sanitaire ?"
                                            class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucune formation trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Formulaire de création -->
        @if ($afficherFormulaireCreation)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="create">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nom de la formation sanitaire</label>
                        <input type="text" wire:model="nom"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nom') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nombre d'ASC</label>
                        <input type="number" min="0" wire:model="nb_asc"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nb_asc') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">District</label>
                        <select wire:model="district_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                            <option value="">-- Sélectionnez un district --</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-gray-500 text-[15px] mb-4 italic">Créer l'utilisateur associé à la Formation sanitaire
                        (Optionnel, peut être crée à partir du menu utilisateur).</div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nom d'utilisateur</label>
                        <input type="text" wire:model="username"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('username') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Mot de passe</label>
                        <input type="password" wire:model="mot_de_passe"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('mot_de_passe') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Confirmer le mot de passe</label>
                        <input type="password" wire:model="confirmation_mot_de_passe"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('confirmation_mot_de_passe') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">Enregistrer</button>
                </form>
            </div>
        @endif

        <!-- Formulaire d’édition -->
        @if ($afficherFormulaireEdition)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="update">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nom de la formation sanitaire</label>
                        <input type="text" wire:model="nomEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nomEdition') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nombre d'ASC</label>
                        <input type="number" min="0" wire:model="nb_ascEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nb_ascEdition') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">District</label>
                        <select wire:model="districtIdEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                            <option value="">-- Sélectionnez un district --</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->nom }}</option>
                            @endforeach
                        </select>
                        @error('districtIdEdition') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-200">Mettre
                        à jour</button>
                </form>
            </div>
        @endif
    </div>
</div>