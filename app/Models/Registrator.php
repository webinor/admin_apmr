<?php

namespace App\Models;

use App\Models\HumanResource\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Registrator extends Model
{
    use HasFactory , Notifiable;

    protected $table = "users";

    public function fullName(): string
    {
        return $this->name." ".$this->last_name;
    }

    /**
     * Get the city that owns the Registrator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

     /**
     * Get the employee that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class,);
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
