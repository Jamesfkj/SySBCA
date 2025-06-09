<x-guest-layout>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-xl w-full p-8 bg-white shadow-lg rounded-lg">
            <h1 class="text-2xl font-bold text-center text-green-700 mb-2">Connexion</h1>
            <p class="text-center text-gray-500 font-semibold mb-4">
                Entrez vos identifiants pour vous connecter !
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form class="space-y-5" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-green-700 font-semibold mb-1">Email</label>
                    <input type="email" id="email" name="email" :value="old('email')" required autofocus
                        placeholder="Email"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-green-700 focus:ring-1 focus:ring-green-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-green-700 font-semibold mb-1">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="Mot de passe"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-green-700 focus:ring-1 focus:ring-green-700">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-700">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">
                        {{ __('Se souvenir de moi') }}
                    </label>
                </div>

                <!-- Login Button and Forgot Password -->
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-green-700"
                            href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif

                    <button type="submit"
                        class="bg-green-700 text-white px-6 py-2 rounded-md hover:bg-green-700 transition duration-300">
                        Se connecter
                    </button>
                </div>
            </form>

            <div class="text-3xl font-semibold text-center text-green-700 mt-12">PNLP Togo</div>
        </div>
    </div>
</x-guest-layout>