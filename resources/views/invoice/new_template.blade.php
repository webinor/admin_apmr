<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { background: #e6f0fa; padding: 10px; border-radius: 8px; }
        .header table { width: 100%; }
        .header img { max-height: 60px; }
        h1 { margin: 0; font-size: 20px; color: #0056a6; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f4f8fc; }
        .totaux td { font-weight: bold; }
        .footer { margin-top: 30px; font-size: 11px; text-align: center; color: #555; }
        .bank { margin-top: 20px; font-size: 12px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    @if($invoice->logo_provider)
                        <img src="{{ $invoice->logo_provider }}" alt="Provider Logo">
                    @endif
                </td>
                <td align="center">
                    <h1>FACTURE N° {{ $invoice->number }}</h1>
                    <p>Date : {{ $invoice->date }}</p>
                    <p>Aéroport : {{ $invoice->airport }}</p>
                    <p>Mois : {{ $invoice->month }}</p>
                </td>
                <td align="right">
                    @if($invoice->logo_customer)
                        <img src="{{ $invoice->logo_customer }}" alt="Customer Logo">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Infos Client & Prestataire -->
    <table style="margin-top:20px;">
        <tr>
            <td width="50%">
                <strong>Prestataire :</strong><br>
                {{ $invoice->provider['name'] }}<br>
                {{ $invoice->provider['address'] }}<br>
                {{ $invoice->provider['po_box'] }}<br>
                Tél : {{ $invoice->provider['phone'] }}<br>
                Email : {{ $invoice->provider['email'] }}<br>
                Site : {{ $invoice->provider['website'] }}
            </td>
            <td width="50%">
                <strong>Client :</strong><br>
                {{ $invoice->customer['name'] }}<br>
                {{ $invoice->customer['address'] }}<br>
                BP {{ $invoice->customer['po_box'] }} - {{ $invoice->customer['city'] }}<br>
                RCCM : {{ $invoice->customer['rc'] }}<br>
                ID Unique : {{ $invoice->customer['unique_id'] }}<br>
                Tél : {{ $invoice->customer['phone'] }}<br>
                Email : {{ $invoice->customer['email'] }}
            </td>
        </tr>
    </table>

    <!-- Tableau des Articles -->
    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Quantité</th>
                <th>PU HT</th>
                <th>Total HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item['label'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ number_format($item['pu'], 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($item['amount'], 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totaux -->
    <table class="totaux" style="margin-top:15px;">
        <tr>
            <td align="right" width="70%">TOTAL HT :</td>
            <td>{{ number_format($invoice->total_ht, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td align="right">TVA :</td>
            <td>{{ number_format($invoice->tva, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td align="right">TOTAL TTC :</td>
            <td>{{ number_format($invoice->ttc, 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td colspan="2"><em>Arrêté la présente facture à la somme de : {{ $invoice->amount_letters }}</em></td>
        </tr>
    </table>

    <!-- Coordonnées bancaires -->
    <div class="bank">
        <strong>Coordonnées Bancaires :</strong><br>
        Banque : {{ $invoice->bank['bank_full'] }}<br>
        Code Banque : {{ $invoice->bank['code_banque'] }} - Guichet : {{ $invoice->bank['guichet'] }}<br>
        Compte : {{ $invoice->bank['compte'] }}<br>
        IBAN : {{ $invoice->bank['iban'] }}<br>
        BIC : {{ $invoice->bank['bic'] }}
    </div>

    <!-- Footer -->
    <div class="footer">
        Merci de votre confiance.<br>
        Facture générée le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
