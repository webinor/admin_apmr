<?php

namespace App\Models\Operations;

use App\Models\AssistanceLine;
use App\Models\Company;
use App\Models\GroundAgent;
use App\Models\Misc\File;
use App\Models\Operations\Signature;
use App\Models\Registrator;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assistance extends Model
{
    use HasFactory;
     /**
     * Get the company that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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



}
