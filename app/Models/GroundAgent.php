<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class GroundAgent extends Model
{
    use HasFactory , Notifiable ;

    public function fullName(): string
    {
        return $this->first_name." ".$this->last_name;
    }

     public function comment(): string
    {
        return $this->comment;
    }
    /**
     * Get the company that owns the GroundAgent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault([
            'name' => 'Non définie',
            //'email' => null,
            //'phone' => null,
        ]);;
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
