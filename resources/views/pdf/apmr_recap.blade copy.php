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
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
        }
        .header-center { 
            text-align: center; 
            flex: 1; 
        }
        .logo { 
            width: 80px; 
            height: auto; 
        }
        .header h2, .header h3, .header p { 
            margin: 3px 0; 
        }

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
        .totals td { 
            font-weight: bold; 
            background-color: #f0f8ff; 
        }

        .footer { 
            margin-top: 30px; 
            font-size: 10px; 
            text-align: center; 
            color: #555; 
        }

        /* Amélioration UX : zébrage des lignes */
        tbody tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
    </style>
</head>
<body>

    <div class="header">
        <!-- Logo entreprise -->
        <div>
            <img src="{{ asset("images/LOGO_CAMEROUN_ASSIST.png") }}" class="logo" alt="Logo Entreprise">
        </div>

        <!-- Texte centré -->
        <div class="header-center">
            <h2>{{ strtoupper($companyName) }}</h2>
            <h3>MOIS DE {{ strtoupper($month) }} {{ $year }}</h3>
            <p>ANNEXÉ À LA FACTURE</p>
            <p>PÉRIODE DU {{ $dateDebut }} AU {{ $dateFin }}</p>
        </div>

        <!-- Logo client -->
        @if ($companyImage)
        <div>
            <img src="{{ $companyImage }}" class="logo" alt="Logo Client">
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
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
                <td colspan="5">TOTAL</td>
                @foreach($totals as $total)
                    <td>{{ $total }}</td>
                @endforeach
                <td>{{ $totalAgents }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Généré le {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>
