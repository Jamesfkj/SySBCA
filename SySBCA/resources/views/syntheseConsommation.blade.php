<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Consommation - Médicaments</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    <style>
        /* Style général */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 0;
            padding: 0;
            background: #fff;
            line-height: 1.4;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            text-transform: uppercase;
            margin: 15px 0;
            color: #0f766e;
            font-weight: bold;
        }

        h4 {
            margin: 8px 0;
            color: #0f766e;
            font-size: 14px;
        }

        /* En-tête amélioré */
        .header {
            width: 100%;
            border-bottom: 3px solid #0f766e;
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
        }

        .motto {
            font-style: italic;
            color: #0f766e;
            margin-top: 8px;
        }

        /* Métadonnées */
        .meta {
            width: 100%;
            border: 2px solid #0f766e;
            margin: 15px 0;
            background: #f8f9fa;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 12px 15px;
            border-right: 1px solid #ddd;
            vertical-align: top;
        }

        .meta-table td:last-child {
            border-right: none;
        }

        .meta-label {
            font-weight: bold;
            color: #0f766e;
            font-size: 12px;
        }

        .meta-value {
            margin-top: 4px;
            font-size: 13px;
        }

        /* Médicaments */
        .medication-section {
            border: 1px solid #ddd;
            margin: 20px 0;
            border-radius: 4px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .medication-title {
            background: #0f766e;
            color: #fff;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
        }

        .medication-number {
            background: #fff;
            color: #0f766e;
            border-radius: 50%;
            padding: 3px 8px;
            margin-right: 10px;
            font-weight: bold;
        }

        /* Tableaux */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }

        .data-table th {
            background: #f0f0f0;
            color: #0f766e;
            font-size: 12px;
            font-weight: bold;
            width: 40%;
        }

        .data-table td {
            font-size: 12px;
            width: 60%;
        }

        /* Valeurs colorées - simplifiées pour PDF */
        .positive-value {
            color: #0f766e;
            font-weight: bold;
        }

        .critical-value {
            color: #dc2626;
            font-weight: bold;
        }

        .highlight-value {
            color: #0f766e;
            font-weight: bold;
        }

        /* Ecarts avec couleurs spécifiques */
        .ecart-positive {
            color: #eab308;
            font-weight: bold;
        }

        .ecart-negative {
            color: #dc2626;
            font-weight: bold;
        }

        .ecart-zero {
            color: #0f766e;
            font-weight: bold;
        }

        /* Pied de page amélioré */
        .document-footer {
            margin-top: 30px;
            border-top: 2px solid #0f766e;
            padding-top: 20px;
            page-break-inside: avoid;
        }

        .footer-content {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .qr-section {
            display: table-cell;
            width: 25%;
            vertical-align: top;
            text-align: left;
            padding-right: 20px;
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            width: 100%;

        }

        .qr-line {
            border-top: 1.5px solid #0f766e;
            margin-bottom: 8px;
            width: 100%;
        }


        .qr-section img {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            margin-bottom: 8px;
        }

        .qr-info {
            font-size: 10px;
            line-height: 1.3;
        }

        .report-info {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            padding: 0 15px;
        }

        .report-detail {
            margin: 5px 0;
            font-size: 11px;
        }

        .status-pending {
            background: #fbbf24;
            color: #fff;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .signature-section {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            padding-left: 20px;
        }

        .signature-block {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fafafa;
        }

        .signature-title {
            font-size: 11px;
            font-weight: bold;
            color: #0f766e;
            margin-bottom: 8px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 20px;
            width: 100%;
            margin: 8px 0;
        }

        .signature-date {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .signature-details {
            font-size: 11px;
            margin: 8px 0;
        }

        /* Améliorations pour l'impression */
        @media print {
            body {
                font-size: 12px;
                color: #000;
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
                margin: 15px 0;
            }

            .document-footer {
                page-break-inside: avoid;
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
        }

        /* Responsive pour les petits écrans */
        @media screen and (max-width: 768px) {

            .header-content,
            .footer-content {
                display: block;
            }

            .header-left,
            .header-right,
            .qr-section,
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
                border-bottom: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
    <!-- En-tête officiel amélioré -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-logo">
                    <img src="{{ asset('images/pnlp3.jpg') }}" alt="Logo PNLP"
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
                    <img src="{{ public_path('assets/logo.png') }}" alt="Armoiries Togo"
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
                    <div class="meta-value">{{$periode->nom}} : {{$periode->mois_debut}} - {{$periode->mois_debut}}</div>
                </td>
                <td>
                    <div class="meta-label">Commande pour la période : </div>
                    <div class="meta-value">{{$periode_suivant->nom}} : {{$periode_suivant->mois_debut}} - {{$periode_suivant->mois_debut}}</div>
                </td>
                <td>
                    <div class="meta-label">Région sanitaire</div>
                    <div class="meta-value">{{$district->region->nom}}</div>
                </td>
                <td>
                    <div class="meta-label">District sanitaire</div>
                    <div class="meta-value">{{$district->nom}}</div>
                </td>
                <td>
                    <div class="meta-label">Type de rapport</div>
                    <div class="meta-value">{{ $type_synthese}}</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="qr-section">
        <div class="qr-line"></div> <!-- Ligne au-dessus -->
        <canvas id="qrcode" width="80" height="80"></canvas>
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code"
            style="width: 50px; height: 50px; margin-top: 10px;">
    </div>

    <!-- Boucle des médicaments avec données Laravel et styles améliorés -->
    @foreach ($consommations as $consommation)
        @php
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
                    <td>{{ $consommation['qte_recu'] ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Stock totale en début de période</th>
                    <td>
                        <span class="positive-value">{{ $consommation['qte_en_stock'] ?? 0 }}</span>
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
                    <td>{{ $consommation['nb_beneficiaire'] ?? 0 }} patients</td>
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
                            <span class="critical-value">{{ $consommation['nb_jour_rupture'] ?? 0 }} jours</span>
                        @else
                            <span class="positive-value">{{ $consommation['nb_jour_rupture'] ?? 0 }} jours</span>
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
                            <span class="highlight-value">{{ $consommation['cmma'] ?? 0 }}
                                /mois</span>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Commande prévue trimestre suivant</th>
                    <td>{{ $consommation['cmd_trim_svt'] ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Quantité accordée</th>
                    <td>
                        @if (isset($consommation['qte_accordee']))
                            <span class="highlight-value">{{ $consommation['qte_accordee'] ?? 0 }}</span>
                        @else
                            <span style="color: #666; font-style: italic;">En attente de validation</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="qr-section">
            <div class="qr-line"></div> <!-- Ligne au-dessus -->
            <canvas id="qrcode" width="80" height="80"></canvas>
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code"
                style="width: 50px; height: 50px; margin-top: 10px;">
        </div>
    @endforeach

    <div class="document-footer">
        <div class="footer-content">
            <div class="qr-section">
                <div class="qr-line"></div>
                <canvas id="qrcode" width="80" height="80"></canvas>
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code"
                    style="width: 50px; height: 50px; margin-top: 10px;">
            </div>

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

</body>

</html>
