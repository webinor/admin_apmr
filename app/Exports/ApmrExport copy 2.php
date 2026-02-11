<?php

namespace App\Exports;

use App\Models\Apmr;
use App\Models\AssistanceAgent;
use App\Models\Company;
use App\Models\Operations\Assistance;
use App\Models\Operations\AssistanceLine;
use App\Models\WheelChair;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OLDApmrExport implements FromArray //FromCollection//, WithMapping, WithHeadings
{
    protected $filters;
    public $wheelChairTypes;

    public $companyName;
    public $companyImage;
    public $month;
    public $year;
    public $dateDebut;
    public $dateFin;
    public $agent;

    public function __construct(array $filters)
    {
        $this->filters = $filters;

        // Compagnie : si un ID est passé, on va chercher le libellé
        if (!empty($filters["company"])) {
            $company = Company::whereCode($filters["company"])->first();
            $this->companyImage = $company->image_path
                ? asset("storage/company_images/" . $company->image_path)
                : null;
            $this->companyName = $company
                ? $company->name
                : "Toutes les compagnies";
        } else {
            $this->companyName = "Toutes les compagnies";
        }

        // Autres paramètres
        $this->month = $filters["month"] ?? now()->translatedFormat("F");
        $this->year = $filters["year"] ?? now()->year;
        $this->dateDebut = isset($filters["date_debut"])
            ? Carbon::parse($filters["date_debut"])->format("d/m/Y")
            : null;

        $this->dateFin = isset($filters["date_fin"])
            ? Carbon::parse($filters["date_fin"])->format("d/m/Y")
            : null;

        if (!empty($this->filters["agent"])) {
            // dd($this->filters['agent']);
            $agent = AssistanceAgent::whereIn(
                "code",
                (array) $this->filters["agent"]
            )
                ->first()
                ->fullName(); 
                $this->agent = "Agent : " . $agent;
                //->pluck('fullName')->join(' / ');
          //  $meta[] = ["", "", "", "Agent : " . $agent];
          //  dd($agent);
        } else {
            $this->agent = 'Agent(s) : Tous les agents';
           //  $meta[] = ['', '', '', 'Agent(s) : Tous les agent'];
        }
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->filter();
    }

    public function collectionoo()
    {
        $data = AssistanceLine::with("wheelChair")
            ->when(
                $this->filters["compagnie"] ?? null,
                fn($q, $compagnie) => $q->where("compagnie", $compagnie)
            )
            ->when(
                $this->filters["periode"] ?? null,
                fn($q, $periode) => $q->whereBetween("date", $periode)
            )
            ->get();

        // Récupération dynamique des types présents dans l’export
        /*  $this->wheelChairTypes = $data
            ->pluck('wheelChair.code')
            ->unique()
            ->values()
            ->toArray();

        return $data;*/
    }

    public function assistances() {}

    public function get_filtered()
    {
        
        // 1️⃣ Récupération des données avec préchargement complet des relations
    /*$filtered = AssistanceLine::with([
        'wheel_chair',
        'assistance.assistance_lines.assistance_agent',
        'assistance.ground_agent.company.wheel_chairs'
    ])*/

    /*$filtered = AssistanceLine::select('id','created_at' , 'assistance_agent_id' ,'assistance_id', 'code', 'wheel_chair_id' , 'beneficiary_name')->with([
        'wheel_chair:id,name,slug,code',
        'assistance:id,code,reference,ground_agent_id,flight_type,flight_number',
        'assistance_agent:id,code,first_name,last_name,city_id',
        'assistance.ground_agent:id,company_id,code',
        'assistance.ground_agent.company:id,city_id,name,code',
        'assistance.ground_agent.company.wheel_chairs:id,name,slug',
    ])*/

        $filtered = AssistanceLine::select('id' , 'assistance_agent_id' ,'assistance_id', 'wheel_chair_id')->with([
        'wheel_chair:id,name,slug',
        'assistance:id,ground_agent_id',
        'assistance_agent:id,city_id',
        'assistance.ground_agent:id,company_id',
        'assistance.ground_agent.company:id,city_id',
        'assistance.ground_agent.company.wheel_chairs:id,name,slug',
    ])
    ->whereHas('assistance.signature')
    ->when($this->filters['company'] ?? null, fn($q, $comp) => 
        $q->whereHas('assistance.ground_agent.company', fn($q2) => $q2->whereCode($comp))
    )
    // ->when($this->filters['date_debut'] ?? null, fn($q, $start) => $q->whereDate('created_at', '>=', $start))
    // ->when($this->filters['date_fin'] ?? null, fn($q, $end) => $q->whereDate('created_at', '<=', $end))
      ->when(
        ($this->filters['date_debut'] ?? null) || ($this->filters['date_fin'] ?? null),
        function ($q) {
            $q->whereHas('assistance', function ($qa) {
                if ($this->filters['date_debut'] ?? null) {
                    $qa->whereDate('created_at', '>=', $this->filters['date_debut']);
                }

                if ($this->filters['date_fin'] ?? null) {
                    $qa->whereDate('created_at', '<=', $this->filters['date_fin']);
                }
            });
        }
    )
    ->when($this->filters['agent'] ?? null, fn($q, $agent) => $q->whereHas('assistance_agent', fn($q2) => $q2->whereCode($agent)))
    ->when($this->filters['city'] ?? null, fn($q, $city) => $q->whereHas('assistance_agent.city', fn($q2) => $q2->whereCode($city)))
    ->when($this->filters['wheel_chair'] ?? null, fn($q, $wc) => $q->whereHas('wheel_chair', fn($q2) => $q2->whereCode($wc)))
    ->orderBy("id","asc")
    ->get();

        return $filtered;
    }

    public function get_filtered_chunks(int $chunkSize = 100, callable $callback)
{
    $filtered = AssistanceLine::with([
    'wheel_chair:id,slug', // seulement les champs nécessaires
    'assistance:id,reference,flight_type,flight_number,ground_agent_id',
    'assistance.ground_agent:id,company_id',
    'assistance.ground_agent.company:id,code,name'
])
    ->whereHas('assistance.signature')
    ->when($this->filters['company'] ?? null, fn($q, $comp) => 
        $q->whereHas('assistance.ground_agent.company', fn($q2) => $q2->whereCode($comp))
    )
    ->when($this->filters['date_debut'] ?? null, fn($q, $start) => $q->whereDate('created_at', '>=', $start))
    ->when($this->filters['date_fin'] ?? null, fn($q, $end) => $q->whereDate('created_at', '<=', $end))
    ->when($this->filters['agent'] ?? null, fn($q, $agent) => $q->whereHas('assistance_agent', fn($q2) => $q2->whereCode($agent)))
    ->when($this->filters['city'] ?? null, fn($q, $city) => $q->whereHas('assistance_agent.city', fn($q2) => $q2->whereCode($city)))
    ->when($this->filters['wheel_chair'] ?? null, fn($q, $wc) => $q->whereHas('wheel_chair', fn($q2) => $q2->whereCode($wc)))
    ->chunk($chunkSize, $callback);
}


    public function filter()
    {
        //dd($filtered);

        $filtered = $this->get_filtered();

        // Récupérer tous les wheel_chairs de toutes les compagnies
        $wheelChairTypes = $filtered
            ->flatMap(function ($line) {
                return $line->assistance->ground_agent->company->wheel_chairs->pluck(
                    "slug"
                );
            })
            ->unique()
            ->values()
            ->toArray();

   

            

        $this->wheelChairTypes = $wheelChairTypes;

        // construire le tableau final
        $array = [];

        // Lignes d'information sur les filtres
        $meta = [];

        $meta[] = ["", "", "", ""];

        // Compagnie(s)
        if (!empty($this->filters["compagnie"])) {
            $companies = Company::whereIn(
                "code",
                (array) $this->filters["compagnie"]
            )
                ->pluck("name")
                ->join(" / ");
            $meta[] = ["", "", "", "Compagnie : " . $companies];
        } else {
            $meta[] = ["", "", "", "Compagnie(s) : Toutes les compagnies"];
        }

        // Période
        if (
            !empty($this->filters["date_debut"]) ||
            !empty($this->filters["date_fin"])
        ) {
            $start = !empty($this->filters["date_debut"])
                ? \Carbon\Carbon::parse($this->filters["date_debut"])->format(
                    "d/m/Y"
                )
                : "début";
            $end = !empty($this->filters["date_fin"])
                ? \Carbon\Carbon::parse($this->filters["date_fin"])->format(
                    "d/m/Y"
                )
                : "fin";
            $meta[] = ["", "", "", "Période : du " . $start . " au " . $end];
        } else {
            $meta[] = ['', '', '', 'Période : Toutes les périodes'];
        }

        // Tu peux ajouter d'autres infos si nécessaire, par ex. agent
        if (!empty($this->filters["agent"])) {
            // dd($this->filters['agent']);
            $agent = AssistanceAgent::whereIn(
                "code",
                (array) $this->filters["agent"]
            )
                ->first()
                ->fullName(); //->pluck('fullName')->join(' / ');
          //  $meta[] = ["", "", "", "Agent : " . $agent];
          //  dd($agent);
        } else {
           //  $meta[] = ['', '', '', 'Agent(s) : Tous les agent'];
        }

        // Ensuite tu concatènes $meta et ton tableau principal
        $array = array_merge($meta, $array);

        // première ligne = titres
        $headings = [
            "#",
            "Date",
            "Feuille de mission",
            "Beneficiaire",
            "Embarquement / Debarquement",
            "# de vol",
        ];

        $emptyLine = array_fill(0, count($headings), "");

        $array[] = [$emptyLine, $emptyLine];

        foreach ($wheelChairTypes as $type) {
            $headings[] = $type;
        }

        // titres
        $headings[] = "Nb d'agents";

        //$array[] = $headings;

        

        foreach ($filtered as $index => $line) {
            // dd($filtered);

            $row = [
                ++$index,
                $line->created_at->format("d/m/Y"),
                $line->assistance->reference,
                $line->beneficiary_name,
                $line->assistance->flight_type == "départ" ? "E" : "D",
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

                if ($line->wheel_chair->slug == "R") {
                    //  dd($line->wheel_chair->slug);
                }
                $row[] = $line->wheel_chair->slug == $type ? 1 : 0;
            }

            // colonne finale : nombre d'agents uniques pour cette assistance
            $row[] = $line->assistance->assistance_lines
                ->pluck("assistance_agent_id")
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
        $totals = array_fill(0, count($headings), "");

        $totals[5] = "TOTAL";

        // Calculer le total pour chaque type de chaise
        for ($i = 0; $i < $wheelChairCount; $i++) {
            $colIndex = $wheelChairStartIndex + $i;
            $sum = 0;
            for ($j = 1; $j < count($array); $j++) {
                // ignorer la ligne des titres
                $sum += isset($array[$j][$colIndex])
                    ? (int) $array[$j][$colIndex]
                    : 0;
            }
            $totals[$colIndex] = $sum;
        }

        //dd($totals);

        // Total général des agents
        $uniqueAgents = collect($filtered) // $filtered = toutes les lignes AssistanceLine
            ->pluck("assistance.assistance_lines.*.assistance_agent_id") // récupérer tous les agents
            ->flatten()
            ->unique()
            ->count();

        $totals[$agentColumnIndex] = $uniqueAgents;
        // ajoute la ligne total à la fin
        $array[] = $totals;

        //dd($array);

        return $array;
    }

    
}
