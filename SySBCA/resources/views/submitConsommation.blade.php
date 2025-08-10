<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirmation de soumission - PNLP</title>
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

        p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        ul.details {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
            padding-left: 20px;
        }

        ul.details li {
            margin-bottom: 6px;
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
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h1>Bonjour {{ $utilisateur->username }} !</h1>

        <p>
            Votre consommation a bien été soumise avec succès.
        </p>

        <p>
            Détails de la consommation soumise :
        </p>

        <ul class="details">
            <li><strong>Formation sanitaire :</strong> {{ $conso->formationSanitaire->nom ?? 'N/A' }}</li>
            <li><strong>Acteur concerné :</strong> {{ $conso->acteur ?? 'N/A' }}</li>
            <li><strong>Période :</strong> {{ $conso->periode->nom ?? 'N/A' }}</li>
            <li><strong>Date de soumission :</strong> {{ optional($conso->submitted_at ?? $conso->updated_at)->format('d/m/Y H:i') ?? 'N/A' }}</li>
        </ul>

        <p>
            Les quantités commandées seront validées par le district dans les bref délais. Vous pouvez effectuer le suivi de votre consommation en vous connectant à votre espace personnel.
        </p>

        <p>
            Merci pour votre collaboration.<br />
            <strong>L’équipe PNLP</strong>
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} PNLP - Tous droits réservés.
        </div>
    </div>
</body>

</html>
