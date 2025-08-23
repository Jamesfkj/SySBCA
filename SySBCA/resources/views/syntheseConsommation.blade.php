<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Consommation - Médicaments</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #0f766e;
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

        /* Style général */
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            background: #fff;
            line-height: 1.5;
        }

        @page {
            size: A4;
            margin: 1cm;
        }

        /* Pagination */
        .page {
            min-height: calc(100vh - 120px);
            page-break-after: always;
            position: relative;
            padding-bottom: 100px;
            margin-bottom: 40px;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .page-header {
            position: relative;
            margin-bottom: 20px;
        }

        .page-number {
            position: absolute;
            bottom: 10px;
            left: 20px;
            font-size: 12px;
            color: var(--text-light);
            background: var(--background-light);
            padding: 5px 10px;
            border: 1px solid var(--border-color);
            border-radius: 3px;
        }

        h1 {
            text-align: center;
            font-size: 22px;
            text-transform: uppercase;
            margin: 15px 0;
            color: var(--primary-color);
            font-weight: bold;
        }

        h4 {
            margin: 8px 0;
            color: var(--primary-color);
            font-size: 15px;
        }

        /* En-tête amélioré */
        .header {
            width: 100%;
            border-bottom: 3px solid var(--primary-color);
            padding: 15px 0;
            margin-bottom: 20px;
        }

        .header-content {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .header-left,
        .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
        }

        .header-logo {
            margin-bottom: 10px;
        }

        .header-logo img {
            max-height: 60px;
            margin-bottom: 5px;
        }

        .logo-placeholder {
            font-size: 10px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 8px;
            color: #888;
            background: #f9f9f9;
        }

        .ministry-info,
        .direction-info,
        .republic-info,
        .motto {
            font-size: 11px;
            line-height: 1.3;
            text-transform: uppercase;
            font-weight: bold;
            margin: 2px 0;
            color: var(--text-dark);
        }

        .motto {
            font-style: italic;
            color: var(--primary-color);
            margin-top: 8px;
        }

        /* Métadonnées */
        .meta {
            width: 100%;
            border: 2px solid var(--primary-color);
            margin: 15px 0;
            background: var(--background-light);
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 15px 18px;
            border-right: 1px solid var(--border-color);
            vertical-align: top;
        }

        .meta-table td:last-child {
            border-right: none;
        }

        .meta-label {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 13px;
        }

        .meta-value {
            margin-top: 6px;
            font-size: 14px;
            color: var(--text-medium);
        }

        /* Médicaments */
        .medication-section {
            border: 1px solid var(--border-color);
            margin: 20px 0;
            border-radius: 4px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .medication-title {
            background: var(--primary-color);
            color: #fff;
            padding: 12px 18px;
            font-weight: bold;
            font-size: 16px;
        }

        .medication-number {
            background: #fff;
            color: var(--primary-color);
            border-radius: 50%;
            padding: 4px 10px;
            margin-right: 12px;
            font-weight: bold;
        }

        .medication-info {
            padding: 10px 18px;
            background: #fafafa;
            font-size: 13px;
            color: var(--text-medium);
            border-bottom: 1px solid var(--border-color);
        }

        /* Tableaux agrandis */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            text-align: left;
        }

        .data-table th {
            background: var(--background-light);
            color: var(--primary-color);
            font-size: 14px;
            font-weight: bold;
            width: 40%;
        }

        .data-table td {
            font-size: 14px;
            width: 60%;
            line-height: 1.4;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Valeurs colorées */
        .positive-value {
            color: var(--success-color);
            font-weight: bold;
            font-size: 15px;
        }

        .critical-value {
            color: var(--danger-color);
            font-weight: bold;
            font-size: 15px;
        }

        .warning-value {
            color: var(--warning-color);
            font-weight: bold;
            font-size: 15px;
        }

        .highlight-value {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 15px;
        }

        /* Ecarts avec couleurs spécifiques */
        .ecart-positive {
            color: var(--warning-color);
            font-weight: bold;
            font-size: 15px;
        }

        .ecart-negative {
            color: var(--danger-color);
            font-weight: bold;
            font-size: 15px;
        }

        .ecart-zero {
            color: var(--success-color);
            font-weight: bold;
            font-size: 15px;
        }

        /* QR Code section améliorée */
        .qr-section {
            position: fixed;
            bottom: 20px;
            right: 20px;
            text-align: center;
            background: white;
            padding: 5px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        .qr-section img {
            width: 50px;
            height: 50px;
            border: 1px solid var(--border-color);
        }

        .qr-info {
            font-size: 8px;
            line-height: 1.2;
            color: var(--text-light);
            margin-top: 2px;
        }

        /* Pied de page amélioré */
        .document-footer {
            margin-top: 30px;
            border-top: 2px solid var(--primary-color);
            padding-top: 20px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .footer-content {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .report-info {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            padding: 0 15px;
        }

        .report-detail {
            margin: 6px 0;
            font-size: 12px;
            color: var(--text-medium);
        }

        .status-pending {
            background: var(--warning-color);
            color: #fff;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .signature-section {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-left: 20px;
        }

        .signature-block {
            margin-bottom: 25px;
            border: 1px solid var(--border-color);
            padding: 12px;
            background: var(--background-light);
        }

        .signature-title {
            font-size: 12px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .signature-line {
            border-bottom: 1px solid var(--text-dark);
            height: 22px;
            width: 100%;
            margin: 10px 0;
        }

        .signature-date {
            font-size: 11px;
            color: var(--text-light);
            margin-top: 6px;
        }

        /* Améliorations pour l'impression */
        @media print {
            body {
                font-size: 13px;
                color: #000;
            }

            .page {
                page-break-after: always;
            }

            .page:last-child {
                page-break-after: avoid;
            }

            .header {
                border-bottom: 2px solid #000;
            }

            .meta {
                background: #fff;
                border: 1px solid #000;
            }

            .medication-section {
                page-break-inside: avoid;
                break-inside: avoid;
                margin: 15px 0;
            }

            .document-footer {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .status-pending {
                background: none;
                color: #000;
                border: 1px solid #000;
            }

            .positive-value,
            .critical-value,
            .highlight-value,
            .ecart-positive,
            .ecart-negative,
            .ecart-zero {
                color: #000;
            }



            .page-number {
                position: fixed;
                bottom: 1cm;
                right: 1cm;
            }
        }

        /* Responsive pour les petits écrans */
        @media screen and (max-width: 768px) {

            .header-content,
            .footer-content {
                display: block;
            }

            .header-left,
            .header-right,
            .report-info,
            .signature-section {
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }

            .meta-table td {
                display: block;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .page {
                min-height: auto;
                padding-bottom: 40px;
            }
        }

        .medications-container {
            display: flex;
            flex-direction: column;
        }

        .page-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <!-- En-tête officiel amélioré -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-logo">
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/pnlp3.jpg'))) }}"
                        alt="Logo PNLP"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="logo-placeholder" style="display: none;">LOGO<br>PNLP</div>
                </div>
                <div class="ministry-info">MINISTÈRE DE LA SANTÉ DE L'HYGIÈNE PUBLIQUE</div>
                <div class="ministry-info">ET DE L'ACCÈS UNIVERSEL AUX SOINS</div>
                <div class="direction-info">DIRECTION GÉNÉRALE DE L'ACTION SANITAIRE</div>
                <div class="direction-info">DIRECTION DE LA LUTTE CONTRE LA MALADIE</div>
                <div class="direction-info">ET LES PROGRAMMES DE SANTÉ PUBLIQUE</div>
                <div class="direction-info">PROGRAMME NATIONAL DE LUTTE CONTRE LE PALUDISME</div>
            </div>

            <div class="header-right">
                <div class="header-logo">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/armoirie.webp'))) }}"
                        alt="Armoiries Togo"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="logo-placeholder" style="display: none;">ARMOIRIES<br>TOGO</div>
                </div>
                <div class="republic-info">RÉPUBLIQUE TOGOLAISE</div>
                <div class="motto">Travail - Liberté - Patrie</div>
            </div>
        </div>
    </div>

    <!-- Titre principal -->
    <h1>RAPPORT DE CONSOMMATION</h1>

    <!-- Métadonnées améliorées -->
    <div class="meta">
        <table class="meta-table">
            <tr>
                <td>
                    <div class="meta-label">Période de la commande</div>
                    <div class="meta-value">{{ $periode->nom }} : {{ $periode->mois_debut }} -
                        {{ $periode->mois_debut }}</div>
                </td>
                <td>
                    <div class="meta-label">Commande pour la période : </div>
                    <div class="meta-value">{{ $periode_suivant->nom }} : {{ $periode_suivant->mois_debut }} -
                        {{ $periode_suivant->mois_debut }}</div>
                </td>
                <td>
                    <div class="meta-label">Région sanitaire</div>
                    <div class="meta-value">{{ $district->region->nom ?? 'Toutes les régions' }}</div>
                </td>
                <td>
                    <div class="meta-label">District sanitaire</div>
                    <div class="meta-value">{{ $district->nom ?? 'Toutes les districts' }}</div>
                </td>
                <td>
                    <div class="meta-label">Type de rapport</div>
                    <div class="meta-value">{{ $type_synthese }}</div>
                </td>
            </tr>
        </table>
    </div>
    @php
        $page = 1;
    @endphp
    <div class="qr-section">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
        <div class="qr-info">Créé le {{ now()->format('d/m/y h:i') }}</div>
        <div>{{ $page }}</div>
    </div>


    @foreach ($consommations as $consommation)
        @php
            $page = $page + 1;
            $stock_theo =
                $consommation['qte_en_stock'] -
                $consommation['qte_utilisee'] -
                $consommation['qte_retour_cameg'] -
                $consommation['perte_avarie'] -
                $consommation['perimee'];
            $perte_non_dec = $consommation['qte_restante'] - $stock_theo;
        @endphp
        <div class="medication-section">
            <div class="medication-title">
                <span class="medication-number">{{ $loop->iteration }}</span>
                {{ $consommation['medicament']['nom'] ?? 'Médicament inconnu' }}
            </div>
            <p>Conditionnement :
                {{ $consommation['medicament']['conditionnement'] }} :
                {{ $consommation['medicament']['qte_par_conditionnement'] }}
                {{ $consommation['medicament']['format'] }}</p>
            <table class="data-table">
                <tr>
                    <th>Stock initiale début de période</th>
                    <td>
                        @if (($consommation['qte_dispo_deb_periode'] ?? 0) > 0)
                            <span class="positive-value">{{ $consommation['qte_dispo_deb_periode'] ?? 0 }}</span>
                        @else
                            <span class="critical-value">{{ $consommation['qte_dispo_deb_periode'] ?? 0 }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Quantité reçue dans le trimestre</th>
                    <td>
                        @if (($consommation['qte_recu'] ?? 0) > 0)
                            <span class="highlight-value">{{ $consommation['qte_recu'] ?? 0 }}</span>
                        @else
                            <span class="warning-value">{{ $consommation['qte_recu'] ?? 0 }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Stock totale en début de période</th>
                    <td>
                        @if (($consommation['qte_en_stock'] ?? 0) > 0)
                            <span class="positive-value">{{ $consommation['qte_en_stock'] ?? 0 }}</span>
                        @else
                            <span class="warning-value">{{ $consommation['qte_en_stock'] ?? 0 }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Quantité utilisée</th>
                    <td>
                        @if (($consommation['qte_utilisee'] ?? 0) > 0)
                            <span class="highlight-value">{{ $consommation['qte_utilisee'] ?? 0 }}</span>
                        @else
                            {{ $consommation['qte_utilisee'] ?? 0 }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Nombre de bénéficiaires</th>
                    <td>
                        <span class="highlight-value">{{ $consommation['nb_beneficiaire'] ?? 0 }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Médicaments périmés</th>
                    <td>
                        @if (($consommation['perimee'] ?? 0) > 0)
                            <span class="critical-value">{{ $consommation['perimee'] ?? 0 }}</span>
                        @else
                            <span class="positive-value">{{ $consommation['perimee'] ?? 0 }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Pertes et avariées</th>
                    <td>
                        @if (($consommation['perte_avarie'] ?? 0) > 0)
                            <span class="critical-value">{{ $consommation['perte_avarie'] ?? 0 }}</span>
                        @else
                            <span class="positive-value">{{ $consommation['perte_avarie'] ?? 0 }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Retour CAMEG</th>
                    <td>{{ $consommation['qte_retour_cameg'] ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Jours de rupture</th>
                    <td>
                        @if (($consommation['nb_jour_rupture'] ?? 0) > 0)
                            <span class="critical-value">{{ $consommation['nb_jour_rupture'] ?? 0 }} </span>
                        @else
                            <span class="positive-value">{{ $consommation['nb_jour_rupture'] ?? 0 }} </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Quantité réel en stock</th>
                    <td>
                        @if (isset($consommation['qte_restante']))
                            <span class="highlight-value">{{ $consommation['qte_restante'] ?? 0 }}</span>
                        @else
                            <span style="color: #666; font-style: italic;">0</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Quantité théorique en stock</th>
                    <td>
                        <span class="highlight-value">{{ $stock_theo ?? 0 }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Ecarts</th>
                    <td>
                        @if ($perte_non_dec > 0)
                            <span class="ecart-positive">+{{ $perte_non_dec }}</span>
                        @elseif ($perte_non_dec < 0)
                            <span class="ecart-negative">{{ $perte_non_dec }}</span>
                        @else
                            <span class="ecart-zero">{{ $perte_non_dec }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Stock de sécurité</th>
                    <td>{{ $consommation['stock_securite'] ?? 0 }}</td>
                </tr>
                <tr>
                    <th>CMM ajustée</th>
                    <td>
                        @if (!empty($consommation['cmma']))
                            <span class="highlight-value">{{ $consommation['cmma'] ?? 0 }} </span>
                        @else
                            <span class="warning-value">Non accordée</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Commande prévue trimestre suivant</th>
                    <td>
                        <span class="highlight-value">{{ $consommation['cmd_trim_svt'] ?? 0 }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Quantité accordée</th>
                    <td>
                        @if (isset($consommation['qte_accordee']))
                            <span class="positive-value">{{ $consommation['qte_accordee'] ?? 0 }}</span>
                        @else
                            <span class="warning-value">Non validé</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div>
        </div>
        <div class="qr-section">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            <div class="qr-info">Créé le {{ now()->format('d/m/y h:i') }}</div>
            <div>{{ $page }}</div>
        </div>
    @endforeach

    <div class="document-footer">
        <div class="footer-content">
            <!-- Section signatures améliorée -->
            <div class="signature-section">
                <h4>Visa et Signatures</h4>

                <div class="signature-block">
                    <div class="signature-title">Point Focal District</div>
                    <div class="signature-line"></div>
                    <div class="signature-date">Date: ___/___/______</div>
                </div>

                <div class="signature-block">
                    <div class="signature-title">Point Focal Région</div>
                    <div class="signature-line"></div>
                    <div class="signature-date">Date: ___/___/______</div>
                </div>

                <div class="signature-block">
                    <div class="signature-title">Directeur Régional de la Santé</div>
                    <div class="signature-line"></div>
                    <div class="signature-date">Date: ___/___/______</div>
                </div>
            </div>
        </div>
    </div>
    <div class="qr-section">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
        <div class="qr-info">Créé le {{ now()->format('d/m/y h:i') }}</div>
        <div>{{$page + 1 }}</div>
    </div>

</body>

</html>
