<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Bus;

class GenerateExportBatchJob implements ShouldQueue
{
    use Dispatchable, Queueable , Batchable;

    public $filteredAssistances;
    public $filteredLines;
    public $wheelChairTypes;
    public $finalCacheKey;
    public $exportId;

    public function __construct($assistancesIds, $linesIds, $wheelChairTypes, $finalCacheKey, $exportId)
    {
        $this->filteredAssistances = $assistancesIds;
        $this->filteredLines = $linesIds;
        $this->wheelChairTypes = $wheelChairTypes;
        $this->finalCacheKey = $finalCacheKey;
        $this->exportId = $exportId;
    }

    public function handle()
    {
        $batchJobs = [];
            $wheelChairTypes = $this->wheelChairTypes;
$finalCacheKey = $this->finalCacheKey;
$exportId = $this->exportId;

        // 1) Diviser les assistances en chunks de 20
        foreach (collect($this->filteredAssistances)->chunk(20) as $i => $chunk) {
            $batchJobs[] = new CalculateUniqueAgentsBatch(
                $chunk->toArray(),
                "batch_agents_$i"
            );
        }

        // 2) Diviser les lignes en chunks
        foreach (collect($this->filteredLines)->chunk(20) as $j => $chunk) {
            $batchJobs[] = new ProcessLinesAndChairsJob(
                $chunk->toArray(),
                $this->wheelChairTypes,
                "batch_lines_$j"
            );
        }


    

        Bus::batch($batchJobs)->then(function (Batch $batch) use ($wheelChairTypes, $finalCacheKey, $exportId) {
            $perAssistanceFinal = [];
            $allAgents = collect();
            $linesFinal = [];
            $totalsFinal = array_fill_keys($wheelChairTypes, 0);

            // Récupération des caches du batch
            foreach (cache()->getMultiple(cache()->getKeys()) as $key => $data) {

                if (str_starts_with($key, "batch_agents_")) {
                    $perAssistanceFinal = array_merge($perAssistanceFinal, $data['per_assistance']);
                    $allAgents = $allAgents->merge($data['agents']);
                }

                if (str_starts_with($key, "batch_lines_")) {
                    $linesFinal = array_merge($linesFinal, $data['lines']);

                    foreach ($data['totals'] as $type => $value) {
                        $totalsFinal[$type] += $value;
                    }
                }
            }

            // Total global des agents uniques
            $totalAgentsGlobal = $allAgents->unique()->count();

            // Résultat final
            cache()->put($finalCacheKey, [
                'lines' => $linesFinal,
                'totals_chairs' => $totalsFinal,
                'agents_per_assistance' => $perAssistanceFinal,
                'total_agents_unique' => $totalAgentsGlobal,
            ], 3600);


             GenerateFinalPdfJob::dispatch($batch->id, $exportId);
        })->dispatch();
    }
}
