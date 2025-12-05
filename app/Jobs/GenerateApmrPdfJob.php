<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
namespace App\Jobs;

use App\Models\Export;
use App\Services\APMRExportPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Events\ApmrPdfReady;
use App\Models\ExportData;
use App\Models\Misc\File;
use App\Models\Operations\Assistance;
use Illuminate\Support\Facades\Log;

class GenerateApmrPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $export;
    protected $pdfData;
    protected $action; // "single" ou "all"

    public function __construct(ExportData $export, array $pdfData, string $action = "download-single")
    {
        $this->export = $export;
        $this->pdfData = $pdfData;
        $this->action = $action;
    }

    public function handle()
    {
        // 1️⃣ Générer le PDF récap
        $recapPath = APMRExportPdfService::generate($this->pdfData, $this->export);

        

        if ($this->action === "download-single") {
            // Émettre un événement pour front afin de lancer le téléchargement
            event(new ApmrPdfReady($this->export->id,  ($recapPath), 'single'));

             echo "--------------------------".json_encode("Event Fired Single")."--------------------------\n\n";


        } elseif ($this->action === "download-all") {
            // 2️⃣ Récupérer les PDF individuels
            //$filtered = array_keys($this->pdfData['agents_per_assistance']);
            $codes = ($this->pdfData['assistanceCodes']);//collect($filtered)->pluck("assistance.code")->unique()->values()->all();

         //    echo "--------------------------".json_encode($codes)."--------------------------\n\n";


            $savedFiles = $this->save_remote_assistance($codes , $this->export->id); // doit retourner array de chemins

             echo "--------------------------".json_encode(array_merge([$recapPath], $savedFiles))."--------------------------\n\n";


            // 3️⃣ Créer un ZIP avec le récap + les fichiers individuels
            $zipPath = $this->get_zip(array_merge([$recapPath], $savedFiles) , $this->export->id);

             echo "--------------------------".json_encode($zipPath)."--------------------------\n\n";


            // 4️⃣ Émettre un événement pour le front
            event(new ApmrPdfReady($this->export->id, ($zipPath), 'all'));

             echo "--------------------------".json_encode("Event Fired All")."--------------------------\n\n";

        }

        // 5️⃣ Marquer l'export comme terminé
        $this->export->update(['status' => 'completed']);
    }

   

    private function save_remote_assistance($codes , $id)
    {

//Storage::put("$directory/$filename", $pdf->output());
      //  $path =  storage_path("app/public/fiches_apmr"); //"exports/{$id}";//
        $path =  storage_path("app/exports/{$id}"); //"exports/{$id}";//

        //    echo "--------------------------".json_encode("save_remote_assistance : $path")."--------------------------\n\n";



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

          //  echo "--------------------------".json_encode("filePath : ".basename($filePath))."--------------------------\n\n";

                $savedFiles[] = "exports/{$id}/".$fileName; // on ajoute le chemin au tableau
            }
        }

        return $savedFiles; // retourne la liste des fichiers PDF enregistrés
  
        // Appel à ton service pour générer PDF individuel
        // Retourne le chemin complet
        //return "/path/to/pdf/{$code}.pdf";
    }

    private function get_zip(array $files , $folder): string
    {
        $zipName = "APMR_RECAP_". date("d_m_Y_H_i_s") . ".zip";
        $zipPath = storage_path("app/exports/{$folder}/{$zipName}");
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($files as $file) {


            $filename = basename($file);
            echo "--------------------------".json_encode("FICHIER : $file")."--------------------------\n\n";



            $localPath = storage_path("app/$file");

            echo "--------------------------".json_encode("PATH : $localPath")."--------------------------\n\n";


             if (file_exists($localPath)) {
            $zip->addFile($localPath, $filename);

            echo "--------------------------".json_encode("FICHIER PRESENT : $localPath")."--------------------------\n\n";


            
        } else {
            echo "--------------------------".json_encode("FICHIER MANQUANT : $localPath")."--------------------------\n\n";
            Log::error("FICHIER MANQUANT : $localPath");
        }
           // $zip->addFile(storage_path("app/$file"), $filename);
        }

        $zip->close();
        return "exports/{$zipName}";
    }
}
