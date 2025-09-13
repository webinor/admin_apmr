<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            color: #000; 
            margin: 20px;
        }

        /* LOGOS */
        .logos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .logos img {
            max-width: 80px;
            height: auto;
        }

        /* META INFO */
        .meta-info {
            text-align: center;
            border-bottom: 3px solid #007BFF;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .meta-info h2, .meta-info h3, .meta-info p {
            margin: 3px 0;
            text-transform: uppercase;
        }

        /* TABLEAU */
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: center; 
        }
        th { 
            background-color: #005eff; /* Bleu électrique */
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }
        td { 
            text-transform: uppercase; 
        }
        tbody tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        .totals td { 
            font-weight: bold; 
            background-color: #f0f8ff; 
        }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 3px solid #007BFF; /* bleu électrique */
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- LOGOS -->
<!-- LOGOS -->
<table style="width: 100%; margin-bottom: 10px; border: none; border-collapse: collapse;">
    <tr>
        <td style="text-align: left; border: none;">
            <img src="{{ asset('images/LOGO_CAMEROUN_ASSIST.png') }}" style="max-width:80px;" alt="Logo CAS">
        </td>
        <td style="text-align: right; border: none;">
            <img src="{{ $companyImage }}" style="max-width:80px;" alt="Logo Client">
        </td>
    </tr>
</table>


    <!-- META INFO -->
    <div class="meta-info">
        <h2>{{ strtoupper($companyName) }}</h2>
        <h3>MOIS DE {{ strtoupper($month) }} {{ $year }}</h3>
        <p>ANNEXÉ À LA FACTURE</p>
        <p>PÉRIODE DU {{ $dateDebut }} AU {{ $dateFin }}</p>
    </div>

    <!-- TABLEAU -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>N° Feuille de mission</th>
                <th>Bénéficiaire</th>
                <th>Débarquement / Embarquement</th>
                <th>N° de vol</th>
                @foreach($wheelChairTypes as $type)
                    <th>{{ strtoupper($type) }}</th>
                @endforeach
                <th>Nb d’agents</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lines as $line)
                <tr>
                    <td>{{ strtoupper($line['#']) }}</td>
                    <td>{{ strtoupper($line['date']) }}</td>
                    <td>{{ strtoupper($line['mission']) }}</td>
                    <td>{{ strtoupper($line['beneficiary']) }}</td>
                    <td>{{ strtoupper($line['flight_type']) }}</td>
                    <td>{{ strtoupper($line['flight_number']) }}</td>
                    @foreach($wheelChairTypes as $type)
                        <td>{{ $line['chairs'][$type] ?? 0 }}</td>
                    @endforeach
                    <td>{{ $line['nb_agents'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals">
                <td colspan="6">TOTAL</td>
                @foreach($totals as $total)
                    <td>{{ $total }}</td>
                @endforeach
                <td>{{ $totalAgents }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
