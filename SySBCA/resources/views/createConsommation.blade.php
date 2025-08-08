<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Activation de votre compte - PNLP</title>
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
        <h1>Bonjour {{ $utilisateur->username }} !</h1>
        <p>
            Vous avez crée une nouvelle consommation sur la plateforme pour la formation sanitaire
            <strong>{{ $conso->fs->nom }}</strong>.
        </p>
        <p>
            Détails de la consommation :
        </p>
        <ul style="font-size:15px; line-height:1.6; margin-bottom:15px; padding-left:20px;">
            <li><strong>Acteur concerné :</strong> {{ $conso->acteur }}</li>
            <li><strong>Période :</strong> {{ $conso->periode->nom }}</li>
            <li><strong>Date de création :</strong> {{ $conso->created_at->format('d/m/y H:i') }}</li>
        </ul>
        <p>
            Nous vous invitons à vous connecter à la plateforme pour consulter ou et soumettre votre consommation au niveau district pour la validation.
        </p>
        <p>
            Cette notification est générée automatiquement dans le cadre du suivi des consommations. 
        </p>

        <p>
            Cordialement,<br>
            <strong>L’équipe du PNLP</strong>
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} PNLP - Tous droits réservés.
        </div>

    </div>
</body>

</html>
