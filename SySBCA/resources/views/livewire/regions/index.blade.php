<div class="min-h-screen flex flex-col justify-start p-3">
    <h2 class="text-2xl font-bold text-green-700 align-content-lg-start mb-4">Liste des régions</h2>
    <div class="w-full overflow-x-auto items-center ">
        <table class="w-full table-auto text-left border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Districts</th>
                    <th class="px-4 py-2 border">Formations Sanitaires</th>
                    <th class="px-4 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="px-4 py-2">Régions des Savanes</td>
                    <td class="px-4 py-2">Régions Maritime</td>
                    <td class="px-4 py-2">Régions des Plateaux</td>
                    <td class="px-4 py-2 flex items-center justify-center gap-3 text-green-700">
                        <button wire:click="edit()" class="hover:text-yellow-500" title="Modifier">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </button>
                        <button wire:click="delete()" class="hover:text-red-600" title="Supprimer">
                            <i class="bi bi-trash text-xl"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
