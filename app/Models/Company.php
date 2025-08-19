<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;


    /**
     * Get the city that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,);
    }

    /**
     * The wheel_chairs that belong to the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wheel_chairs(): BelongsToMany
    {
        return $this->belongsToMany(WheelChair::class, 'company_wheel_chair', 'company_id', 'wheel_chair_id')->withPivot('price');
    }

     /**
     * Get all of the ground_agents for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ground_agents(): HasMany
    {
        return $this->hasMany(GroundAgent::class );
    }
   
}



