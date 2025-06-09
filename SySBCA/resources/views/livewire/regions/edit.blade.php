<div">
    <h2 class="text-xl font-semibold text-green-700 mb-4">Liste des régions</h2>

    <table class="min-w-full table-auto text-left border border-gray-200">
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
                <td class="px-4 py-2"></td>
                <td class="px-4 py-2"></td>
                <td class="px-4 py-2"></td>
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