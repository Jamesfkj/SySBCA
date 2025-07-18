<div>
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
            role="alert">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div wire:loading wire:target="afficherFormulaire,afficherEdition,afficherTableau,create,updateUtilisateur,delete"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>

    <div class="flex flex-col gap-6">
        <!-- EN-TÊTE -->
        <div class="flex justify-between items-center relative">
            <h2 class="text-2xl font-semibold text-teal-600">
                @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                    <span class="flex items-center gap-2">
                        <div class="bg-teal-100 w-9 h-9 rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <p>Liste des utilisateurs</p>
                    </span>
                @elseif ($afficherFormulaireCreation)
                    <span class="flex items-center gap-2">
                        <div class="bg-teal-100 w-9 h-9 rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-plus"></i>
                        </div>
                        <p>Ajouter un utilisateur</p>
                    </span>
                @elseif ($afficherFormulaireEdition)
                    <span class="flex items-center gap-2">
                        <div class="bg-teal-100 w-9 h-9 rounded-full flex items-center justify-center text-teal-600">
                            <i class="bi bi-pen-fill"></i>
                        </div>
                        <p>Modifier un utilisateur</p>
                    </span>
                @endif
            </h2>

            @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                <div>
                    <input type="text" wire:model.live="recherche" placeholder="Rechercher un utilisateur..."
                        class="w-96 rounded-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-teal-500 text-blue-900" />
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

            @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
                <button wire:click="afficherFormulaire"
                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white text-blue-600 shadow">
                            <i class="bi bi-plus"></i>
                        </div>Ajouter un utilisateur
                    </span>
                </button>
            @endif
        </div>

        <!-- TABLEAU -->
        @if (!$afficherFormulaireCreation && !$afficherFormulaireEdition)
            <div class="text-[15px] text text-gray-500 mb-2">
                Pour effectuer une recherche selon la date, entrez au format : aaaa-mm-jj
            </div>
            <div class="bg-white shadow-lg rounded-lg overflow-auto max-h-[650px]">
                <table class="min-w-full border-collapse">
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Nom d'utilisateur
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Niveau sanitaire</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Nom de zone</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Statut</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Créé le</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Modifié le</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-blue-900 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($utilisateurs as $utilisateur)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-blue-900">{{ $utilisateur->username }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ ucfirst($utilisateur->role->nom_role ?? 'N/A') }}</td>
                                <td class="px-6 py-4 text-blue-900">{{ ucfirst($utilisateur->entity->nom ?? 'N/A') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $utilisateur->etat === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($utilisateur->etat) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-blue-900">
                                    {{ $utilisateur->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-blue-900">
                                    {{ $utilisateur->updated_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 flex gap-4">
                                    @if ($utilisateur->etat === 'suspendu')
                                        <button wire:click="reactiver({{ $utilisateur->id }})"
                                            class="text-green-600 hover:text-green-700 flex items-center justify-center w-9 h-9 rounded-full bg-green-100 shadow-md"
                                            title="Réactiver cet utilisateur">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                        <button wire:click="delete({{ $utilisateur->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?"
                                            class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md"
                                            title="Supprimer cet utilisateur">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    @else
                                        <button wire:click="afficherEdition({{ $utilisateur->id }})"
                                            class="text-blue-600 hover:text-blue-700 flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shadow-md"
                                            title="Modifier cet utilisateur">
                                            <i class="bi bi-pen-fill"></i>
                                        </button>
                                        <button wire:click="suspendre({{ $utilisateur->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir suspendre cet utilisateur ?"
                                            class="text-red-600 hover:text-red-700 flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shadow-md"
                                            title="Suspendre cet utilisateur">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
       <!-- FORMULAIRE DE CRÉATION -->
        @if ($afficherFormulaireCreation)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="create">
                    <!-- Nom d'utilisateur -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nom d'utilisateur</label>
                        <input type="text" wire:model="username"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                        @error('username')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Sélection du rôle -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Rôle</label>
                        <select wire:model="role_id" wire:model.live='role_choisi'
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                            <option value="">-- Sélectionnez un rôle --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nom_role }}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $role_choisi = $roles->firstWhere('id', $role_id);
                    @endphp

                    @if ($role_choisi && in_array($role_choisi->nom_role, ['District', 'Formation sanitaire']))
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-blue-900">{{ $role_choisi->nom_role }}</label>
                            <select wire:model.live='zone_sanitaire'
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900 overflow-auto">
                                <option>-- Sélectionnez {{ $role_choisi->nom_role }} --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->nom }}</option>
                                @endforeach
                            </select>
                            @error('zone_sanitaire')
                                <span class="text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    <!-- Mot de passe -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Mot de passe</label>
                        <input type="password" wire:model="mot_de_passe"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                        @error('mot_de_passe')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Confirmer le mot de passe</label>
                        <input type="password" wire:model="confirmation_mot_de_passe"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                        @error('confirmation_mot_de_passe')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full mt-4 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                        Enregistrer
                    </button>
                </form>
            </div>
        @endif

        @if ($afficherFormulaireEdition)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <form wire:submit.prevent="updateUtilisateur">
                    <!-- Nom d'utilisateur -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Nom d'utilisateur</label>
                        <input type="text" wire:model="usernameEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                        @error('usernameEdition')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Sélection du rôle -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-900">Rôle</label>
                        <select wire:model.live="roleIdEdition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                            <option value="">-- Sélectionnez un rôle --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nom_role }}</option>
                            @endforeach
                        </select>
                        @error('roleIdEdition')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    @php
                        $role_choisiEdition = $roles->firstWhere('id', $roleIdEdition);
                    @endphp

                    @if ($role_choisiEdition && in_array($role_choisiEdition->nom_role, ['District', 'Formation sanitaire']))
                        @php
                            if ($role_choisiEdition->nom_role === 'District') {
                                $zones = \App\Models\District::all();
                            } elseif ($role_choisiEdition->nom_role === 'Formation sanitaire') {
                                $zones = \App\Models\FormationSanitaire::all();
                            } else {
                                $zones = collect();
                            }
                        @endphp
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-blue-900">{{ $role_choisiEdition->nom_role }}</label>
                            <select wire:model="zone_sanitaireEdition"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                                <option>-- Sélectionnez {{ $role_choisiEdition->nom_role }} --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->nom }}</option>
                                @endforeach
                            </select>
                            @error('zone_sanitaireEdition')
                                <span class="text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button type="submit"
                        class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-200">
                        Mettre à jour
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>