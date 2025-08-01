<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consommations Médicaments</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #0f766e; font-size: 20px; margin-bottom: 10px; }
        h2 { margin-top: 30px; font-size: 16px; color: #0f766e; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Liste des consommations</h1>

    @foreach ($consommations as $consommation)
        <h2>{{ $loop->iteration }}. {{ $consommation->medicament->nom }}</h2>
        <table>
            <tr><th>Stock sécurité</th><td>{{ $consommation->stock_securite }}</td></tr>
            <tr><th>CMM ajustée</th><td>{{ $consommation->cmma }}</td></tr>
            <tr><th>Commande prévue</th><td>{{ $consommation->cmd_trim_svt }}</td></tr>
            <tr><th>Quantité accordée</th><td>{{ $consommation->qte_accordee }}</td></tr>
            <tr><th>Stock début</th><td>{{ $consommation->qte_dispo_deb_periode }}</td></tr>
            <tr><th>Quantité reçue</th><td>{{ $consommation->qte_recu }}</td></tr>
            <tr><th>Quantité utilisée</th><td>{{ $consommation->qte_utilisee }}</td></tr>
            <tr><th>Bénéficiaires</th><td>{{ $consommation->nb_beneficiaire }}</td></tr>
            <tr><th>Périmé</th><td>{{ $consommation->perimee }}</td></tr>
            <tr><th>Pertes et avariées</th><td>{{ $consommation->perte_avarie }}</td></tr>
            <tr><th>Retour CAMEG</th><td>{{ $consommation->qte_retour_cameg }}</td></tr>
            <tr><th>Jours de rupture</th><td>{{ $consommation->nb_jour_rupture }}</td></tr>
        </table>
    @endforeach
</body>
</html>
