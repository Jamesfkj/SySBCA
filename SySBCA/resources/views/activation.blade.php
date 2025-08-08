<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Définir un mot de passe - PNLP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css']) {{-- Ou lien CDN Tailwind --}}
</head>

<body class="min-h-screen w-full flex items-center justify-center font-[Rubik]"
    style="background-image: url('{{ asset('images/bg.png') }}'); background-repeat: no-repeat; background-size: cover; background-position: center;">

    <div class="w-full max-w-xl p-6 bg-teal-900 bg-opacity-40 shadow-lg rounded-lg m-4 backdrop-filter backdrop-blur-lg">

        <div class="flex justify-center mt-1">
            <img src="{{ asset('images/pnlp3.jpg') }}" alt="Logo" class="h-20">
        </div>

        <h1 class="text-2xl font-bold text-center text-white">Activation du compte</h1>

        @if (!empty($user))
            <div class="text-white text-center">
                <p class="text-center text-gray-700 font-semibold">Bonjour <strong class="text-[18px] text-gray-800">{{ $user->username }}</strong>,
                veuillez définir un mot de passe pour activer votre compte !</p>
            </div>

            <form method="POST" action="{{ route('definir.password', $user->id) }}" class="space-y-5 mt-6">
                @csrf
                <div>
                    <label for="password" class="block text-teal-700 font-semibold mb-1">Mot de passe</label>
                    <input type="password" name="password" id="password" required placeholder="Mot de passe ..."
                        class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-teal-700 font-semibold mb-1">Confirmer le mot
                        de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Confirmer le mot de passe ..."
                        class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
                </div>

                <div class="pt-2 text-center">
                    <button type="submit"
                        class="bg-teal-600 text-white px-6 py-2 rounded-md hover:bg-teal-900 transition duration-300">
                        Définir mon mot de passe
                    </button>
                </div>

            </form>
        @else
            <div class="text-center text-red-200 font-semibold mt-6">
                ❌ Le lien d'activation est invalide ou a expiré.
            </div>
        @endif

        <div class="text-3xl font-semibold text-center text-white mt-12">PNLP Togo</div>
    </div>
</body>

</html>
