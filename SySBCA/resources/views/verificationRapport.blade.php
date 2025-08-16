<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de Rapport - PNLP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            border: 1px solid #e9ecef;
        }

        .header {
            background: linear-gradient(90deg, #0d5e56, #16a085);
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: 12px 12px 0 0;
            border-bottom: 3px solid #f1c40f;
        }

        .logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }

        .header-text {
            flex: 1;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .content {
            padding: 25px;
        }

        .result {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .result.success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border-left-color: #28a745;
            color: #155724;
        }

        .result.error {
            background: linear-gradient(135deg, #f8d7da, #f1aeb5);
            border-left-color: #dc3545;
            color: #721c24;
        }

        .result-icon {
            font-size: 32px;
            flex-shrink: 0;
        }

        .result.success .result-icon {
            color: #28a745;
        }

        .result.error .result-icon {
            color: #dc3545;
        }

        .result-info {
            flex: 1;
        }

        .result h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .result p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .result-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 10px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .detail-value {
            font-weight: 500;
            color: #34495e;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-success {
            background: #28a745;
            color: white;
        }

        .status-error {
            background: #dc3545;
            color: white;
        }

        .uuid-code {
            font-family: 'Courier New', monospace;
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .footer {
            background: #f8f9fa;
            padding: 12px 25px;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
            border-top: 1px solid #e9ecef;
            border-radius: 0 0 12px 12px;
        }

        /* Responsive pour écrans très petits */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
                padding: 12px 20px;
            }
            
            .content {
                padding: 20px;
            }
            
            .result {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .result-details {
                justify-content: center;
                flex-direction: column;
                gap: 8px;
            }
            
            .detail-item {
                justify-content: space-between;
                width: 100%;
            }
        }

        /* Version ultra compacte pour une ligne */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .header {
                padding: 8px 15px;
            }
            
            .header h1 {
                font-size: 14px;
            }
            
            .header p {
                font-size: 10px;
            }
            
            .content {
                padding: 15px;
            }
            
            .result {
                padding: 12px;
            }
            
            .result h2 {
                font-size: 16px;
            }
            
            .result p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/pnlp3.jpg'))) }}"
                 alt="Logo PNLP" class="logo">
            <div class="header-text">
                <h1>Vérification de Rapport</h1>
                <p>Programme National de Lutte contre le Paludisme</p>
            </div>
        </div>

        <div class="content">
            @if($verification['status'] === 'authentique')
                <div class="result success">
                    <i class="fas fa-check-circle result-icon"></i>
                    <div class="result-info">
                        <h2>Rapport Authentique</h2>
                        <p>Ce rapport a été vérifié avec succès et est authentique.</p>
                        <div class="result-details">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-user"></i> Créateur:</span>
                                <span class="detail-value">{{ $verification['Créateur'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-calendar-alt"></i> Date:</span>
                                <span class="detail-value">{{ $verification['date de creation'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-fingerprint"></i> UUID:</span>
                                <span class="detail-value uuid-code">{{ $verification['uuid'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="status-badge status-success">
                                    <i class="fas fa-check"></i> Vérifié
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="result error">
                    <i class="fas fa-times-circle result-icon"></i>
                    <div class="result-info">
                        <h2>Rapport Non Valide</h2>
                        <p>{{ $verification['message'] }}</p>
                        <div class="result-details">
                            <div class="detail-item">
                                <span class="status-badge status-error">
                                    <i class="fas fa-times"></i> Non vérifié
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-info-circle"></i> Conseil:</span>
                                <span class="detail-value">Vérifiez le token ou contactez l'émetteur</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="footer">
            <strong>Ministère de la Santé et de l'Hygiène Publique</strong> • 
            Programme National de Lutte contre le Paludisme © {{ date('Y') }}
        </div>
    </div>
</body>
</html>