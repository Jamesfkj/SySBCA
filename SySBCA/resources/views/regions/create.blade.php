<div">
    <h2 class="text-xl font-semibold text-green-700 mb-4">Enregistrer une nouvelle région</h2>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label for="nom" class="block text-gray-700 font-semibold">Nom de la région</label>
            <input type="text" id="nom" wire:model.defer="nom"
                class="w-full mt-1 border border-gray-300 rounded px-4 py-2 focus:ring-1 focus:ring-green-700 focus:outline-none">
            @error('nom') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
            Enregistrer
        </button>
    </form>
</div>
