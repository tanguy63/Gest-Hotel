
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de paiement - {{ $reservation->reference }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f1f5f9;
            color: #1e293b;
            font-family: Arial, sans-serif;
        }

        .receipt {
            max-width: 850px;
            margin: auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 2px solid #2457A7;
            padding-bottom: 25px;
        }

        .header h1 {
            color: #2457A7;
            margin: 0 0 10px;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            font-size: 17px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail p {
            margin: 7px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #f1f5f9;
        }

        .total {
            margin-top: 25px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .total p {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .grand-total {
            color: #2457A7;
            font-size: 20px;
            font-weight: bold;
        }

        .valid {
            color: #15803d;
            font-weight: bold;
        }

        .cancelled {
            color: #dc2626;
            font-weight: bold;
        }

        .actions {
            max-width: 850px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
        }

        .actions a, .actions button {
            border: none;
            border-radius: 7px;
            padding: 12px 18px;
            color: white;
            background: #2457A7;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .footer {
            margin-top: 45px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .receipt {
                max-width: 100%;
                padding: 15px;
                border-radius: 0;
            }

            .actions {
                display: none;
            }

            @page {
                size: A4;
                margin: 12mm;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .receipt {
                padding: 20px;
            }

            .header {
                flex-direction: column;
            }

            .details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="actions">
    <a href="{{ route('reservations.show', $reservation) }}">
        Retour à la réservation
    </a>

    <button onclick="window.print()">
        Imprimer le reçu
    </button>
</div>

<div class="receipt">

    <div class="header">
        <div>
            <h1>{{ $etablissement->nom ?? config('app.name') }}</h1>

            @if($etablissement->adresse ?? false)
                <p>{{ $etablissement->adresse }}</p>
            @endif

            @if($etablissement->telephone ?? false)
                <p>Téléphone : {{ $etablissement->telephone }}</p>
            @endif

            @if($etablissement->email ?? false)
                <p>Email : {{ $etablissement->email }}</p>
            @endif
        </div>

        <div>
            <h2>REÇU DE PAIEMENT</h2>
            <p><strong>Réservation :</strong></p>
            <p>{{ $reservation->reference }}</p>
            <p><strong>Date :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Informations du client</h2>

        <div class="details">
            <div class="detail">
                <p>
                    <strong>Nom :</strong>
                    {{ $reservation->client->nom_affichage ?? 'Non renseigné' }}
                </p>

                <p>
                    <strong>Téléphone :</strong>
                    {{ $reservation->client->telephone ?? 'Non renseigné' }}
                </p>

                <p>
                    <strong>Email :</strong>
                    {{ $reservation->client->email ?? 'Non renseigné' }}
                </p>
            </div>

            <div class="detail">
                <p>
                    <strong>Chambre :</strong>
                    {{ $reservation->chambre->numero ?? 'Non renseignée' }}
                </p>

                <p>
                    <strong>Arrivée :</strong>
                    {{ $reservation->date_arrivee->format('d/m/Y à H:i') }}
                </p>

                <p>
                    <strong>Départ :</strong>
                    {{ $reservation->date_depart->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Historique complet des paiements</h2>

        @if($reservation->paiements->isEmpty())

            <p>Aucun paiement enregistré pour cette réservation.</p>

        @else

            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $modes = [
                            'especes' => 'Espèces',
                            'mobile_money' => 'Mobile Money',
                            'carte_bancaire' => 'Carte bancaire',
                            'virement' => 'Virement',
                            'cheque' => 'Chèque',
                        ];
                    @endphp

                    @foreach($reservation->paiements as $paiement)
                        <tr>
                            <td>{{ $paiement->reference }}</td>

                            <td>
                                {{ $paiement->date_paiement->format('d/m/Y à H:i') }}
                            </td>

                            <td>
                                {{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                            </td>

                            <td>
                                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                            </td>

                            <td>
                                @if($paiement->statut === 'valide')
                                    <span class="valid">Validé</span>
                                @else
                                    <span class="cancelled">Annulé</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif
    </div>

    <div class="total">
        <p>
            <span>Montant total de la réservation</span>
            <strong>
                {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA
            </strong>
        </p>

        <p>
            <span>Total encaissé (paiements validés)</span>
            <strong class="valid">
                {{ number_format($montantEncaisse, 0, ',', ' ') }} FCFA
            </strong>
        </p>

        <p>
            <span>Total des paiements annulés</span>
            <strong class="cancelled">
                {{ number_format($montantAnnule, 0, ',', ' ') }} FCFA
            </strong>
        </p>

        <p class="grand-total">
            <span>Reste à payer</span>
            <strong>
                {{ number_format($resteAPayer, 0, ',', ' ') }} FCFA
            </strong>
        </p>
    </div>

    <div class="footer">
        <p>Merci pour votre confiance.</p>
        <p>Document généré par GEST-HOTEL.</p>
    </div>

</div>

</body>
</html>
