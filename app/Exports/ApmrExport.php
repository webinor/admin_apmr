<?php

namespace App\Exports;

use App\Models\Apmr;
use App\Models\AssistanceAgent;
use App\Models\Company;
use App\Models\Operations\Assistance;
use App\Models\Operations\AssistanceLine;
use App\Models\WheelChair;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApmrExport implements FromArray//FromCollection//, WithMapping, WithHeadings
{


    protected $filters;
    protected $wheelChairTypes;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array():array
    {

      return $this->filter();
      
    }

    public function filter()
    {

        $query = AssistanceLine::query()
        ->with([
            'assistance.ground_agent.company.wheel_chairs',
            'assistance.registrator',
            'assistance_agent.city',
            //'wheel_chair',
        ])
    ->whereHas('assistance.signature') // s'assure que l'assistance a une signature
    ->whereHas('assistance', function($q) {

        // Compagnie
        if (!empty($this->filters['compagnie'])) {
            $q->whereHas('ground_agent.company', function($qry) {
                $qry->whereCode($this->filters['compagnie']);
            });
        }

        // Période
        if (!empty($this->filters['date_debut'])) {
            $q->whereDate('created_at', '>=', $this->filters['date_debut']);
        }
        if (!empty($this->filters['date_fin'])) {
            $q->whereDate('created_at', '<=', $this->filters['date_fin']);
        }

        // Enregistré par
        if (!empty($this->filters['user'])) {
            $q->whereHas('registrator', function($qry) {
                $qry->whereCode($this->filters['user']);
            });
        }

        // Déjà facturées
        if (!empty($this->filters['justificatifs'])) {
            $q->where('is_invoiced', true);
        }

        // Min / Max prix
        if (!empty($this->filters['min-price'])) {
            $q->where('total', '>=', $this->filters['min-price']);
        }
        if (!empty($this->filters['max-price'])) {
            $q->where('total', '<=', $this->filters['max-price']);
        }
    });

// Filtre sur Agent (ligne d'assistance)
if (!empty($this->filters['agent'])) {
    $query->whereHas('assistance_agent', function($qry) {
        $qry->whereCode($this->filters['agent']);
    });
}

// Filtre sur Ville (ligne d'assistance)
if (!empty($this->filters['city'])) {
    $query->whereHas('assistance_agent.city', function($qry) {
        $qry->whereCode($this->filters['city']);
    });
}

// Filtre sur Type de chaise (ligne d'assistance)
if (!empty($this->filters['wheel_chair'])) {
    $query->whereHas('wheel_chair', function($qry) {
        $qry->whereCode($this->filters['wheel_chair']);
    });
}


$filtered = $query->get();

//dd($filtered);


// Récupérer tous les wheel_chairs de toutes les compagnies
$wheelChairTypes = $filtered->flatMap(function($line) {
    return $line->assistance->ground_agent->company->wheel_chairs->pluck('slug');
})->unique()->values()->toArray();

$this->wheelChairTypes = $wheelChairTypes;



 // construire le tableau final
 $array = [];

 

 // Lignes d'information sur les filtres
$meta = [];

$meta[] = ['', '', '', ''];

// Compagnie(s)
if (!empty($this->filters['compagnie'])) {
    $companies = Company::whereIn('code', (array) $this->filters['compagnie'])->pluck('name')->join(' / ');
    $meta[] = ['', '', '', 'Compagnie : ' . $companies];
} else {
    $meta[] = ['', '', '', 'Compagnie(s) : Toutes les compagnies'];
}

// Période
if (!empty($this->filters['date_debut']) || !empty($this->filters['date_fin'])) {
    $start = !empty($this->filters['date_debut']) ? \Carbon\Carbon::parse($this->filters['date_debut'])->format('d/m/Y') : 'début';
    $end = !empty($this->filters['date_fin']) ? \Carbon\Carbon::parse($this->filters['date_fin'])->format('d/m/Y') : 'fin';
    $meta[] = ['', '', '', 'Période : du ' . $start . ' au ' . $end];
} else {
   // $meta[] = ['', '', '', 'Période : Toutes les périodes'];
}

// Tu peux ajouter d'autres infos si nécessaire, par ex. agent
if (!empty($this->filters['agent'])) {
   // dd($this->filters['agent']);
    $agents = AssistanceAgent::whereIn('code', (array) $this->filters['agent'])->first()->fullName();//->pluck('fullName')->join(' / ');
    $meta[] = ['', '', '', 'Agent : ' . $agents];
} else {
   // $meta[] = ['', '', '', 'Agent(s) : Tous les agents'];
}

// Ensuite tu concatènes $meta et ton tableau principal
    $array = array_merge($meta, $array);

 // première ligne = titres
 $headings = [
    '#',
    'Date',
    'Feuille de mission',
    'Beneficiaire',
    'Embarquement / Debarquement',
    '# de vol',
 ];

 $emptyLine = array_fill(0, count($headings), '');

 $array[] = [$emptyLine ,$emptyLine];

 foreach ($wheelChairTypes as $type) {
     $headings[] = $type;
 }

 // titres
$headings[] = "Nb d'agents";

 $array[] = $headings;


foreach ($filtered as $index => $line) {

   // dd($line);

    $row = [

        ++$index,
        $line->created_at->format('d/m/Y'),
        $line->assistance->reference,
        $line->beneficiary_name,
        $line->assistance->flight_type == 'départ' ? 'E' : 'D',
        $line->assistance->flight_number,
        /*$line->id,
        optional($line->assistance)->reference,
        optional($line->assistance_agent)->fullName(),
        optional($line->assistance_agent->city)->name,
        optional($line->assistance->registrator)->fullName(),
        $line->price,
        $line->description,
        $line->status,
        $line->created_at->format('Y-m-d H:i:s'),*/
    ];




   // $line3 = AssistanceLine::find(3);
  //  $wc = WheelChair::find($line3->wheel_chair_id);
    
   // dd($line3->wheel_chair_id, $wc->id);


   // $w = WheelChair::find(2);
  //  $line2 = AssistanceLine::with('wheel_chair')->whereId(2)->get();
  //  dd($line2);
  //  dd($line->wheel_chair_id, $line2->wheel_chair->id, $line->wheel_chair->slug , $w->id);
   // dd($wheelChairTypes);

    // colonnes dynamiques pour types de chaise
    foreach ($wheelChairTypes as $type) {



     //   dd($line->wheel_chair);
     //   dd($line);

        if ($line->wheel_chair->slug=="R") {
          //  dd($line->wheel_chair->slug);
        }
        $row[] = ($line->wheel_chair->slug == $type) ? 1 : 0;
    }

   // colonne finale : nombre d'agents uniques pour cette assistance
   $row[]  = $line->assistance->assistance_lines
   ->pluck('assistance_agent_id')
   ->unique()
   ->count();

    $array[] = $row;





        // ajoute ici d’autres filtres si besoin

        /*return $query->get([
            'id',
            'created_at',
            'updated_at',
            'price',
            'description',
            'status',
        ]);*/


    }

    // Indice de départ pour les colonnes dynamiques
$wheelChairStartIndex = 6; // selon ton tableau
$wheelChairCount = count($wheelChairTypes);
$wheelChairEndIndex = $wheelChairStartIndex + $wheelChairCount - 1;
    // On suppose que la colonne “Nb d’agents” est à la fin
$agentColumnIndex = count($headings) - 1;
    

   // $totals = array_fill(0, count($array[0]), '');
    $totals = array_fill(0, count($headings), '');

    $totals[5] = 'TOTAL';
    
    // Calculer le total pour chaque type de chaise
for ($i = 0; $i < $wheelChairCount; $i++) {
    $colIndex = $wheelChairStartIndex + $i;
    $sum = 0;
    for ($j = 1; $j < count($array); $j++) { // ignorer la ligne des titres
        $sum += isset($array[$j][$colIndex]) ? (int) $array[$j][$colIndex] : 0;
    }
    $totals[$colIndex] = $sum;
}

//dd($totals);
    

    // Total général des agents
$uniqueAgents = collect($filtered) // $filtered = toutes les lignes AssistanceLine
->pluck('assistance.assistance_lines.*.assistance_agent_id') // récupérer tous les agents
->flatten()
->unique()
->count();

$totals[$agentColumnIndex] = $uniqueAgents;
// ajoute la ligne total à la fin
$array[] = $totals;



    
    return $array;
}

    /*public function map($line): array
    {

       
        return [
            $line->id,
            $line->created_at,
            $line->assistance->reference,
            $line->beneficiary,
            $line->assistance->flight_type,
            $line->assistance->flight_number,
        ];
    }

    public function headings(): array
    {

        $data_filtered = $this->filter();

        $headings= [
            '#',
            'Date',
            'Feuille de mission',
            'Beneficiaire',
            'Embarquement / Debarquement',
            '# de vol',
        ];

            // ajouter les colonnes dynamiques pour chaque type de chaise
    foreach ($this->wheelChairTypes as $type) {
        $headings[] = $type;
    }


    $headings+= [
        'Description',
        'Statut',
        'Date de création',
    ];
    return $headings;
    }*/
}
