<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssistanceAgent extends Model
{
    use HasFactory;

    public function fullName(): string
    {
        return $this->first_name." ".$this->last_name;
    }

    /**
     * Get the city that owns the AssistanceAgent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Boot du modèle pour ajouter le scope global
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }
}
