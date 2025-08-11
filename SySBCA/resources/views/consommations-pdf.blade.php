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
        }

        h1 {
            text-align: center;
            font-size: 20px;
            text-transform: uppercase;
            margin: 15px 0;
            color: #0f766e;
        }

        h4 {
            margin: 8px 0;
            color: #0f766e;
        }

        /* En-tête */
        .header {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            border-bottom: 3px solid #0f766e;
        }

        .header-left,
        .header-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 45%;
        }

        .header-logo img {
            max-height: 60px;
            margin-bottom: 5px;
        }

        .logo-placeholder {
            font-size: 10px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 4px;
            color: #888;
        }

        .ministry-info,
        .direction-info,
        .republic-info,
        .motto {
            font-size: 11px;
            line-height: 1.2;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Métadonnées */
        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px 20px;
            border: 1px solid #ddd;
            margin: 15px 20px;
            background: #f9f9f9;
        }

        .meta-item {
            flex: 1 1 30%;
        }

        .meta-label {
            font-weight: bold;
            color: #0f766e;
        }

        .meta-value {
            margin-top: 2px;
        }

        /* Médicaments */
        .medication-section {
            border: 1px solid #ddd;
            margin: 15px 20px;
            border-radius: 4px;
            overflow: hidden;
        }

        .medication-title {
            background: #0f766e;
            color: #fff;
            padding: 8px 10px;
            font-weight: bold;
        }

        .medication-number {
            background: #fff;
            color: #0f766e;
            border-radius: 50%;
            padding: 2px 7px;
            margin-right: 8px;
        }

        /* Tableaux */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: left;
        }

        .data-table th {
            background: #f0f0f0;
            color: #0f766e;
            font-size: 12px;
        }

        .data-table td {
            font-size: 12px;
        }

        /* Valeurs colorées */
        .positive-value {
            color: #0f766e;
            font-weight: bold;
        }

        .critical-value {
            color: #b91c1c;
            font-weight: bold;
        }

        .highlight-value {
            color: #b45309;
            font-weight: bold;
        }

        /* Pied de page */
        .document-footer {
            margin: 20px;
            border-top: 2px solid #0f766e;
            padding-top: 10px;
        }

        .footer-grid {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-size: 11px;
        }

        .report-info {
            flex: 1;
            font-size: 11px;
        }

        .report-detail {
            margin: 4px 0;
        }

        .status-pending {
            background: #fbbf24;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .signature-section {
            flex: 1;
            font-size: 11px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 20px;
            width: 100%;
            margin-top: 10px;
        }

        /* Impression */
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
            }

            .status-pending {
                background: none;
                color: #000;
                border: 1px solid #000;
            }
        }
    </style>
</head>

<body>
    <!-- En-tête officiel -->
    <div class="header">
        <div class="header-left">
            <div class="header-logo">
                <img src="{{ asset('images/pnlp3.jpg') }}" alt="Logo PNLP"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                    <span class="positive-value">{{ number_format($consommation->qte_dispo_deb_periode, 0, ',', ' ')
                        }}</span>
                    @else
                    <span class="critical-value">{{ number_format($consommation->qte_dispo_deb_periode, 0, ',', ' ')
                        }}</span>
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
                    <span style="color: #666; font-style: italic;">En attente de validation</span>
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
                   <img src="data:image/png;base64,{{ $qrCode }}" 
             alt="QR Code" 
             style="width: 50px; height: 50px; margin-top: 10px;">
                </div>
            </div>

            <!-- Informations du rapport -->
            <div class="report-info">
                <h4>Informations de génération</h4>
                <div class="report-detail"><strong>Rapport généré le :</strong> {{ date('d/m/Y à H:i') }}</div>
                <div class="report-detail"><strong>Créateur :</strong> {{ Auth::user()->name ?? Auth::user()->email ??
                    'Système' }}</div>
                <div class="report-detail"><strong>Référence :</strong> RPT-{{ date('Y-m-d-His') }}-{{
                    \Illuminate\Support\Str::random(6) }}</div>
                <div class="report-detail"><strong>Total médicaments :</strong> {{ count($consommations) }}</div>
                <div class="report-detail"><strong>Statut :</strong>
                    <span class="status-pending">En attente de validation</span>
                </div>
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
        window.onload = function () {
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
                dark: '#0f766e',
                light: '#FFFFFF'
            },
            errorCorrectionLevel: 'M'
        }, function (error) {
            if (error) {
                console.error('Erreur génération QR Code:', error);
                // Fallback: afficher un message à la place du QR code
                document.getElementById('qrcode').style.display = 'none';
                const fallback = document.createElement('div');
                fallback.innerHTML = '<div style="border: 2px solid #0f766e; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 10px; text-align: center; color: #0f766e;">QR<br>CODE</div>';
                document.getElementById('qrcode').parentNode.appendChild(fallback);
            }
        });
        };
    </script>
</body>

</html>