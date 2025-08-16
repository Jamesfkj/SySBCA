<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Réinitialisation de votre mot de passe - PNLP</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        h1 {
            color: #0f766e;
            font-size: 24px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            margin: 30px 0 10px;
            color: #222;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .btn-wrapper {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            background-color: #0f766e;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 40px;
        }

        @media only screen and (max-width: 640px) {
            .email-container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            .btn {
                font-size: 15px;
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <img src="{{ $message->embed(public_path('images/pnlp3.jpg')) }}" alt="Logo PNLP" width="100"
            style="display:block; margin:auto;">
        <h1>Bonjour {{ $utilisateur->username }}</h1>

        <p>
            Nous avons reçu une demande de réinitialisation du mot de passe pour votre compte sur la plateforme de
            collecte
            des données du <strong>PNLP</strong>.
        </p>

        <h2>Réinitialisez votre mot de passe :</h2>

        <div class="btn-wrapper">
            <a href="{{ route('activation.compte', $token) }}" class="btn" target="_blank" rel="noopener noreferrer">
                Réinitialiser mon mot de passe
            </a>
        </div>

        <p>
            Si vous n’avez pas demandé cette réinitialisation, vous pouvez ignorer ce message.
        </p>

        <p>
            Bien cordialement,<br>
            <strong>L’équipe PNLP</strong>
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} PNLP - Tous droits réservés.
        </div>
    </div>
</body>

</html>
