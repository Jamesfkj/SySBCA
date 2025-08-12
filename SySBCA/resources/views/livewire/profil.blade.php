<div>
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
            role="alert">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div wire:loading wire:target="mettreAJourProfil,changerMotDePasse"
        class="absolute top-0 left-0 w-full h-1 bg-teal-600 animate-progress-bar z-20">
    </div>

    <div class="flex flex-col gap-6">
        <!-- EN-TÊTE -->
        <div class="flex justify-between items-center relative">
            <h2 class="text-2xl font-semibold text-teal-600">
                <span class="flex items-center gap-2">
                    <div class="bg-teal-100 w-9 h-9 rounded-full flex items-center justify-center text-teal-600">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <p>Mon Profil</p>
                </span>
            </h2>
        </div>

        <!-- INFORMATIONS PERSONNELLES -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center text-teal-600">
                    <i class="bi bi-person-circle text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-blue-900">{{ $utilisateur->username }}</h3>
                    <p class="text-gray-600">{{ $utilisateur->email }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @php
                            $classe = match ($utilisateur->etat) {
                                'actif' => 'bg-green-100 text-green-800',
                                'inactif' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-red-100 text-red-800',
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $classe }}">
                            {{ ucfirst($utilisateur->etat) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations générales -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-blue-900 border-b pb-2">Informations générales</h4>
                    
                    <div class="flex items-center gap-3">
                        <div class="bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-envelope text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-blue-900 font-medium">{{ $utilisateur->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-person text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nom d'utilisateur</p>
                            <p class="text-blue-900 font-medium">{{ $utilisateur->username }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-shield text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rôle</p>
                            <p class="text-blue-900 font-medium">{{ ucfirst($utilisateur->role->nom_role ?? 'N/A') }}</p>
                        </div>
                    </div>

                    @if($utilisateur->entity)
                        <div class="flex items-center gap-3">
                            <div class="bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="bi bi-geo-alt text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Zone assignée</p>
                                <p class="text-blue-900 font-medium">{{ $utilisateur->entity->nom ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Informations système -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-blue-900 border-b pb-2">Informations système</h4>
                    
                    <div class="flex items-center gap-3">
                        <div class="bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-calendar-plus text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Créé le</p>
                            <p class="text-blue-900 font-medium">{{ $utilisateur->created_at?->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-clock-history text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dernière modification</p>
                            <p class="text-blue-900 font-medium">{{ $utilisateur->updated_at?->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODIFICATION DU PROFIL -->
        @if($modeEdition)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-blue-100 w-8 h-8 rounded-full flex items-center justify-center text-blue-600">
                        <i class="bi bi-pen-fill"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-900">Modifier mes informations</h4>
                </div>
                
                <form wire:submit.prevent="mettreAJourProfil">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-blue-900">Nom d'utilisateur</label>
                            <input type="text" wire:model="usernameEdition"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                            @error('usernameEdition')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit"
                            class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                            <i class="bi bi-check-lg mr-2"></i>Enregistrer
                        </button>
                        <button type="button" wire:click="annulerEdition"
                            class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="bi bi-x-lg mr-2"></i>Annuler
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- CHANGEMENT DE MOT DE PASSE -->
        @if($modeChangementMdp)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-yellow-100 w-8 h-8 rounded-full flex items-center justify-center text-yellow-600">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-900">Changer mon mot de passe</h4>
                </div>
                
                <form wire:submit.prevent="changerMotDePasse">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-blue-900">Mot de passe actuel</label>
                            <input type="password" wire:model="motDePasseActuel"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                            @error('motDePasseActuel')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-blue-900">Nouveau mot de passe</label>
                            <input type="password" wire:model="nouveauMotDePasse"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                            @error('nouveauMotDePasse')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-blue-900">Confirmer le nouveau mot de passe</label>
                            <input type="password" wire:model="confirmerMotDePasse"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-blue-900">
                            @error('confirmerMotDePasse')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit"
                            class="bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-200">
                            <i class="bi bi-key mr-2"></i>Changer le mot de passe
                        </button>
                        <button type="button" wire:click="annulerChangementMdp"
                            class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="bi bi-x-lg mr-2"></i>Annuler
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- BOUTONS D'ACTION -->
        @if(!$modeEdition && !$modeChangementMdp)
            <div class="flex flex-wrap gap-3">
                <button wire:click="activerModeEdition"
                    class="flex items-center gap-2 p-3 rounded-lg bg-blue-500 text-white shadow-md hover:bg-blue-700 transition">
                    <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white/20">
                        <i class="bi bi-pen-fill"></i>
                    </div>
                    Modifier mes informations
                </button>

                <button wire:click="activerModeChangementMdp"
                    class="flex items-center gap-2 p-3 rounded-lg bg-yellow-500 text-white shadow-md hover:bg-yellow-700 transition">
                    <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white/20">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    Changer mon mot de passe
                </button>
            </div>
        @endif

    </div>
</div>