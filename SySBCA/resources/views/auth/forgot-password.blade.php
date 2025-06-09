<x-guest-layout>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-xl w-full p-8 bg-white shadow-lg rounded-lg">
            <h1 class="text-2xl font-bold text-center text-green-700 mb-2">Réinitialiser le mot de passe</h1>
            <p class="text-center text-gray-500 font-semibold mb-4">
                Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center text-green-600" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-green-700 font-semibold mb-1">Email</label>
                    <input type="email" id="email" name="email" :value="old('email')" required autofocus
                        placeholder="Votre email"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-green-700 focus:ring-1 focus:ring-green-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <button type="submit"
                        class="bg-green-700 text-white px-6 py-2 rounded-md hover:bg-green-800 transition duration-300">
                        Envoyer un lien
                    </button>
                </div>
            </form>

            <div class="text-3xl font-semibold text-center text-green-700 mt-12">PNLP Togo</div>
        </div>
    </div>
</x-guest-layout>
