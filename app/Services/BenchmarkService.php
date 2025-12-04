<?php

namespace App\Services;

use App\Models\ApmrBenchmark;

class BenchmarkService
{
    private $marks = [];
    private $data = [];

    public function start($name)
    {
        $this->marks[$name] = microtime(true);
    }

    public function end($name)
    {
        $end = microtime(true);
        $this->data[$name] = round($end - $this->marks[$name], 4);
    }

    public function save($action, array $extra = [])
    {
        return ApmrBenchmark::create([
            'action' => $action,
            'recap_creation_time' => $this->data['recap_creation'] ?? null,
            'download_only_time' => $this->data['download_only'] ?? null,
            'generation_individual_files_time' => $this->data['generation_individual'] ?? null,
            'zip_time' => $this->data['zip'] ?? null,
            'total_time' => $this->data['total'] ?? null,
            'extra' => $extra,
        ]);
    }

    public function getData()
    {
        return $this->data;
    }
}
