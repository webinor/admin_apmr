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
            /* margin: 15px 0; */
            width: 100%;
            border-collapse: collapse;
             margin-left: auto;
    margin-right: 0;
    width: 30%; 
    font-size: 10px;

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
            margin-top: 10px;
        }

        .watermark {
    position: fixed;
    top: 40%;
    left: 20%;
    font-size: 100px;
    color: rgba(0,0,0,0.08);
    transform: rotate(-30deg);
    z-index: 0;
}

.footer-info {
    font-size: 10px;
    text-align: center;
    margin-top: 15px;
}
    </style>
</head>
<body>
    @if($watermark ?? false)
    <div class="watermark">APERÇU</div>
@endif
    <!-- Header -->
    <div class="header">
        <table width="100%">
            <tr>
                {{-- <td align="right"><img src="{{ $invoice->logo_customer }}" alt="Logo 2"></td> --}}
                <td align="left"><img src="{{ $invoice->logo_iso }}" alt="Logo 2"></td>
                <td align="right"><img src="{{ $invoice->logo_provider }}" alt="Logo 1"></td>
            </tr>
        </table>
    </div>

    {{-- <div class="title">FACTURE</div> --}}

    <!-- Infos facture -->
    {{-- <table class="details">
        <tr>
            <td><strong>Facture n° :</strong> {{ $invoice->number }}</td>
            <td><strong>Du :</strong> {{ $invoice->date }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Réf :</strong> {{ $invoice->reference }}</td>
        </tr>
    </table> --}}

    <table class="details">
       {{-- {!! displayInvoiceRow('Facture n°', $invoice->number) !!} --}}
{{-- {!! displayInvoiceRow('Du', $invoice->date) !!} --}}

{!! displayInvoiceRow($invoice->city_name." le", $invoice->date_of_day , "" , 'both') !!}
{!! displayInvoiceRow("", $invoice->airport , "" , 'value') !!}
{!! displayInvoiceRow("", $invoice->reference , "" , 'none') !!}
{!! displayInvoiceRow('', $invoice->po_box , ":" , 'none') !!}
{!! displayInvoiceRow('', $invoice->city_name , "" , 'none') !!}
{!! displayInvoiceRow('NIU', $invoice->unique_id,":",'none') !!}
{!! displayInvoiceRow('RC', $invoice->rc , ":", 'none') !!}
{!! displayInvoiceRow('V/Réf', $invoice->city_name, ":" , 'both') !!}
    </table>


    <table  style="margin-top:5px; width:50%; border-collapse:collapse;">
    <tr>
        <td><strong>Facture n°</strong></td>
        <td style="border:1px solid #000; padding:6px; text-align:center;">
            {{ $invoice->number }}
        </td>
        <td style="padding-left:10px;"><strong>du</strong></td>
        <td style="border:1px solid #000; padding:6px; text-align:center;">
            {{ $invoice->end }}
        </td>
    </tr>
</table>


    <!-- Mission -->
    <div class="mission">
        Assistance aux passagers à mobilité réduite à l’Aéroport International de {{ $invoice->airport }}
         {{ $invoice->period }}
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

               @if (!$item['is_mensual_fee'])
                   
                <tr>
                    <td>{{ $item['label'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td class="right">{{ number_format((float)$item['pu'], 0, ',', ' ') }}</td>
                    <td class="right">{{ number_format((float)$item['amount'], 0, ',', ' ') }}</td>
                </tr>

               @else

                <tr>
                    <td colspan="3" >{{ $item['label'] }}</td>
                    <td class="right">{{ number_format((float)$item['amount'], 0, ',', ' ') }}</td>
                </tr>
                   
               @endif


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
            <td class="right"><strong>{{ number_format($invoice->ttc, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
    </table>

    <p style="border:1px solid #000; padding:5px; font-weight:bold;"><em>Facture arrêtée à la somme de FCFA {{ $invoice->amount_letters }}</em></p>

    <!-- Conditions -->
    <div class="conditions">
        <strong>Conditions de paiement :</strong> En espèces, par chèque ou virement sur le compte de <br>
        <strong>{{ $invoice->bank_name }}</strong> <br>
        Banque : {{ $invoice->bank }} <br>
        Code banque : {{ $invoice->code_banque }} - Guichet : {{ $invoice->guichet }} <br>
        N° Cpte : {{ $invoice->compte }} - IBAN : {{ $invoice->iban }} - BIC : {{ $invoice->bic }}
    </div>


    <table width="100%" class="footer-info" style="margin-top:70px; font-size:10px; border-collapse:collapse;">
       <tr>
      
        <td style="padding:5px; text-align:center;">
            Tél. H24 : (237) 233 42 14 14 • 233 42 15 15 • 233 42 20 20 • 233 42 48 91 • 233 43 91 91<br>
            Fax : (237) 233 42 00 79 • 233 43 30 30<br>
            Email : administration@assistance.com • commercial@assistance.com<br>
            www.cwas-assistance.com
        </td>
    </tr>
    <tr>
        <td  style="padding-top:6px; text-align:center;">
            S.A. au capital de <strong>100 000 000 FCFA</strong> – RC/DLA/1987/B/04790 – CM/Emi. 56133/01 – NUI : M12880000469U<br>
            Autorisation Arrêté Ministériel N° <strong>1992/MINATD/SG/DOST/SDSP</strong> du 07 juin 2010
        </td>
    </tr>

</table>


  <table width="25%" class="footer-info"
       style="margin:15px auto; text-align:center; font-size:10px; border-collapse:collapse;">
    <tr>
        <td style="border:1px solid #000; padding:5px; font-weight:bold;">
            Certificat N° Qual/2003/1357
        </td>
    </tr>
</table>


   
</body>
</html>
