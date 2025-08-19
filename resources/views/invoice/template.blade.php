<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }
        .header, .footer {
            width: 100%;
            text-align: center;
        }
        .header img {
            height: 100px;
        }
        .title {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }
        .details {
            margin: 15px 0;
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 4px;
        }
        .mission {
            border: 1px solid #000;
            padding: 8px;
            margin: 10px 0;
            font-weight: bold;
        }
        table.table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .total {
            margin-top: 10px;
            width: 100%;
        }
        .total td {
            padding: 6px;
        }
        .conditions {
            font-size: 11px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table width="100%">
            <tr>
                <td align="left"><img src="{{ $invoice->logo_provider }}" alt="Logo 1"></td>
                <td align="right"><img src="{{ $invoice->logo_customer }}" alt="Logo 2"></td>
            </tr>
        </table>
    </div>

    <div class="title">FACTURE</div>

    <!-- Infos facture -->
    <table class="details">
        <tr>
            <td><strong>Facture n° :</strong> {{ $invoice->number }}</td>
            <td><strong>Du :</strong> {{ $invoice->date }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Réf :</strong> {{ $invoice->reference }}</td>
        </tr>
    </table>

    <!-- Mission -->
    <div class="mission">
        Assistance aux passagers à mobilité réduite à l’Aéroport International de {{ $invoice->airport }}
        au courant du mois de {{ $invoice->month }}
    </div>

    <!-- Tableau -->
    <table class="table">
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Quantité</th>
                <th>PU</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item['label'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td class="right">{{ number_format((float)$item['pu'], 0, ',', ' ') }}</td>
                    <td class="right">{{ number_format((float)$item['amount'], 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totaux -->
    <table class="total">
        <tr>
            <td class="right"><strong>Total HT :</strong></td>
            <td class="right">{{ number_format($invoice->total_ht, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td class="right"><strong>TVA (19,25%) :</strong></td>
            <td class="right">{{ number_format($invoice->tva, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td class="right"><strong>Net à payer TTC :</strong></td>
            <td class="right"><strong>{{ number_format($invoice->ttc, 0, ',', ' ') }}</strong></td>
        </tr>
    </table>

    <p><em>Facture arrêtée à la somme de {{ $invoice->amount_letters }}</em></p>

    <!-- Conditions -->
    <div class="conditions">
        <strong>Conditions de paiement :</strong> En espèces, par chèque ou virement sur le compte de <br>
        <strong>{{ $invoice->bank_name }}</strong> <br>
        Banque : {{ $invoice->bank }} <br>
        Code banque : {{ $invoice->code_banque }} - Guichet : {{ $invoice->guichet }} <br>
        N° Cpte : {{ $invoice->compte }} - IBAN : {{ $invoice->iban }} - BIC : {{ $invoice->bic }}
    </div>
</body>
</html>
