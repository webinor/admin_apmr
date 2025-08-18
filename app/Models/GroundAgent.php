<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroundAgent extends Model
{
    use HasFactory;

    public function fullName(): string
    {
        return $this->first_name." ".$this->last_name;
    }
    /**
     * Get the company that owns the GroundAgent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class,);
    }
}
