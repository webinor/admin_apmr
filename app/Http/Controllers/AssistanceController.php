<?php

namespace App\Http\Controllers;

use App\Exports\ApmrExport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Operations\Assistance;
use App\Services\Misc\AssistanceService;
use App\Models\Operations\AssistanceLine;
use App\Http\Requests\StoreAssistanceRequest;
use App\Http\Requests\UpdateAssistanceRequest;
use App\Jobs\CalculateUniqueAgentsBatch;
use App\Jobs\GenerateExportBatchJob;
use App\Jobs\GenerateFinalPdfJob;
use App\Jobs\ProcessLinesAndChairsJob;
use App\Models\ExportData;
use App\Services\BenchmarkService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use ZipArchive;

class AssistanceController extends Controller
{
    protected $assistance_service;

    public function __construct(AssistanceService $assistance_service)
    {
        $this->assistance_service = $assistance_service;

        // $this->authorizeResource(Company::class, "employee");
        //$this->authorizeResource(CommercialQuote::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    $this->authorize('viewAny',Assistance::class);

        $results = $request->results ? (int) $request->results : 10;

        $variables = $this->assistance_service->getIndexVariables($request , $results);

        return $this->assistance_service->getView(
            "assistance.index",
            $variables
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    

        $variables = $this->assistance_service->getCreateVariables();

        return $this->assistance_service->getView(
            "assistance.manage",
            $variables
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAssistanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssistanceRequest $request)
    {
        //
    }

    public function count_filtered_results(Request $request)
    {
        return $this->assistance_service->count_filtered_results($request);
    }

    public function countFiltered(Request $request)
    {


        $filters = $request->all();

           $query = Assistance::query()
   // ->has("signature") // assistance doit être signée
    ->whereHas("ground_agent.company.wheel_chairs") // filtre compagnie
    ->with([
        "ground_agent.company.wheel_chairs",
        "registrator",
        "assistance_lines.assistance_agent.city",
        "assistance_lines.wheel_chair",
    ]);

// Compagnie
if (!empty($filters["company"])) {
    $query->whereHas("ground_agent.company", function ($qry) use ($filters) {
        $qry->whereCode($filters["company"]);
    });
}

// Période
if (!empty($filters["date_debut"])) {
    $query->whereDate("created_at", ">=", $filters["date_debut"]);
}
if (!empty($filters["date_fin"])) {
    $query->whereDate("created_at", "<=", $filters["date_fin"]);
}

// Enregistré par
if (!empty($filters["user"])) {
    $query->whereHas("registrator", function ($qry) use ($filters) {
        $qry->whereCode($filters["user"]);
    });
}
   

// Déjà facturées
if (!empty($filters["invoiced"]) && $filters['invoiced'] == "y") {
    //dd($filters['invoiced']);
    $query->whereNotNull("invoice_id");
}

//Non facturées
if (!empty($filters["invoiced"]) && $filters['invoiced'] == "n") {
    $query->whereNull("invoice_id");
}

// Min / Max prix
if (!empty($filters["min-price"])) {
    $query->where("total", ">=", $filters["min-price"]);
}
if (!empty($filters["max-price"])) {
    $query->where("total", "<=", $filters["max-price"]);
}

// Filtre Agent (au niveau des lignes)
if (!empty($filters["agent"])) {
    $query->whereHas("assistance_lines.assistance_agent", function ($qry) use ($filters) {
        $qry->whereCode($filters["agent"]);
    });
}

// Filtre Ville (au niveau des lignes)
if (!empty($filters["city"])) {
    $query->whereHas("assistance_lines.assistance_agent.city", function ($qry) use ($filters) {
        $qry->whereCode($filters["city"]);
    });
}

// Filtre Type chaise (au niveau des lignes)
if (!empty($filters["wheel_chair"])) {
    $query->whereHas("assistance_lines.wheel_chair", function ($qry) use ($filters) {
        $qry->whereCode($filters["wheel_chair"]);
    });
}


//  $totalFiches = (clone $query)->count();

// $totalFichesSignees = (clone $query)
//     ->has('signature')
//     ->count();

// $totalFichesNonSignees = (clone $query)
//     ->doesntHave('signature')
//     ->count();

$totalFiches = (clone $query)->count();

$totalFichesSignees = (clone $query)
    ->has('signature')
    ->count();

$totalFichesNonSignees = $totalFiches - $totalFichesSignees;


        return response()->json([
            "count_signed" =>  $totalFichesSignees,
            "count_unsigned" =>  $totalFichesNonSignees,
            "count_total"=>$totalFiches
        ]);



        /////////////////////////////////////////////////////////////////////////////
        // On part directement des lignes d'assistance
        $query = AssistanceLine::query()
            ->whereHas("assistance.signature") // s'assure que l'assistance a une signature
            ->whereHas("assistance", function ($q) use ($request) {
                // Compagnie
                if ($request->filled("compagnie")) {
                    $q->whereHas("ground_agent.company", function ($qry) use (
                        $request
                    ) {
                        $qry->whereCode($request->compagnie);
                    });
                }

                // Période
                if ($request->filled("date_debut")) {
                    $q->whereDate("created_at", ">=", $request->date_debut);
                }
                if ($request->filled("date_fin")) {
                    $q->whereDate("created_at", "<=", $request->date_fin);
                }

                // Enregistré par
                if ($request->filled("user")) {
                    $q->whereHas("registrator", function ($qry) use ($request) {
                        $qry->whereCode($request->user);
                    });
                }

                // Déjà facturées
                if ($request->filled("justificatifs")) {
                    $q->where("is_invoiced", true);
                }

                // Min / Max prix
                if ($request->filled("min-price")) {
                    $q->where("total", ">=", $request->input("min-price"));
                }
                if ($request->filled("max-price")) {
                    $q->where("total", "<=", $request->input("max-price"));
                }
            });

        // Filtre sur Agent (ligne d'assistance)
        if ($request->filled("agent")) {
            $query->whereHas("assistance_agent", function ($qry) use (
                $request
            ) {
                $qry->whereCode($request->agent);
            });
        }

        // Filtre sur Ville (ligne d'assistance)
        if ($request->filled("city")) {
            $query->whereHas("assistance_agent.city", function ($qry) use (
                $request
            ) {
                $qry->whereCode($request->city);
            });
        }

        // Filtre sur Type de chaise (ligne d'assistance)
        if ($request->filled("wheel_chair")) {
            $query->whereHas("wheel_chair", function ($qry) use ($request) {
                $qry->whereCode($request->wheel_chair);
            });
        }

        // Comptage exact des lignes filtrées
        $totalLines = $query->count();

        return response()->json([
            "count" => $totalLines,
        ]);
    }

    public function export_old(Request $request)
    {
        $filters = $request->all();

        return Excel::download(
            new ApmrExport($filters), // ici on passe le tableau des filtres
            "apmr_export_" . date("Ymd_His") . ".xlsx"
        );
    }

    public function save_remote_assistance(array $codes)
    {
        $path = storage_path("app/public/fiches_apmr");

        // Vérifie et crée le dossier si nécessaire
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true); // true => récursif
        }

        $client = new \GuzzleHttp\Client();
        $savedFiles = []; // tableau pour stocker les fichiers enregistrés

        foreach ($codes as $code) {
            $url =
                config("services.apmr_service.base_url") .
                "/api/operations/export-pdf/{$code}?output=save-remote";

            $response = $client->get($url, [
                "headers" => [
                    "Accept" => "application/pdf",
                ],
            ]);

            $contentType = $response->getHeaderLine("Content-Type");

            if ($contentType !== "application/pdf") {
                // On peut logguer l'erreur plutôt que dd()
                Log::error(
                    "Erreur lors de l'export PDF pour le code {$code} : " .
                        (string) $response->getBody()
                );
                continue; // on passe au code suivant
            }

            $assistance = Assistance::whereCode($code)->first();
            //  $fileName = $assistance->flight_number . '_' . Str::random(8) . '.pdf';
            $fileName = $assistance->reference . ".pdf";
            $filePath = $path . "/" . $fileName;

            file_put_contents($filePath, $response->getBody());

            if ($response->getStatusCode() === 200) {
                $savedFiles[] = $filePath; // on ajoute le chemin au tableau
            }
        }

        return $savedFiles; // retourne la liste des fichiers PDF enregistrés
    }


    public function generateRecap(Request $request)
{



    $action = $request->get('action');

    //Redis::setex("papa", 3600, json_encode(["papa"]));
    
    //dd(Redis::keys("*papa*"));
   // dd(Redis::get("papa"));
    // 1) Récupérer les IDs filtrés via ton export builder
    $export = new ApmrExport($request->all());

   // return $request->all();

    $filtered = $export->get_filtered(); // ta méthode actuelle

  //  return($filtered);
    $linesIds = $filtered->pluck('id')->toArray();
    $assistanceIds = $filtered->pluck('assistance_id')->unique()->toArray();

    // 2) Récupérer les wheel_chairs (déjà calculé dans ton code)
    // $wheelChairTypes  = $filtered
    // ->flatMap(fn($line) => $line->assistance->ground_agent->company->wheel_chairs->pluck('slug'))
    // ->unique()
    // ->values()
    // ->toArray();

    $wheelChairTypes = $filtered
    ->pluck('assistance')              // récupère toutes les assistances
    ->unique('company_id')             // 1 seule fois chaque company
    ->flatMap(fn($assistance) => $assistance->company->wheel_chairs->pluck('slug'))
    ->unique()
    ->values()
    ->toArray();

    // echo "--------------------------".json_encode($wheelChairTypes)."--------------------------\n\n";

    // 3) Générer la clé finale du cache
    $filtersHash =  Str::uuid();
    $finalCacheKey = "export_batch_" . $filtersHash;

    $exportData = ExportData::create([
    'data' => json_encode($export),
     'finalCacheKey' => $finalCacheKey,
]);


 // 4) Créer les jobs enfants
    $batchJobs = [];

    // Chunk des assistances
    foreach (collect($assistanceIds)->chunk(20) as $i => $chunk) {
        $batchJobs[] = new CalculateUniqueAgentsBatch(
            $chunk->toArray(),
            $finalCacheKey."_batch_agents_$i"
        );
    }

    // Chunk des lignes
    foreach (collect($linesIds)->chunk(20) as $j => $chunk) {
        $batchJobs[] = new ProcessLinesAndChairsJob(
            $chunk->toArray(),
            $wheelChairTypes,
            $finalCacheKey."_batch_lines_$j"
        );
    }

    // 5) Lancer le batch avec callback finally
    Bus::batch($batchJobs)
        ->name("export_batch_{$exportData->id}")
        ->finally(function ($batch) use ($finalCacheKey, $exportData, $wheelChairTypes,$action) {
            // Job final pour assembler les données et générer le PDF
            GenerateFinalPdfJob::dispatch($finalCacheKey, $exportData->id, $wheelChairTypes , $action);
        })
        ->dispatch();
    

    // 4) Lancer le batch orchestrateur
    /*GenerateExportBatchJob::dispatch(
        $assistanceIds,
        $linesIds,
        $wheelChairTypes,
        $finalCacheKey,
        $exportData->id
    );*/

  


    return response()->json([
        'message' => 'Génération de vos fichiers en cours...',
        'exportId' => $exportData->id,
      //  'cache_key' => $finalCacheKey
    ]);
}


       public function export(Request $request)
    {
        $params = $request->all();
        
       // dd($request->has('export'));

        if (!$request->has('export')) {

          //  dd("ici");
    return $this->filterData($request);
}

$benchmark = new BenchmarkService();


if ($params["file_type"] == "excel" ) {
   
     return Excel::download(
            new ApmrExport($params), // ici on passe le tableau des filtres
            "apmr_export_" . date("Ymd_His") . ".xlsx"
        );
}

elseif($params["file_type"] == "csv"){

}

 // Mesure CREATION recap PDF

 return $this->generateRecap($request);









 

$benchmark->start("recap_creation");

Log::info("\n\n\n-----------------------------||-----------------------------------");

// Début global
$startGlobal = microtime(true);

// 1️⃣ Instanciation du service / export
$start = microtime(true);
$export = new ApmrExport($request->all());
$time = microtime(true) - $start;
Log::info("Benchmark: Export instancié en {$time} secondes");

// 2️⃣ Récupération des lignes filtrées avec relations
$start = microtime(true);
$filtered = $export->get_filtered();
$time = microtime(true) - $start;
Log::info("Benchmark: Lignes filtrées récupérées en {$time} secondes");

// 3️⃣ WheelChair types dynamiques
$start = microtime(true);
$wheelChairTypes = $filtered
    ->flatMap(fn($line) => $line->assistance->ground_agent->company->wheel_chairs->pluck('slug'))
    ->unique()
    ->values()
    ->toArray();
$export->wheelChairTypes = $wheelChairTypes;
$time = microtime(true) - $start;
Log::info("Benchmark: WheelChair types calculés en {$time} secondes");

// 4️⃣ Construction des lignes pour le Blade
$start = microtime(true);

$benchmark->start("recap_lines_totals");

// Construction des lignes + calcul des totaux + agents uniques
$lines = [];
$totals = array_fill_keys($wheelChairTypes, 0);
$uniqueAgentIds = collect();

// Calculer les agents uniques **globalement**
$assistancesIds = $filtered->pluck('assistance_id')->unique();

$chunkSize = 50; // ou 10, 50, selon le volume

$chunks = $assistancesIds->chunk($chunkSize);

foreach ($chunks as $index => $chunk) {
    $cacheKey = "unique_agents_batch_$index";

  //  dispatch(new CalculateUniqueAgentsBatch($chunk->toArray(), $cacheKey));
}


foreach ($filtered as $index => $line) {
    $chairs = [];
    foreach ($wheelChairTypes as $type) {
        $count = $line->wheel_chair->slug === $type ? 1 : 0;
        $chairs[$type] = $count;
        $totals[$type] += $count;
    }

    $lines[] = [
        '#' => $index + 1,
        'date' => $line->created_at->format('d/m/Y'),
        'mission' => $line->assistance->reference,
        'beneficiary' => $line->beneficiary_name,
        'flight_type' => $line->assistance->flight_type === 'départ' ? 'E' : 'D',
        'flight_number' => $line->assistance->flight_number,
        'chairs' => $chairs,
        'nb_agents' =>0// $line->nb_agents_unique,
    ];
}

// Total général agents
//$totalAgents = $filtered->sum('nb_agents_unique');

// Total général des agents uniques
$totalAgents =0;// $agentCounts;// $uniqueAgentIds->count();

$time = microtime(true) - $start;
Log::info("Benchmark: Lignes et totaux construits en {$time} secondes");


// 6️⃣ Génération PDF
$start = microtime(true);
$pdf = Pdf::loadView('pdf.apmr_recap', [
    'companyImage' => $export->companyImage,
    'companyName' => $export->companyName,
    'month' => $export->month,
    'year' => $export->year,
    'dateDebut' => $export->dateDebut,
    'dateFin' => $export->dateFin,
    'agent' => $export->agent,
    'wheelChairTypes' => $wheelChairTypes,
    'lines' => $lines,
    'totals' => $totals,
    'totalAgents' => $totalAgents,
]);
$time = microtime(true) - $start;
Log::info("Benchmark: PDF généré en {$time} secondes");

// Fin globale
$totalTime = microtime(true) - $startGlobal;
Log::info("Benchmark total: {$totalTime} secondes");


$benchmark->end("recap_creation");

        switch ($params["action"]) {
            case "download-single":
                
                $recapName = "APMR_RECAP_". date("d_m_Y_H_i_s");
                    $benchmark->start("download_only");
                    $response = $pdf->download("APMR_RECAP_". date("d_m_Y_H_i_s") . ".pdf");
                    $benchmark->end("download_only");

                    // Total
                    $benchmark->start("total");
                    $benchmark->end("total");

                    // Sauvegarde DB
                    $benchmark->save("download-single", [
                        "fiche_recap"=>$recapName,
                        "agent" => $export->agent,
                        "count_lines" => count($lines),
                    ]);

                    return $response;

                break;

            case "download-all":

           


    $benchmark->start("generation_individual");

    $filtered = $export->get_filtered();
    $codes = collect($filtered)->pluck("assistance.code")->unique()->values()->all();
    $savedFiles = $this->save_remote_assistance($codes);

    $benchmark->end("generation_individual");

    $recapName = "recapitulatif_" . date("d_m_Y_H_i_s");
    $recapPath = $this->savePdf($pdf, "fiches_apmr", $recapName);

    // ZIP
    $benchmark->start("zip");
    $zipPath = $this->get_zip(array_merge([$recapPath], $savedFiles));
    $benchmark->end("zip");

    // Total process
    $benchmark->start("total");
    $benchmark->end("total");

    // Sauvegarde DB
    $benchmark->save("download-all", [
        "fiche_recap"=>$recapName,
        "agent" => $export->agent,
        "nb_fiches" => count($savedFiles),
        "count_lines" => count($lines),
    ]);

    return response()->download($zipPath)->deleteFileAfterSend(true);

    break;
        }
    }
    


    /**
     * Sauvegarde un PDF et retourne le chemin complet
     *
     * @param \Barryvdh\DomPDF\PDF $pdf
     * @param string|null $folder Dossier de sauvegarde relatif à storage/app/public
     * @param string|null $fileName Nom du fichier, si null => génère un nom aléatoire
     * @return string Chemin complet du fichier sauvegardé
     */
    function savePdf($pdf, $folder = "pdfs", $fileName = null)
    {
        // Dossier complet
        $path = storage_path("app/public/" . $folder);

        // Crée le dossier si inexistant
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Nom du fichier
        if (!$fileName) {
            $fileName = "pdf_" . Str::random(8) . ".pdf";
        } elseif (!str_ends_with($fileName, ".pdf")) {
            $fileName .= ".pdf";
        }

        $filePath = $path . "/" . $fileName;

        // Sauvegarde le PDF
        file_put_contents($filePath, $pdf->output());

        return $filePath;
    }

    public function get_zip($savedFiles)
    {
        //  dd($savedFiles);

        $path = storage_path("app/public/fiches_apmr");

        // Création du ZIP
        $zipName = "fiches_apmr_" . date("d_m_Y_H_i_s") . ".zip";

        $zipPath = $path . "/" . $zipName;
        //$zipPath = tempnam(sys_get_temp_dir(), 'fiches_apmr_') . '.zip';

        $zip = new ZipArchive();
        $res = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($res === true) {
            // dd("ok");
            foreach ($savedFiles as $file) {
                $zip->addFile($file, basename($file)); // ajoute le fichier dans le zip
            }
            $zip->close();

            if (!file_exists($zipPath)) {
                abort(500, "Le ZIP n’a pas pu être créé");
            }
        } else {
            abort(
                500,
                "Impossible de créer le ZIP. Code erreur ZipArchive: $res"
            );
        }

        return $zipPath;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function show(Assistance $assistance)
    {
         $this->authorize('view',$assistance);

        $variables = $this->assistance_service->getShowVariables($assistance);

        return $this->assistance_service->getView(
            "assistance.manage",
            $variables
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function edit(Assistance $assistance)
    {

        $this->authorize('update',$assistance);

        $variables = $this->assistance_service->getEditVariables($assistance);

        return $this->assistance_service->getView(
            "assistance.manage",
            $variables
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAssistanceRequest  $request
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateAssistanceRequest $request,
        Assistance $assistance
    ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assistance $assistance)
    {
        $response = $this->assistance_service->deleteAssistance($assistance);

        return $response;
    }
}
