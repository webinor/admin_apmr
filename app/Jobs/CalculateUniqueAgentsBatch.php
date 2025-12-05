<?php
namespace App\Jobs;

use App\Models\Operations\Assistance;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class CalculateUniqueAgentsBatch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels , Batchable;

    public $assistanceIds;
    public $cacheKey;

    public function __construct(array $assistanceIds, string $cacheKey)
    {
        $this->assistanceIds = $assistanceIds;
        $this->cacheKey = $cacheKey;
    }

    public function handle()
    {
        $perAssistance = [];               // [ assistance_id => count ]
        $assistanceCodes = [];               // [ assistance_id => count ]
        $globalAgents = collect();         // tous les agents uniques du batch

        // Charger ce batch de fiches + relations nécessaires
        $assistances = Assistance::with('assistance_lines:id,assistance_id,assistance_agent_id')
                                  ->whereIn('id', $this->assistanceIds)
                                  ->get();

        foreach ($assistances as $assistance) {
            // IDs agents uniques sur cette fiche
            $agentIds = $assistance->assistance_lines
                                    ->pluck('assistance_agent_id')
                                    ->unique()
                                    ->values();

            // Stocker le nombre
            $perAssistance[$assistance->id] = $agentIds->count();

            // Ajouter à la liste globale pour ce batch
            $globalAgents = $globalAgents->merge($agentIds);

            $assistanceCodes[] = $assistance->code;

        }

        echo "--------------------------".json_encode([
            'per_assistance' => $perAssistance,
            'agents' => $globalAgents->unique()->values(),
        ])."--------------------------\n\n";

        // On enregistre dans le cache, la liste globale permet plus tard de calculer le total global

       // $cacheKey = "export:{$this->batchId}:agents:{$this->assistanceId}";

$data = [
    'per_assistance' => $perAssistance,
    'assistanceCodes' => $assistanceCodes,
    'agents' => $globalAgents->unique()->values(),
];

// Stockage avec TTL 3600 secondes (1h)
Redis::setex($this->cacheKey, 3600, json_encode($data));


       /* cache()->put(
            //"export:{$this->batchId}:agents:{$this->assistanceId}",
            $this->cacheKey,
             [
            'per_assistance' => $perAssistance,
            'agents' => $globalAgents->unique()->values(),
        ], 3600); // 1h ou configurable
        */

    }
}
