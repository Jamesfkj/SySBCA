<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Consommation - Médicaments Antipaludiques</title>
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #0d5e56;
            --secondary-color: #1a8f85;
            --accent-color: #26a69a;
            --text-dark: #2c3e50;
            --text-medium: #34495e;
            --text-light: #7f8c8d;
            --border-color: #e0e0e0;
            --background-light: #f8f9fa;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        /* Styles de base */
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        @page {
            size: A4;
            margin: 1cm;
        }

        .page {
            page-break-after: always;
            position: relative;
            padding: 1cm;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* Page de garde */
        .cover-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
        }

        .header {
            width: 100%;
            margin-bottom: 2cm;
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 1cm;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            height: 80px;
        }

        .ministry-name {
            font-weight: bold;
            font-size: 14pt;
            color: var(--primary-color);
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .program-name {
            font-weight: 600;
            font-size: 12pt;
            color: var(--text-medium);
        }

        .republic {
            font-weight: bold;
            font-size: 12pt;
        }

        .motto {
            font-style: italic;
            font-size: 10pt;
            margin: 5px 0;
        }

        .document-title {
            font-weight: bold;
            font-size: 20pt;
            color: var(--primary-color);
            text-transform: uppercase;
            margin: 1cm 0;
        }

        .cover-info {
            width: 100%;
            margin: 2cm 0;
        }

        .cover-info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2cm 0;
        }

        .cover-info-table td {
            padding: 10px;
            border: 1px solid var(--border-color);
        }

        .info-item {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .info-label {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 9pt;
        }

        .info-value {
            font-size: 10pt;
            margin-top: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 9pt;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #e65100;
            border: 1px solid #ff9800;
        }

        .cover-qr {
            margin: 2cm 0;
        }

        .cover-footer {
            position: absolute;
            bottom: 1.5cm;
            width: calc(100% - 3cm);
            text-align: center;
            font-size: 9pt;
            color: var(--text-light);
            border-top: 1px solid var(--border-color);
            padding-top: 10px;
        }

        /* Pages de consommation */
        .consumption-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5cm;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
        }

        .consumption-title {
            font-weight: bold;
            font-size: 14pt;
            color: var(--primary-color);
        }

        .consumption-subtitle {
            font-size: 10pt;
            color: var(--text-light);
        }

        .page-info {
            text-align: right;
            font-size: 9pt;
            color: var(--text-light);
        }

        .medication-header {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 12pt;
            border-radius: 5px 5px 0 0;
            margin-bottom: 0;
        }

        .consumption-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1cm;
        }

        .consumption-table th {
            background-color: var(--background-light);
            color: var(--text-dark);
            text-align: left;
            padding: 10px;
            font-weight: bold;
            font-size: 9pt;
            border: 1px solid var(--border-color);
        }

        .consumption-table td {
            padding: 10px;
            border: 1px solid var(--border-color);
            font-size: 10pt;
        }

        .consumption-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .highlight {
            font-weight: bold;
        }

        .positive {
            color: var(--success-color);
        }

        .warning {
            color: var(--warning-color);
        }

        .danger {
            color: var(--danger-color);
        }

        .signature-area {
            margin-top: 2cm;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
        }

        .signature-line {
            border-bottom: 1px solid var(--text-dark);
            margin: 40px 0 5px 0;
        }

        .signature-label {
            font-size: 8pt;
            color: var(--text-light);
        }

        .page-footer {
            position: absolute;
            bottom: 1.5cm;
            left: 1cm;
            right: 1cm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8pt;
            color: var(--text-light);
            border-top: 1px solid var(--border-color);
            padding-top: 10px;
        }

        .page-number {
            font-weight: bold;
        }

        @media print {
            body {
                background: white;
            }

            .page {
                page-break-after: always;
            }

            .page:last-child {
                page-break-after: avoid;
            }
        }
    </style>
</head>

<body>
    <!-- Page de garde -->
    <div class="page cover-page">
        <div class="header">
            <div class="header-content">
                <div>
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/pnlp3.jpg'))) }}"
                        alt="Logo PNLP" class="logo">
                </div>
                <div>
                    <div class="ministry-name">Ministère de la Santé et de l'Hygiène Publique</div>
                    <div class="program-name">Programme National de Lutte contre le Paludisme</div>
                </div>
                <div>
                    <div class="republic">République Togolaise</div>
                    <div class="motto">Travail - Liberté - Patrie</div>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/armoirie.webp'))) }}"
                        alt="Armoiries Togo" class="logo">
                </div>
            </div>
        </div>

        <div class="document-title">
            Rapport de Consommation
        </div>
        <div class="document-title">
            des Médicaments Antipaludiques
        </div>

        <table class="cover-info-table" style="width: 100%; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td class="info-label" style="font-weight: bold; padding: 5px;">Formation sanitaire</td>
                    <td class="info-value" style="padding: 5px;">
                        {{ $conso->formationSanitaire->nom ?? 'Non spécifié' }}</td>
                </tr>
                <tr>
                    <td class="info-label" style="font-weight: bold; padding: 5px;">Période</td>
                    <td class="info-value" style="padding: 5px;">{{ $conso->periode->nom ?? 'Non spécifiée' }}</td>
                </tr>
                <tr>
                    <td class="info-label" style="font-weight: bold; padding: 5px;">District</td>
                    <td class="info-value" style="padding: 5px;">
                        {{ $conso->formationSanitaire->district->nom ?? 'Non spécifié' }}</td>
                </tr>
                <tr>
                    <td class="info-label" style="font-weight: bold; padding: 5px;">Région</td>
                    <td class="info-value" style="padding: 5px;">
                        {{ $conso->formationSanitaire->district->region->nom ?? 'Non spécifiée' }}</td>
                </tr>
                <tr>
                    <td class="" style="font-weight: bold; padding: 5px;">État du rapport</td>
                    <td class="info-value" style="padding: 5px;">
                        <span class="status-badge status-pending">
                            {{ $conso->etat === 'en_cours'
                                ? 'Non soumis'
                                : ($conso->etat === 'soumis'
                                    ? 'En attente de validation'
                                    : ($conso->etat === 'valide'
                                        ? 'Validé'
                                        : 'Non défini')) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="info-label" style="font-weight: bold; padding: 5px;">Généré le</td>
                    <td class="info-value" style="padding: 5px;">{{ date('d/m/Y à H:i') }}</td>
                </tr>
            </tbody>
        </table>


        <div class="page-footer">
            <div>
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="height: 30px;">
                <span style="margin-left: 10px;">Créé le {{now()->format('d/m/yy h:i')}}</span>
            </div>
        </div>
    </div>

    <!-- Pages de consommation -->
    @foreach ($consommations as $consommation)
        <div class="page">
            <div class="consumption-header">
                <div>
                    <div class="consumption-title">Rapport de Consommation - Médicaments Antipaludiques</div>
                    <div class="consumption-subtitle">
                        Période: {{ $conso->periode->nom ?? 'Non spécifiée' }} |
                        Formation: {{ $conso->formation_sanitaire->nom ?? 'Non spécifié' }}
                    </div>
                </div>
                <div class="page-info">
                    Médicament {{ $loop->iteration }} sur {{ count($consommations) }}<br>
                    {{ date('d/m/Y') }}
                </div>
            </div>

            <div class="medication-header">
                {{ $consommation->medicament->nom }}
            </div>

            <table class="consumption-table">
                <tr>
                    <td>Stock début de période</td>
                    <td class="{{ $consommation->qte_dispo_deb_periode <= 0 ? 'danger highlight' : 'highlight' }}">
                        {{ $consommation->qte_dispo_deb_periode }}
                    </td>
                </tr>
                <tr>
                    <td>Quantité reçue</td>
                    <td class="{{ $consommation->qte_recu <= 0 ? 'warning highlight' : 'highlight' }}">
                        {{ $consommation->qte_recu }}
                    </td>
                </tr>
                <tr>
                    <td>Quantité total en stock</td>
                    <td class="{{ $consommation->qte_en_stock <= 0 ? 'warning highlight' : 'highlight' }}">
                        {{ $consommation->qte_en_stock }}
                    </td>
                </tr>
                <tr>
                    <td>Quantité utilisée</td>
                    <td class="highlight">
                        {{ $consommation->qte_utilisee }}
                    </td>
                </tr>
                <tr>
                    <td>Nombre de bénéficiaires</td>
                    <td class="highlight">
                        {{ $consommation->nb_beneficiaire }} patients
                    </td>
                </tr>
                <tr>
                    <td>Médicaments périmés</td>
                    <td class="{{ $consommation->perimee > 0 ? 'danger highlight' : 'highlight' }}">
                        {{ $consommation->perimee }}
                    </td>
                </tr>
                <tr>
                    <td>Pertes et avariées</td>
                    <td class="{{ $consommation->perte_avarie > 0 ? 'danger highlight' : 'highlight' }}">
                        {{ $consommation->perte_avarie }}
                    </td>
                </tr>
                <tr>
                    <td>Retour CAMEG</td>
                    <td>
                        {{ $consommation->qte_retour_cameg }}
                    </td>
                </tr>
                <tr>
                    <td>Jours de rupture</td>
                    <td class="{{ $consommation->nb_jour_rupture > 0 ? 'danger highlight' : 'positive highlight' }}">
                        {{ $consommation->nb_jour_rupture }} jours
                    </td>
                </tr>
                <tr>
                    <td>Stock de sécurité</td>
                    <td>
                        {{ $consommation->stock_securite }}
                    </td>
                </tr>
                <tr>
                    <td>CMM ajustée</td>
                    <td class="{{ $consommation->cmma ? 'highlight' : 'warning' }}">
                        {{ $consommation->cmma ? $consommation->cmma : 'Non calculée' }}
                    </td>
                </tr>
                <tr>
                    <td>Commande prévue trimestre suivant</td>
                    <td class="highlight">
                        {{ $consommation->cmd_trim_svt }}
                    </td>
                </tr>
                <tr>
                    <td>Quantité accordée</td>
                    <td
                        class="{{ $consommation->qte_accordee === null ? 'warning highlight' : 'positive highlight' }}">
                        {{ $consommation->qte_accordee !== null ? $consommation->qte_accordee : 'Non validé' }}
                    </td>
                </tr>

            </table>
            <div class="page-footer">
                <div>
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="height: 30px;">
                <span style="margin-left: 10px;">Créé le {{now()->format('d/m/yy h:i')}}</span>
                </div>
                <div class="page-number">Page {{ $loop->iteration }}</div>
            </div>
        </div>
    @endforeach
</body>

</html>
