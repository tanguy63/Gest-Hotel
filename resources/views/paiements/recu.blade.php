
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu {{ $paiement->reference }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f1f5f9;
            color: #1e293b;
            font-family: Arial, Helvetica, sans-serif;
        }

        .actions {
            max-width: 850px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-print {
            background: #2457A7;
            color: white;
        }

        .btn-back {
            background: white;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .receipt {
            max-width: 850px;
            margin: auto;
            padding: 45px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 25px;
            border-bottom: 3px solid #2457A7;
        }

        .hotel-name {
            color: #2457A7;
            font-size: 25px;
            font-weight: 800;
            margin: 0 0 10px;
            text-transform: uppercase;
        }

        .hotel-info {
            color: #64748b;
            font-size: 13px;
            line-height: 1.8;
        }

        .receipt-title {
            text-align: right;
        }

        .receipt-title h1 {
            margin: 0;
            color: #2457A7;
            font-size: 27px;
        }

        .receipt-title p {
            margin: 8px 0;
            font-size: 13px;
            color: #64748b;
        }

        .reference {
            display: inline-block;
            margin-top: 8px;
            padding: 8px 12px;
            background: #eff6ff;
            color: #2457A7;
            border-radius: 6px;
            font-weight: bold;
            font-size: 13px;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            margin: 0 0 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #2457A7;
            font-size: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 30px;
        }

        .info-label {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 12px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            overflow-wrap: anywhere;
        }

        .amount-box {
            margin-top: 30px;
            padding: 25px;
            text-align: center;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            background: #eff6ff;
        }

        .amount-label {
            color: #475569;
            font-size: 14px;
        }

        .amount {
            margin-top: 10px;
            color: #2457A7;
            font-size: 32px;
            font-weight: 800;
        }

        .status {
            display: inline-block;
            margin-top: 12px;
            padding: 7px 14px;
            border-radius: 30px;
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
            font-weight: bold;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 45px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            line-height: 1.8;
        }

        .signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 45px;
        }

        .signature div {
            width: 220px;
            padding-top: 12px;
            border-top: 1px solid #94a3b8;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .receipt {
                padding: 22px;
            }

            .header {
                flex-direction: column;
            }

            .receipt-title {
                text-align: left;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .amount {
                font-size: 25px;
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                padding: 0;
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .actions {
                display: none !important;
            }

            .receipt {
                max-width: none;
                margin: 0;
                padding: 15px;
                border: none;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <div class="actions">
        <a href="{{ route('reservations.show', $paiement->reservation) }}"
           class="btn btn-back">
            ← Retour à la réservation
        </a>

        <button onclick="window.print()" class="btn btn-print">
            Imprimer le reçu
        </button>
    </div>

    <main class="receipt">

        <header class="header">

            <div>
                <h2 class="hotel-name">
                    {{ $etablissement->nom ?? config('app.name') }}
                </h2>

                <div class="hotel-info">
                    @if($etablissement->adresse ?? false)
                        {{ $etablissement->adresse }}<br>
                    @endif

                    @if($etablissement->telephone ?? false)
                        Téléphone : {{ $etablissement->telephone }}<br>
                    @endif

                    @if($etablissement->email ?? false)
                        Email : {{ $etablissement->email }}
                    @endif
                </div>
            </div>

            <div class="receipt-title">
                <h1>REÇU DE PAIEMENT</h1>
                <p>Document justificatif d'encaissement</p>

                <div class="reference">
                    {{ $paiement->reference }}
                </div>
            </div>

        </header>

        <section class="section">

            <h2>Informations du client</h2>

            <div class="info-grid">

                <div>
                    <span class="info-label">Nom du client</span>
                    <span class="info-value">
                        {{ $paiement->reservation->client->nom_affichage ?? 'Client indisponible' }}
                    </span>
                </div>

                <div>
                    <span class="info-label">Téléphone</span>
                    <span class="info-value">
                        {{ $paiement->reservation->client->telephone ?? 'Non renseigné' }}
                    </span>
                </div>

                <div>
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        {{ $paiement->reservation->client->email ?? 'Non renseigné' }}
                    </span>
                </div>

                <div>
                    <span class="info-label">Référence réservation</span>
                    <span class="info-value">
                        {{ $paiement->reservation->reference }}
                    </span>
                </div>

            </div>

        </section>

        <section class="section">

            <h2>Détails du paiement</h2>

            <div class="info-grid">

                <div>
                    <span class="info-label">Date du paiement</span>
                    <span class="info-value">
                        {{ $paiement->date_paiement->format('d/m/Y à H:i') }}
                    </span>
                </div>

                <div>
                    <span class="info-label">Mode de paiement</span>
                    <span class="info-value">
                        @php
                            $modes = [
                                'especes' => 'Espèces',
                                'mobile_money' => 'Mobile Money',
                                'carte_bancaire' => 'Carte bancaire',
                                'virement' => 'Virement bancaire',
                                'cheque' => 'Chèque',
                            ];
                        @endphp

                        {{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                    </span>
                </div>

                <div>
                    <span class="info-label">Référence de transaction</span>
                    <span class="info-value">
                        {{ $paiement->reference_transaction ?: 'Non renseignée' }}
                    </span>
                </div>

                <div>
                    <span class="info-label">Chambre</span>
                    <span class="info-value">
                        {{ $paiement->reservation->chambre->numero ?? 'Non renseignée' }}
                    </span>
                </div>

            </div>

        </section>

        <div class="amount-box">

            <div class="amount-label">
                Montant du paiement
            </div>

            <div class="amount">
                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
            </div>

            @if($paiement->statut === 'valide')
                <span class="status">PAIEMENT VALIDÉ</span>
            @else
                <span class="status status-cancelled">PAIEMENT ANNULÉ</span>
            @endif

        </div>

        @if($paiement->notes)
            <section class="section">
                <h2>Notes</h2>
                <p>{{ $paiement->notes }}</p>
            </section>
        @endif

        @if($paiement->motif_annulation)
            <section class="section">
                <h2>Motif d'annulation</h2>
                <p>{{ $paiement->motif_annulation }}</p>
            </section>
        @endif

        <div class="signature">
            <div>
                Signature et cachet de l'établissement
            </div>
        </div>

        <footer class="footer">
            Merci pour votre confiance.<br>
            Reçu généré le {{ now()->format('d/m/Y à H:i') }}.
            <br>
            {{ $etablissement->nom ?? config('app.name') }}
        </footer>

    </main>

</body>
</html>
