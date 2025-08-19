<?php

namespace App\Models\Misc;

use App\Models\Operations\Signature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeySign extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'model_type',
        'model_id',
        'key',
        'hash'
    ];

    public function signature()
    {
        return $this->morphOne(Signature::class, 'verifier');
    }
}
