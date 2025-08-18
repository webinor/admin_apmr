<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;


    /**
     * Get the parent imageable model (user or post).
     */
    public function keywordable()
    {
        return $this->morphTo();
    }

}
