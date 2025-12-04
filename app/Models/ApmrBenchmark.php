<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApmrBenchmark extends Model
{
    use HasFactory;

     protected $fillable = [
        'action',
        'recap_creation_time',
        'download_only_time',
        'generation_individual_files_time',
        'zip_time',
        'total_time',
        'extra',
    ];

    protected $casts = [
        'extra' => 'array',
    ];
}
