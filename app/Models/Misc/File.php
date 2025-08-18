<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable=[
        'code'
    ];

     /**
     * Get the parent file model (structure or user).
     */
    public function fileable()
    {
        return $this->morphTo();
    }

    

      
}
