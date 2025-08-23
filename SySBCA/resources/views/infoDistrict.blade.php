<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport PNLP - Informations sur les Districts</title>
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

        .cover-info-table {
            width: 100%; 
            border-collapse: collapse;
            margin: 2cm 0;
        }

        .cover-info-table td {
            padding: 10px;
            border: 1px solid var(--border-color);
        }
        
        .info-label {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 9pt;
            background-color :hsl(174, 69%, 89%);
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

        /* Pages de contenu */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5cm;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
        }

        .content-title {
            font-weight: bold;
            font-size: 14pt;
            color: var(--primary-color);
        }

        .content-subtitle {
            font-size: 10pt;
            color: var(--text-light);
        }

        .page-info {
            text-align: right;
            font-size: 9pt;
            color: var(--text-light);
        }

        .section-title {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 12pt;
            border-radius: 5px 5px 0 0;
            margin: 20px 0 0 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1cm;
        }

        .info-table th {
            background-color: var(--background-light);
            color: black;
            text-align: left;
            padding: 10px;
            font-weight: bold;
            font-size: 9pt;
            border: 1px solid var(--border-color);
        }

        .info-table td {
            padding: 10px;
            border: 1px solid var(--border-color);
            font-size: 10pt;
        }

        .info-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .metrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .metrics-table th {
            background: var(--text-dark);
            color: black;
            font-weight: bold;
            padding: 12px;
            text-align: left;
            border: 1px solid var(--border-color);
        }

        .metrics-table td {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
        }

        .metrics-table tr:nth-child(even) {
            background: #f9f9f9;
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

        .status-negative {
            background: var(--danger-color);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-positive {
            background: var(--success-color);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .table-head{
            background-color: var(--background-light);
            text-align: left;
            padding: 10px;
            font-weight: bold;
            font-size: 9pt;
            border: 1px solid var(--border-color);
        }

        .status-neutral {
            background: var(--text-light);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .type-badge {
            background: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
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
            Informations sur les Districts
        </div>

        <table class="cover-info-table">
            <tbody>
                <tr>
                    <td class="info-label">Année</td>
                    <td class="info-value">{{ now()->year }}</td>
                    <td class="info-label">Date de rapport</td>
                    <td class="info-value">{{ now()->format('d/m/y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Région</td>
                    <td class="info-value">{{ $district->region->nom }}</td>
                    <td class="info-label">District</td>
                    <td class="info-value">{{ $district->nom }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nombre de FS dans le district</td>
                    <td class="info-value">{{ $nb_fs }}</td>
                    <td class="info-label">Nombre d'ASC dans le district</td>
                    <td class="info-value">{{ $nb_asc }}</td>
                </tr>
                <tr>
                    <td class="info-label">Rapport de la période de</td>
                    <td class="info-value">{{ $periode->mois_debut }} - {{ $periode->mois_fin }}</td>
                    <td class="info-label">Trimestre</td>
                    <td class="info-value">{{ $periode->nom }}</td>
                </tr>
                <tr>
                    <td class="info-label">Commande pour la période de</td>
                    <td class="info-value">{{ $periode_suivant->mois_debut }} - {{ $periode_suivant->mois_fin }}</td>
                    <td class="info-label">Trimestre</td>
                    <td class="info-value">{{ $periode_suivant->nom }}</td>
                </tr>
                <tr>
                    <td class="info-label">Deadline/Délai de rapportage</td>
                    <td class="info-value" colspan="3">{{ $deadline->format('d/m/yy') }}</td>
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

    <!-- Page de complétude -->
    <div class="page">
        <div class="content-header">
            <div>
                <div class="content-title">Informations sur les Districts</div>
                <div class="content-subtitle">
                    District: {{ $district->nom }} |
                    Région: {{ $district->region->nom }}
                </div>
            </div>
            <div class="page-info">
                {{ date('d/m/Y') }}
            </div>
        </div>

        <div class="section-title">
            COMPLÉTUDE
        </div>

        <table class="metrics-table">
            <thead>
                <tr class="table-head">
                    <th>Indicateur</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nombre de FS dans le district ayant rapporté</td>
                    <td>{{ $nb_fs_soumission }}</td>
                </tr>
                <tr>
                    <td>Nombre d'ASC dans le district ayant rapporté (rapports reçus)</td>
                    <td>{{ $nb_asc_soumission }}</td>
                </tr>
                <tr>
                    <td>Calcul de la Complétude des FS</td>
                    <td>{{ ($nb_fs_soumission / $nb_fs) * 100 }} %</td>
                </tr>
                <tr>
                    <td>Calcul de la Complétude des ASC</td>
                    <td>{{ ($nb_asc_soumission / $nb_fs) * 100 }} %</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">
            PROMPTITUDE
        </div>

        <table class="metrics-table">
            <thead>
                <tr class="table-head">
                    <th>Indicateur</th>
                    <th>Nombre</th>
                    <th>Pourcentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nombre de FS dans le district prompts</td>
                    <td>{{ $fs_prompt }}</td>
                    <td>{{ ($fs_prompt / $nb_fs) * 100 }} %</td>
                </tr>
                <tr>
                    <td>Nombre de FS dans le district pas prompts</td>
                    <td>{{ $nb_fs - $fs_prompt }}</td>
                    <td>{{ (($nb_fs - $fs_prompt) / $nb_fs) * 100 }} %</td>
                </tr>
                <tr>
                    <td>Synthèse de rapport d'ASC dans le district prompts</td>
                    <td>{{ $asc_prompt }}</td>
                    <td>{{ ($asc_prompt / $nb_fs) * 100 }} %</td>
                </tr>
                <tr>
                    <td>Synthèse de rapport d'ASC dans le district pas prompts</td>
                    <td>{{ $nb_fs - $asc_prompt }}</td>
                    <td>{{ (($nb_fs - $asc_prompt) / $nb_fs) * 100 }} %</td>
                </tr>
            </tbody>
        </table>

        <div class="page-footer">
            <div>
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="height: 30px;">
                <span style="margin-left: 10px;">Créé le {{now()->format('d/m/yy h:i')}}</span>
            </div>
            <div class="page-number">Page 2</div>
        </div>
    </div>

    <!-- Page des détails des structures -->
    <div class="page">
        <div class="content-header">
            <div>
                <div class="content-title">Informations sur les Districts</div>
                <div class="content-subtitle">
                    District: {{ $district->nom }} |
                    Région: {{ $district->region->nom }}
                </div>
            </div>
            <div class="page-info">
                {{ date('d/m/Y') }}
            </div>
        </div>

        <div class="section-title">
            DÉTAIL DES STRUCTURES
        </div>

        <table class="metrics-table">
            <thead>
                <tr class="table-head">
                    <th>N°</th>
                    <th>Type FS/ASC</th>
                    <th>Formation sanitaire</th>
                    <th>Date de dépôt du rapport</th>
                    <th>Écarts (jours)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consommations as $index => $conso)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="type-badge">{{ $conso->acteur }}</span></td>
                        <td>{{ $conso->formationSanitaire->nom }}</td>
                        @php
                            $submittedDate = \Carbon\Carbon::parse($conso->submitted_at);
                            $deadlineDate = \Carbon\Carbon::parse($deadline);

                            $diff = $submittedDate->diffInDays($deadlineDate, false); 
                            $absDiff = intval(abs($diff));

                            $ecartClass = '';
                            $ecartText = '';

                            if ($diff > 0) {
                                $ecartClass = 'status-positive';
                                $ecartText = "En avance : $absDiff jours";
                            } elseif ($diff < 0) {
                                $ecartClass = 'status-negative';
                                $ecartText = "Retard : $absDiff jours";
                            } else {
                                $ecartClass = 'status-neutral';
                                $ecartText = 'À la date';
                            }
                        @endphp
                        <td>{{ $submittedDate->format('d/m/Y') }}</td>
                        <td><span class="{{ $ecartClass }}">{{ $ecartText }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="page-footer">
            <div>
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="height: 30px;">
                <span style="margin-left: 10px;">RPT-{{ date('Y-m-d-His') }}</span>
            </div>
            <div class="page-number">Page 3</div>
        </div>
    </div>
</body>
</html>