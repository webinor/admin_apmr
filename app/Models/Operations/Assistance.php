<?php

namespace App\Models\Operations;

use App\Models\Company;
use App\Models\Misc\File;
use App\Models\User\User;
use App\Models\Adjustment;
use App\Models\GroundAgent;
use App\Models\Registrator;
use Illuminate\Support\Str;
use App\Models\Operations\Signature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assistance extends Model
{
    use HasFactory;



    public function flight_type_text()  {
        
        return  Str::substr($this->flight_type, 0, 3);
    }
    
     /**
     * Get the company that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function adjustments()
{
    return $this->morphMany(Adjustment::class, 'adjustable');
}

    public function signature()
{
    return $this->morphOne(Signature::class, 'model');
}


        
  // Si une assistance peut avoir plusieurs fichiers
  public function files()
  {
      return $this->morphMany(File::class, 'fileable');
  }

 /**
  * Get all of the Assistance_lines for the Assistance
  *
  * @return \Illuminate\Database\Eloquent\Relations\HasMany
  */
 public function Assistance_lines(): HasMany
 {
     return $this->hasMany(AssistanceLine::class,);
 }

 /**
  * Get the ground_agent associated with the Assistance
  *
  * @return \Illuminate\Database\Eloquent\Relations\HasOne
  */
  public function ground_agent(): BelongsTo
  {
      return $this->belongsTo(GroundAgent::class);
  }

   /**
  * Get the registrator associated with the Assistance
  *
  * @return \Illuminate\Database\Eloquent\Relations\HasOne
  */
 public function registrator(): BelongsTo
 {
     return $this->belongsTo(Registrator::class,'user_id');
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
