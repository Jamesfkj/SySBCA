<div>
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
            role="alert">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div wire:loading wire:target="afficherFormulaire,afficherEdition,afficherTableau,create,updateDistrict,delete"
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
                            <i class="bi bi-map-fill"></i>
                        </div>
                        <p>Liste des districts</p>
                    </span>
                @elseif ($afficherFormulaireCreation)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-plus"></i>
                        </div>
                        <p>Ajouter un district</p>
                    </span>
                @elseif ($afficherFormulaireEdition)
                    <span class="flex items-center gap-2">
                        <div
                            class="bg-teal-100 w-9 aspect-square rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-pen-fill"></i>
                        </div>
                        <p>Modifier un district</p>
                    </span>
                @endif
            </h2>

            @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                <div>
                    <input type="text" wire:model.live="recherche" placeholder="Rechercher un district..."
                        class="w-96 rounded-full px-4 py-2 border focus:outline-none focus:ring-teal-500" />
                </div>
            @endif

            @if ($afficherFormulaireCreation || $afficherFormulaireEdition)
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

            @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>Ajouter un district
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
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Nom du district
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Région</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($districts as $district)
                            <tr wire:key="{{ $district->id }}" class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-blue-900">{{ $district->nom }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ $district->region->nom }}</td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button wire:click="afficherEdition({{ $district->id }})"
                                        class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md">
                                        <i class="bi bi-pen-fill"></i>
                                    </button>
                                    <button wire:click="delete({{ $district->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer cet district ?"
                                        class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucun district trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Formulaire d’ajout -->
        @if ($afficherFormulaireCreation)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="create">
                    <div class="mb-4">
                        <label for="nom" class="block text-sm font-medium text-blue-900">Nom du district</label>
                        <input type="text" id="nom" wire:model="nom"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nom')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="region_id" class="block text-sm font-medium text-blue-900">Région</label>
                        <select id="region_id" wire:model="region_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                            <option value="">-- Sélectionnez une région --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-gray-500 text-[15px] mb-4 italic">Créer l'utilisateur associé au district
                        (Optionelle, possibilié de la créer après à partir du menu utilisateur).</div>
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-blue-900">Nom d'utilisateur</label>
                        <input type="text" id="username" wire:model="username"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('username')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="mot_de_passe" class="block text-sm font-medium text-blue-900">Mot de passe</label>
                        <input type="password" id="mot_de_passe" wire:model="mot_de_passe"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('mot_de_passe')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="confirmation_mot_de_passe" class="block text-sm font-medium text-blue-900">Confirmer
                            le mot de passe</label>
                        <input type="password" id="confirmation_mot_de_passe" wire:model="confirmation_mot_de_passe"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900">
                        @error('confirmation_mot_de_passe')
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

        <!-- Formulaire de modification -->
        @if ($afficherFormulaireEdition)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="updateDistrict">
                    <div class="mb-4">
                        <label for="nomEdition" class="block text-sm font-medium text-blue-900">Nom du
                            district</label>
                        <input type="text" id="nomEdition" wire:model="nomEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                        @error('nomEdition')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="regionIdEdition" class="block text-sm font-medium text-blue-900">Région</label>
                        <select id="regionIdEdition" wire:model="regionIdEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 text-blue-900"
                            required>
                            <option value="">-- Sélectionnez une région --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->nom }}</option>
                            @endforeach
                        </select>
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
