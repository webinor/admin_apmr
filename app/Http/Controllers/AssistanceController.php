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
use Illuminate\Support\Facades\Log;
use ZipArchive;

class AssistanceController extends Controller
{
    protected $assistance_service;

    public function __construct(AssistanceService $assistance_service)
    {
        $this->assistance_service = $assistance_service;

        // $this->authorizeResource(Company::class, "employee");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = $request->results ? (int) $request->results : 10;

        $variables = $this->assistance_service->getIndexVariables($results);

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

    public function export(Request $request)
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

    public function exportPdf(Request $request)
    {
        // dd($request->all());
        // tu peux réutiliser ton ApmrExport ou construire un service "ApmrService"
        $export = new ApmrExport($request->all());
        $data = $export->array(); // même structure que ton Excel

        $params = $request->all();

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

        // return $totals;

        $pdf = Pdf::loadView("pdf.apmr_recap", [
            "companyImage" => $export->companyImage,
            "companyName" => $export->companyName, // libellé déjà résolu
            "month" => $export->month,
            "year" => $export->year,
            "dateDebut" => $export->dateDebut,
            "dateFin" => $export->dateFin,
            "wheelChairTypes" => $export->wheelChairTypes,
            "lines" => $lines,
            "totals" => $totals, // récupérés du calcul Excel
            "totalAgents" =>
                $_SERVER["SERVER_NAME"] != "127.0.0.1"
                    ? "$totalAgents"
                    : $totalAgents, // idem
        ]);

        switch ($params["action"]) {
            case "download-single":
                return $pdf->download("APMR_RECAP_". date("d_m_Y_H_i_s").".pdf");

                break;

            case "download-all":
                $filtered = $export->get_filtered();

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Http\Response
     */
    public function edit(Assistance $assistance)
    {
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
