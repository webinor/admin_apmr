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
use App\Services\BenchmarkService;
use Illuminate\Support\Facades\Log;
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
    ->has("signature") // assistance doit être signée
    ->whereHas("ground_agent.company.wheel_chairs") // filtre compagnie
    ->with([
        "ground_agent.company.wheel_chairs",
        "registrator",
        "assistance_lines.assistance_agent.city",
        "assistance_lines.wheel_chair",
    ]);

// Compagnie
if (!empty($filters["compagnie"])) {
    $query->whereHas("ground_agent.company", function ($qry) use ($filters) {
        $qry->whereCode($filters["compagnie"]);
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
if (!empty($filters["justificatifs"])) {
    $query->where("is_invoiced", true);
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


// Comptage exact des lignes filtrées
        $totalFiches = $query->count();

        return response()->json([
            "count" => $totalFiches,
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
$totalAgents = 0;

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
        'nb_agents' => $line->assistance->nb_unique_agents, // pas de pluck
    ];

    $totalAgents += $line->assistance->nb_unique_agents;
}

// Total général des agents uniques
//$totalAgents = $uniqueAgentIds->unique()->count();

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

             /*   $filtered = $export->get_filtered();

                $codes = collect($filtered)
                    ->pluck("assistance.code")
                    ->unique()
                    ->values()
                    ->all();

                $savedFiles = $this->save_remote_assistance($codes);

                $recapName = "recapitulatif_" . date("d_m_Y_H_i_s");

                $recapPath = $this->savePdf($pdf, "fiches_apmr", $recapName);

                // $allFiles = [];

                $allFiles = array_merge([$recapPath], $savedFiles);

                // array_push($savedFiles,  $recapPath);

                //  dd($savedFiles);

                $zipPath = $this->get_zip($allFiles);

                // Retourne le ZIP pour téléchargement
                return response()
                    ->download($zipPath)
                    ->deleteFileAfterSend(true);
                break;

            default:
                # code...
                break;

                */


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
    

    public function export_old_2(Request $request)
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
$benchmark->start("recap_creation");


        // dd($request->all());
        // tu peux réutiliser ton ApmrExport ou construire un service "ApmrService"
        $export = new ApmrExport($request->all());
        

$data = $export->array(); // même structure que Excel

       // dd($data);

      
      

        // ($filtered); save-remote
        // $codes = collect($filtered)->pluck('assistance.code')->unique()->values()->all();

        // $savedFiles = $this->save_remote_assistance($codes);

        $lines = [];

        // On prépare un tableau de totaux initialisés à 0
        $totals = [];
        foreach ($export->wheelChairTypes as $type) {
            $totals[$type] = 0;
        }
        $totalAgents = 0;
        $seenMissions = [];
        // On commence à partir de l’index où se trouvent les vraies données
        // Ici d’après ton dump, les données commencent à l’index 4 avec l’en-tête
        for ($i = 4; $i < count($data); $i++) {
            $row = $data[$i];

            // Ignore les lignes de totaux ou vides
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            $chairs = [];
            foreach ($export->wheelChairTypes as $index => $type) {
                // On suppose que les colonnes du Excel commencent à l'index 6 pour les chaises
                $colIndex = 6 + $index;
                $chairs[$type] = $row[$colIndex] ?? 0;
            }

            $lines[] = [
                "#" => $row[0] ?? null,
                "date" => $row[1] ?? null,
                "mission" => $row[2] ?? null,
                "beneficiary" => $row[3] ?? null,
                "flight_type" => $row[4] ?? null,
                "flight_number" => $row[5] ?? null,
                "chairs" => $chairs,
                "nb_agents" => $row[6 + count($export->wheelChairTypes)] ?? 0, // dernière colonne pour nb_agents
            ];
        }

        // On additionne
        foreach ($lines as $line) {
            foreach ($export->wheelChairTypes as $type) {
                // return $line;
                // return
                /*$totals[$type] += $line
            ['chairs']
            [$type] ?? 0;*/

                $value = $line["chairs"][$type] ?? 0;
                $totals[$type] = ($totals[$type] ?? 0) + (int) $value;
            }
        }

        // return $data;

        $lastLine = $data[count($data) - 1];
        $totalAgents = $lastLine[6 + count($export->wheelChairTypes)] ?? 0; // index correspondant à 'Nb d'agents'

       


         //   $pdf = Pdf::loadHTML("<h1>Test rapide</h1>");

        /* */

        $pdf = Pdf::loadView("pdf.apmr_recap", [
            "companyImage" => $export->companyImage,
            "companyName" => $export->companyName, // libellé déjà résolu
            "month" => $export->month,
            "year" => $export->year,
            "dateDebut" => $export->dateDebut,
            "dateFin" => $export->dateFin,
            "agent" => $export->agent,
            "wheelChairTypes" => $export->wheelChairTypes,
            "lines" => $lines,
            "totals" => $totals, // récupérés du calcul Excel
            "totalAgents" =>
                $_SERVER["SERVER_NAME"] != "127.0.0.1"
                    ? "$totalAgents"
                    : $totalAgents, // idem
        ]);/**/

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

             /*   $filtered = $export->get_filtered();

                $codes = collect($filtered)
                    ->pluck("assistance.code")
                    ->unique()
                    ->values()
                    ->all();

                $savedFiles = $this->save_remote_assistance($codes);

                $recapName = "recapitulatif_" . date("d_m_Y_H_i_s");

                $recapPath = $this->savePdf($pdf, "fiches_apmr", $recapName);

                // $allFiles = [];

                $allFiles = array_merge([$recapPath], $savedFiles);

                // array_push($savedFiles,  $recapPath);

                //  dd($savedFiles);

                $zipPath = $this->get_zip($allFiles);

                // Retourne le ZIP pour téléchargement
                return response()
                    ->download($zipPath)
                    ->deleteFileAfterSend(true);
                break;

            default:
                # code...
                break;

                */


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
