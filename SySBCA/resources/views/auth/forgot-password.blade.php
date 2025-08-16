<x-guest-layout>
    <div class="min-h-screen w-full flex items-center justify-center font-[Rubik]"
        style="background-image: url('{{ asset('images/bg.png') }}'); background-repeat: no-repeat; background-size: cover; background-position: center;">

        <div class="w-full max-w-xl p-6 bg-teal-900 bg-opacity-40 shadow-lg rounded-lg m-4 backdrop-filter backdrop-blur-lg">

            {{-- Alertes d'erreur générique (ex: compte inactif, etc.) --}}
            @if ($errors->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity role="alert"
                    class="text-red-600 bg-red-100 text-center text-sm mt-2 px-4 py-2 rounded-full shadow-sm">
                    {{ $errors->first('error') }}
                </div>
            @endif
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity
                    class="mx-auto w-[70%] text-green-700 bg-green-100 text-center text-sm mt-4 px-4 py-3 rounded-full shadow-sm"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <div class="flex justify-center mt-1">
                <img src="{{ asset('images/pnlp3.jpg') }}" alt="Logo" class="h-20 font-size-2xl">
            </div>

            <h1 class="text-2xl font-bold text-center text-white mb-2">Réinitialiser le mot de passe</h1>
            <p class="text-center text-gray-700 font-semibold mb-4">
                Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.
            </p>

            <form method="POST" action="{{ route('reset.password') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-teal-700 font-semibold mb-1">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        placeholder="Votre email"
                        class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-bold text-red-500 bg-white rounded-full text-center" />
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('login'))
                        <a class="underline text-sm text-gray-00 hover:text-teal-900"
                           href="{{ route('login') }}">
                            {{ __('Retour à la connexion') }}
                        </a>
                    @endif

                    <button type="submit"
                        class="bg-teal-600 text-white px-6 py-2 rounded-md hover:bg-teal-900 transition duration-300">
                        Envoyer le lien
                    </button>
                </div>
            </form>

            <div class="text-3xl font-semibold text-center text-white mt-12">PNLP Togo</div>
        </div>
    </div>
</x-guest-layout>
