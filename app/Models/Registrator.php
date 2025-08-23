<?php

namespace App\Models;

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
}
