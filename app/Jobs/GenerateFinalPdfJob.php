<?php

namespace App\Jobs;

use App\Models\ExportData;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Services\APMRExportPdfService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerateFinalPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     public $finalCacheKey;
    public $exportId;
    public $wheelChairTypes;
    public $action;

    public function __construct($finalCacheKey, $exportId, $wheelChairTypes, $action)
    {
        $this->finalCacheKey = $finalCacheKey;
        $this->exportId = $exportId;
        $this->wheelChairTypes = $wheelChairTypes;
        $this->action = $action;
    }



    public function handle()
    {
        $export = ExportData::find($this->exportId);

        // Récupérer tous les caches des jobs enfants   
        //$finalCacheKey = "export_batch_" . $filtersHash;
        $cachePrefix = config('database.redis.options.prefix', '');
$pattern =  '*'.$this->finalCacheKey.'*';
        //$keys = cache()->get($batchKeyPrefix . '*');
        $keys = Redis::keys($pattern);
        $linesFinal = [];
        $totalsFinal = array_fill_keys($this->wheelChairTypes, 0);
        $allAgents = collect();
        $perAssistanceFinal = [];
        $assistanceCodes = [];

             echo "--------------------------".json_encode($pattern)."--------------------------\n\n";
             echo "--------------------------".json_encode($keys)."--------------------------\n\n";
        

        foreach ($keys as $key) {

            $key = trim($key); // retire espaces ou retours à la ligne

            
             $logicalKey = Str::replaceFirst($cachePrefix, '', $key);

          //   echo "--------------------------".json_encode($logicalKey)."--------------------------\n\n";


            $data = json_decode(Redis::get($logicalKey), true);
            
            
          //  echo "--------------------------".json_encode($raw)."--------------------------\n\n";
           
            if (Str::contains($key, '_batch_agents_') ) {
             //   $perAssistanceFinal = array_merge($perAssistanceFinal, $data['per_assistance']);
                foreach ($data['per_assistance'] as $assistanceId => $value) {
    $perAssistanceFinal[$assistanceId] = $value;

}

     foreach ($data['assistanceCodes'] as $assistanceCode ) {
    $assistanceCodes[] = $assistanceCode;

}

   
                $allAgents = $allAgents->merge($data['agents']);

           //  echo "--------------------------".json_encode($allAgents)."--------------------------\n\n";

            }

            if (Str::contains($key, '_batch_lines_') ) {

              //   echo "--------------------------".json_encode("ouiiiii 2")."--------------------------\n\n";

                $linesFinal = array_merge($linesFinal, $data['lines']);
                foreach ($data['totals'] as $type => $val) {
                    $totalsFinal[$type] += $val;
                }

            // echo "--------------------------".json_encode($data['lines'])."--------------------------\n\n";

            }
        }


           //  echo "--------------------------".json_encode($data)."--------------------------\n\n";


        $totalAgentsGlobal = $allAgents->unique()->count();

        // Stocker le résultat final dans le cache
        /*cache()->put($this->finalCacheKey, [
            'lines' => $linesFinal,
            'totals_chairs' => $totalsFinal,
            'agents_per_assistance' => $perAssistanceFinal,
            'total_agents_unique' => $totalAgentsGlobal,
        ], 3600);*/

      //  $action = 'all';
        

        // Génération PDF ici
        //$pdfPath = (new PdfGenerator)->build($export, $results, $allAgentIds);

        $pdf_data = [
            'lines' => $linesFinal,
            'totals_chairs' => $totalsFinal,
            'assistanceCodes'=>$assistanceCodes,
            'agents_per_assistance' => $perAssistanceFinal,
            'total_agents_unique' => $totalAgentsGlobal,
            'wheelChairTypes'=>$this->wheelChairTypes
        ];

             echo "--------------------------".json_encode($pdf_data)."--------------------------\n\n";
           //  echo "--------------------------".json_encode($export)."--------------------------\n\n";


        GenerateApmrPdfJob::dispatch($export, $pdf_data, $this->action);

      
    }

    public function handle_2()
    {
        // Récupère tous les résultats du batch
        $keys =[];// Cache::getRedis()->keys("export:{$this->batchId}:agents:*");

        $results = [];
        foreach ($keys as $key) {
            $assistanceId = explode(':', $key)[3]; // dernier segment
            $results[$assistanceId] = Cache::get($key);
        }

        // Calcul du total agents uniques global
        $allAgentIds = collect($results)->values()->unique()->count();

        // Charger ici les lignes/tableau nécessaires au PDF
        $export = ExportData::find($this->exportId);

        // Génération PDF ici
        //$pdfPath = (new PdfGenerator)->build($export, $results, $allAgentIds);

        $data = Cache::get($export->finalCacheKey);;// cache()->get();
        

if (!$data) {
    abort(404, "Export non prêt");
}

$pdfPath = APMRExportPdfService::generate($data,$export);

        // Mise à jour en DB
        $export->update([
            'pdf_path' => $pdfPath,
            'status' => 'completed'
        ]);
    }
}
