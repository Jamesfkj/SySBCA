<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport PNLP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        .report-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-layout {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }

        .header-layout td {
            border: none;
            padding: 5px 0;
            vertical-align: top;
        }

        .header-left {
            text-align: left;
            width: 50%;
        }

        .header-right {
            text-align: right;
            width: 50%;
        }

        .ministry-title {
            font-size: 14px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 5px;
        }

        .ministry-subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }

        .country-info {
            font-size: 14px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 20px;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #0d9488;
            margin-top: 10px;
            text-align: center;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }

        .info-table th {
            background: #f0fdfc;
            font-weight: bold;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            color: #0d9488;
        }

        .info-table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }

        .info-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0d9488;
            margin: 25px 0 15px 0;
            border-bottom: 1px solid #0d9488;
            padding-bottom: 5px;
        }

        .metrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #ddd;
        }

        .metrics-table th {
            background: #0d9488;
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .metrics-table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }

        .metrics-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin: 25px 0 15px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .metrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #ddd;
        }

        .metrics-table th {
            background: #2c3e50;
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .metrics-table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }

        .metrics-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .percentage-red {
            background: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-negative {
            background: #e74c3c;
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

        .footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="report-container">
        <header class="header">
            <table class="header-layout">
                <tr>
                    <td class="header-left">
                        <div class="ministry-title">MINISTÈRE DE LA SANTÉ DE L'HYGIÈNE PUBLIQUE ET DE L'ACCÈS UNIVERSELLE
                            AUX SOINS</div>
                        <div class="ministry-subtitle">
                            DIRECTION GÉNÉRALE DE L'ACTION SANITAIRE<br>
                            DIRECTION DE LA LUTTE CONTRE LA MALADIE ET LES PROGRAMMES DE SANTÉ PUBLIQUE<br>
                            PROGRAMME NATIONAL DE LUTTE CONTRE LE PALUDISME
                        </div>
                    </td>
                    <td class="header-right">
                        <div class="country-info">RÉPUBLIQUE TOGOLAISE<br><em>Travail - Liberté - Patrie</em></div>
                    </td>
                </tr>
            </table>
            <h1 class="report-title">INFORMATIONS SUR LES DISTRICTS</h1>
        </header>

        <main>
            <table class="info-table">
                <tr>
                    <th>Année</th>
                    <td>{{ now()->year() }}</td>
                    <th>Date de rapport</th>
                    <td>{{ now()->format('d/m/y') }}</td>
                </tr>
                <tr>
                    <th>Région</th>
                    <td>{{ $district->region->nom }}</td>
                    <th>District</th>
                    <td>{{ $district->nom }}</td>
                </tr>
                <tr>
                    <th>Nombre de FS dans le district</th>
                    <td>{{ $nb_fs }}</td>
                    <th>Nombre d'ASC dans le district</th>
                    <td>{{ $nb_asc }}</td>
                </tr>
                <tr>
                    <th>Rapport de la période de</th>
                    <td> {{ $periode->mois_debut }} - {{ $periode->mois_fin }}</td>
                    <th>Trimestre</th>
                    <td>{{ $periode->nom }}</td>
                </tr>
                <tr>
                    <th>Commande pour la période de</th>
                    <td>{{ $periode_suivant->mois_debut }} - {{ $periode_suivant->mois_fin }}</td>
                    <th>Trimestre</th>
                    <td>{{ $periode_suivant->nom }}</td>
                </tr>
                <tr>
                    <th>Deadline/Délai de rapportage</th>
                    <td colspan="3">{{ $deadline->format('d/m/yy') }}</td>
                </tr>
            </table>

            <h2 class="section-title">COMPLÉTUDE</h2>
            <table class="metrics-table">
                <thead>
                    <tr>
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

            <h2 class="section-title">PROMPTITUDE</h2>
            <table class="metrics-table">
                <thead>
                    <tr>
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
                        <td><span class="">{{ (($nb_fs - $fs_prompt) / $nb_fs) * 100 }} %</span></td>
                    </tr>
                    <tr>
                        <td>Synthèse de rapport d'ASC dans le district prompts</td>
                        <td>{{ $asc_prompt }}</td>
                        <td>{{ ($asc_prompt / $nb_fs) * 100 }} %</td>
                    </tr>
                    <tr>
                        <td>Synthèse de rapport d'ASC dans le district pas prompts</td>
                        <td>{{ $nb_fs - $asc_prompt }}</td>
                        <td><span class="">{{ (($nb_fs - $asc_prompt) / $nb_fs) * 100 }} %</span></td>
                    </tr>
                </tbody>
            </table>

            <h2 class="section-title">DÉTAIL DES STRUCTURES</h2>
            <table class="metrics-table">
                <thead>
                    <tr>
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

        </main>

        <footer class="footer">
            <p></p>
        </footer>
    </div>
</body>

</html>
