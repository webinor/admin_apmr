<?php
namespace App\Jobs;

use App\Models\Operations\AssistanceLine;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessLinesAndChairsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $lineIds;
    public $wheelChairTypes;
    public $cacheKey;

    public function __construct(array $lineIds, array $wheelChairTypes, string $cacheKey)
    {
        $this->lineIds = $lineIds;
        $this->wheelChairTypes = $wheelChairTypes;
        $this->cacheKey = $cacheKey;
    }

    public function handle()
    {
        $linesOutput = [];
        $totals = array_fill_keys($this->wheelChairTypes, 0);

        // $lines = AssistanceLine::select('id','created_at' , 'assistance_agent_id' ,'assistance_id', 'code', 'wheel_chair_id' , 'beneficiary_name')->
        // with(['assistance:id,code,reference,ground_agent_id,flight_type,flight_number,created_at', 'wheel_chair:id,name,slug,code'])
        //                        ->whereIn('id', $this->lineIds)
        //                        ->orderBy('assistances.created_at', 'asc') // tri par la date de l'assistance
        //                        ->get();

    $lines = AssistanceLine::select(
        'assistance_lines.id',
        'assistance_lines.created_at',
        'assistance_lines.assistance_agent_id',
        'assistance_lines.assistance_id',
        'assistance_lines.code',
        'assistance_lines.wheel_chair_id',
        'assistance_lines.beneficiary_name'
    )
    ->join('assistances', 'assistances.id', '=', 'assistance_lines.assistance_id')
    ->with([
        'assistance:id,code,reference,ground_agent_id,flight_type,flight_number,created_at',
        'wheel_chair:id,name,slug,code'
    ])
    ->whereIn('assistance_lines.id', $this->lineIds)
   // ->orderBy('assistances.created_at', 'asc') // tri par la date de l'assistance
    ->get();


        foreach ($lines as $index => $line) {
            $chairs = [];

            foreach ($this->wheelChairTypes as $type) {
                $isMatch = $line->wheel_chair->slug === $type ? 1 : 0;
                $chairs[$type] = $isMatch;
                $totals[$type] += $isMatch;
            }

            $linesOutput[] = [
                '#' => $index + 1,
                'date' => $line->assistance->created_at->format('d/m/Y'),
                'mission' => $line->assistance->reference,
                'beneficiary' => $line->beneficiary_name,
                'flight_type' => $line->assistance->flight_type === 'départ' ? 'E' : 'D',
                'flight_number' => $line->assistance->flight_number,
                'chairs' => $chairs,
                'assistance_id'=>$line->assistance_id
            ];
        }

        //     echo "--------------------------".json_encode($linesOutput)."--------------------------\n\n";
$data = [
            'lines' => $linesOutput,
            'totals' => $totals
];

        Redis::setex($this->cacheKey, 3600, json_encode($data));
        /*cache()->put($this->cacheKey, [
            'lines' => $linesOutput,
            'totals' => $totals
        ], 3600);*/
    }
}
