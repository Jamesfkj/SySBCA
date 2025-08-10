<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Consommation - Médicaments</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    <style>

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: white;
            margin: 0;
            padding: 20px;
        }

        /* En-tête officiel - Garde les couleurs */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #0f766e;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header-left {
            flex: 1;
            text-align: center;
        }

        .header-right {
            flex: 1;
            text-align: center;
        }

        .header-logo img {
            height: 70px;
            width: auto;
            margin-bottom: 10px;
        }

        .logo-placeholder {
            width: 70px;
            height: 70px;
            background: #f8f9fa;
            border: 2px solid #0f766e;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 10px;
            color: #0f766e;
            font-weight: bold;
            border-radius: 5px;
        }

        .ministry-info {
            font-size: 11px;
            color: #0f766e;
            font-weight: bold;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .direction-info {
            font-size: 9px;
            color: #666;
            line-height: 1.1;
            margin: 1px 0;
        }

        .republic-info {
            font-size: 14px;
            font-weight: bold;
            color: #0f766e;
            margin-bottom: 8px;
        }

        .motto {
            font-size: 11px;
            font-style: italic;
            color: #666;
        }

        /* Titre principal - Garde la couleur */
        h1 {
            background: #0f766e;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0;
            border-radius: 5px;
        }

        /* Métadonnées du rapport - Simplifié pour PDF */
        .meta {
            background: white;
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .meta-item {
            text-align: center;
            border: 1px solid #000;
            padding: 10px;
        }

        .meta-label {
            font-weight: bold;
            color: #000;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .meta-value {
            font-size: 12px;
            color: #000;
            font-weight: bold;
            margin-top: 8px;
        }

        /* Sections des médicaments - Noir et blanc */
        .medication-section {
            margin-bottom: 35px;
            page-break-inside: avoid;
            border: 3px solid #000;
            overflow: hidden;
        }

        .medication-title {
            background: #000;
            color: white;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .medication-number {
            background: #333;
            color: white;
            padding: 3px 8px;
            margin-right: 10px;
            font-size: 12px;
            border: 1px solid #000;
        }

        /* Tableaux de données - Noir et blanc */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 0;
            border: 2px solid #000;
        }

        .data-table th,
        .data-table td {
            border: 2px solid #000;
            padding: 12px 15px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background: #000;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            width: 40%;
        }

        .data-table td {
            background: white;
            color: #000;
            font-weight: bold;
        }

        .data-table tr:nth-child(even) td {
            background: #f5f5f5;
        }

        /* Mise en évidence des valeurs - Noir et blanc */
        .highlight-value {
            background: #ddd;
            padding: 3px 8px;
            border: 2px solid #000;
            font-weight: bold;
            color: #000;
            display: inline-block;
        }

        .critical-value {
            background: #000;
            padding: 3px 8px;
            border: 2px solid #000;
            font-weight: bold;
            color: white;
            display: inline-block;
        }

        .positive-value {
            background: white;
            padding: 3px 8px;
            border: 2px solid #000;
            font-weight: bold;
            color: #000;
            display: inline-block;
        }

        /* Numérotation des pages */
        .page-number {
            position: fixed;
            bottom: 1cm;
            right: 50%;
            transform: translateX(50%);
            font-size: 12px;
            font-weight: bold;
            color: #000;
            background: white;
            padding: 5px 15px;
            border: 2px solid #000;
        }

        /* Pied de page */
        .document-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 3px solid #000;
            font-size: 10px;
            color: #000;
            page-break-inside: avoid;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 30px;
            align-items: center;
            margin-bottom: 15px;
        }

        .qr-section {
            text-align: center;
            border: 2px solid #000;
            padding: 10px;
        }

        .qr-section canvas {
            margin-bottom: 8px;
            border: 2px solid #000;
        }

        .qr-info {
            font-size: 8px;
            color: #000;
            line-height: 1.2;
            font-weight: bold;
        }

        .report-info {
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
        }

        .report-info h4 {
            font-size: 12px;
            color: #000;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .report-detail {
            margin: 5px 0;
            font-size: 9px;
            font-weight: bold;
            color: #000;
        }

        .signature-section {
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
        }

        .signature-section h4 {
            font-size: 11px;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .signature-line {
            border-bottom: 2px solid #000;
            width: 150px;
            margin: 25px auto 8px;
        }

        .signature-details {
            font-size: 9px;
            color: #000;
            line-height: 1.2;
            font-weight: bold;
        }

        /* Responsive pour impression */
        @media print {
            body {
                padding: 0;
                font-size: 10px;
            }
            
            .medication-section {
                page-break-inside: avoid;
            }
            
            .data-table th,
            .data-table td {
                padding: 8px 10px;
                font-size: 10px;
            }
            
            .page-number {
                display: none; /* Le @page counter gère déjà cela */
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body data-date="{{ date('d/m/Y') }}">
    <!-- En-tête officiel -->
    <div class="header">
        <div class="header-left">
            <div class="header-logo">
                <img src="{{ asset('images/pnlp3.jpg') }}" alt="Logo PNLP" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                <img src="{{ public_path('assets/logo.png') }}" alt="Armoiries Togo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display: none;">ARMOIRIES<br>TOGO</div>
            </div>
            <div class="republic-info">RÉPUBLIQUE TOGOLAISE</div>
            <div class="motto">Travail - Liberté - Patrie</div>
        </div>
    </div>

    <!-- Titre principal -->
    <h1>RAPPORT DE CONSOMMATION</h1>

    <!-- Métadonnées -->
    <div class="meta">
        <div class="meta-item">
            <div class="meta-label">Période</div>
            <div class="meta-value">{{ $conso->periode->nom ?? 'Période inconnue' }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Formation Sanitaire</div>
            <div class="meta-value">{{ $conso->formation_sanitaire->nom ?? 'Formation inconnue' }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Acteur</div>
            <div class="meta-value">{{ $conso->acteur ?? 'Acteur inconnu' }}</div>
        </div>
    </div>

    <!-- Boucle des médicaments -->
    @foreach ($consommations as $consommation)
    <div class="medication-section">
        <div class="medication-title">
            <span class="medication-number">{{ $loop->iteration }}</span>
            {{ $consommation->medicament->nom }}
        </div>
        <table class="data-table">
            <tr>
                <th>Stock début de période</th>
                <td>
                    @if($consommation->qte_dispo_deb_periode > 0)
                        <span class="positive-value">{{ number_format($consommation->qte_dispo_deb_periode, 0, ',', ' ') }}</span>
                    @else
                        <span class="critical-value">{{ number_format($consommation->qte_dispo_deb_periode, 0, ',', ' ') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Quantité reçue</th>
                <td>{{ number_format($consommation->qte_recu, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <th>Quantité utilisée</th>
                <td>
                    @if($consommation->qte_utilisee > 0)
                        <span class="highlight-value">{{ number_format($consommation->qte_utilisee, 0, ',', ' ') }}</span>
                    @else
                        {{ number_format($consommation->qte_utilisee, 0, ',', ' ') }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nombre de bénéficiaires</th>
                <td>{{ number_format($consommation->nb_beneficiaire, 0, ',', ' ') }} patients</td>
            </tr>
            <tr>
                <th>Médicaments périmés</th>
                <td>
                    @if($consommation->perimee > 0)
                        <span class="critical-value">{{ number_format($consommation->perimee, 0, ',', ' ') }}</span>
                    @else
                        <span class="positive-value">{{ number_format($consommation->perimee, 0, ',', ' ') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Pertes et avariées</th>
                <td>
                    @if($consommation->perte_avarie > 0)
                        <span class="critical-value">{{ number_format($consommation->perte_avarie, 0, ',', ' ') }}</span>
                    @else
                        <span class="positive-value">{{ number_format($consommation->perte_avarie, 0, ',', ' ') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Retour CAMEG</th>
                <td>{{ number_format($consommation->qte_retour_cameg, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <th>Jours de rupture</th>
                <td>
                    @if($consommation->nb_jour_rupture > 0)
                        <span class="critical-value">{{ $consommation->nb_jour_rupture }} jours</span>
                    @else
                        <span class="positive-value">{{ $consommation->nb_jour_rupture }} jours</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Stock de sécurité</th>
                <td>{{ number_format($consommation->stock_securite, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <th>CMM ajustée</th>
                <td>
                    @if($consommation->cmma)
                        <span class="highlight-value">{{ number_format($consommation->cmma, 2, ',', ' ') }} /mois</span>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th>Commande prévue trimestre suivant</th>
                <td>{{ number_format($consommation->cmd_trim_svt, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <th>Quantité accordée</th>
                <td>
                    @if($consommation->qte_accordee !== null)
                        <span class="highlight-value">{{ number_format($consommation->qte_accordee, 0, ',', ' ') }}</span>
                    @else
                        <span style="color: #333; font-style: italic; font-weight: normal;">En attente de validation</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endforeach

    <!-- Pied de page avec QR Code et informations -->
    <div class="document-footer">
        <div class="footer-grid">
            <!-- Section QR Code -->
            <div class="qr-section">
                <canvas id="qrcode" width="80" height="80"></canvas>
                <div class="qr-info">
                    <strong>ID Rapport:</strong><br>
                    RPT-{{ date('Y-m-d-His') }}<br>
                    <strong>Utilisateur:</strong><br>
                    {{ Auth::user()->email ?? 'system@cms-vidosa.tg' }}
                </div>
            </div>
            
            <!-- Informations du rapport -->
            <div class="report-info">
                <h4>Informations de génération</h4>
                <div class="report-detail"><strong>Rapport généré le :</strong> {{ date('d/m/Y à H:i') }}</div>
                <div class="report-detail"><strong>Créateur :</strong> {{ Auth::user()->name ?? Auth::user()->email ?? 'Système' }}</div>
                <div class="report-detail"><strong>Référence :</strong> RPT-{{ date('Y-m-d-His') }}-{{ \Illuminate\Support\Str::random(6) }}</div>
                <div class="report-detail"><strong>Total médicaments :</strong> {{ count($consommations) }}</div>
                <div class="report-detail"><strong>Statut :</strong> En attente de validation</div>
            </div>
            
            <!-- Section signatures -->
            <div class="signature-section">
                <h4>Validation</h4>
                <div class="signature-line"></div>
                <div class="signature-details">
                    Responsable Pharmacie<br>
                    Date: {{ date('d/m/Y') }}
                </div>
                <div class="signature-line" style="margin-top: 20px;"></div>
                <div class="signature-details">
                    Directeur Formation<br>
                    Date: ___/___/___
                </div>
            </div>
        </div>
    </div>

    <script>
        // Génération du code QR avec les données du rapport
        window.onload = function() {
            const qrData = JSON.stringify({
                id_rapport: 'RPT-{{ date("Y-m-d-His") }}',
                id_consommation: '{{ $conso->id ?? "N/A" }}',
                id_user: '{{ Auth::user()->id ?? "system" }}',
                formation_sanitaire: '{{ $conso->formation_sanitaire->nom ?? "Formation inconnue" }}',
                periode: '{{ $conso->periode->nom ?? "Période inconnue" }}',
                date_creation: '{{ date("Y-m-d") }}',
                nb_medicaments: {{ count($consommations) }},
                type: 'rapport_consommation_medicaments'
            });
            
            QRCode.toCanvas(document.getElementById('qrcode'), qrData, {
                width: 80,
                height: 80,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                },
                errorCorrectionLevel: 'M'
            }, function (error) {
                if (error) {
                    console.error('Erreur génération QR Code:', error);
                    // Fallback: afficher un message à la place du QR code
                    document.getElementById('qrcode').style.display = 'none';
                    const fallback = document.createElement('div');
                    fallback.innerHTML = '<div style="border: 2px solid #000; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 10px; text-align: center; color: #000; font-weight: bold;">QR<br>CODE</div>';
                    document.getElementById('qrcode').parentNode.appendChild(fallback);
                }
            });
        };
    </script>
</body>
</html>