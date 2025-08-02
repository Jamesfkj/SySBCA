<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Consommations Médicaments</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        h1,
        h2 {
            color: #0f766e;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 16px;
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-left {
            flex-grow: 1;
        }
        .header-logo img {
            height: 60px;
        }
        .meta {
            margin-top: -10px;
            margin-bottom: 10px;
        }
        .meta p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>Rapport de consommation</h1>
            <div class="meta">
                <p><strong>Période :</strong> {{ $conso->periode->libelle ?? 'Période inconnue' }}</p>
                <p><strong>Formation :</strong> {{ $conso->formation->nom ?? 'Formation inconnue' }}</p>
                <p><strong>Acteur :</strong> {{ $conso->acteur ?? 'Acteur inconnu' }}</p>
            </div>
        </div>
        <div class="header-logo">
            <img src="{{ public_path('assets/logo.png') }}" alt="Logo">
        </div>
    </div>

    @foreach ($consommations as $consommation)
        <h2>{{ $loop->iteration }}. {{ $consommation->medicament->nom }}</h2>
        <table>
            <tr>
                <th>Stock début</th>
                <td>{{ $consommation->qte_dispo_deb_periode }}</td>
            </tr>
            <tr>
                <th>Quantité reçue</th>
                <td>{{ $consommation->qte_recu }}</td>
            </tr>
            <tr>
                <th>Quantité utilisée</th>
                <td>{{ $consommation->qte_utilisee }}</td>
            </tr>
            <tr>
                <th>Bénéficiaires</th>
                <td>{{ $consommation->nb_beneficiaire }}</td>
            </tr>
            <tr>
                <th>Périmé</th>
                <td>{{ $consommation->perimee }}</td>
            </tr>
            <tr>
                <th>Pertes et avariées</th>
                <td>{{ $consommation->perte_avarie }}</td>
            </tr>
            <tr>
                <th>Retour CAMEG</th>
                <td>{{ $consommation->qte_retour_cameg }}</td>
            </tr>
            <tr>
                <th>Jours de rupture</th>
                <td>{{ $consommation->nb_jour_rupture }}</td>
            </tr>
            <tr>
                <th>Stock sécurité</th>
                <td>{{ $consommation->stock_securite }}</td>
            </tr>
            <tr>
                <th>CMM ajustée</th>
                <td>{{ $consommation->cmma }}</td>
            </tr>
            <tr>
                <th>Commande prévue</th>
                <td>{{ $consommation->cmd_trim_svt }}</td>
            </tr>
            <tr>
                <th>Quantité accordée</th>
                <td>{{ $consommation->qte_accordee ?? 'N/A' }}</td>
            </tr>
        </table>
    @endforeach
</body>
</html>
