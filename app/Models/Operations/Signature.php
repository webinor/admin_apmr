<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    public function model()
    {
        return $this->morphTo();
    }

    public function verifier()
    {
        return $this->morphTo();
    }
}
