<?php
namespace App\Models\Operations;

use App\Models\Adjustment;
use App\Models\AssistanceAgent;
use App\Models\Operations\Assistance;
use App\Models\WheelChair;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssistanceLine extends Model
{
    use HasFactory;

    public function adjustments()
{
    return $this->morphMany(Adjustment::class, 'adjustable');
}


 /**
     * Get the Assistance that owns the AssistanceLine
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Assistance(): BelongsTo
    {
        return $this->belongsTo(Assistance::class,);
    }

    /**
     * Get the assistance_agent that owns the AssistanceLine
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assistance_agent(): BelongsTo
    {
        return $this->belongsTo(AssistanceAgent::class,"assistance_agent_id");
    }


     /**
     * Get the wheel_chair that owns the AssistanceLine
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wheel_chair(): BelongsTo
    {
        return $this->belongsTo(WheelChair::class);
    }


    public function getAdjustedValue(string $field)
{
    // Cherche le dernier adjustment pour ce champ
    $lastAdjustment = $this->adjustments()
        ->where('field', $field)
        ->latest()
        ->first();

        if ($field == "wheel_chair_id" && $this->id ==2) {

            //dd($this);
           // dd($this->adjustments);
           // dd($lastAdjustment);
        }

        

    if ($lastAdjustment) {
        //dd($lastAdjustment);
        return $lastAdjustment->new_value;
    }

       // dd($field);
       // dd($this);
      //  dd($this->beneficiary_name ?? "nullo");

    // Sinon, valeur d'origine
    return $this->getRawOriginal($field);
}

public function getBeneficiaryNameAttribute($value)
{
    return $this->getAdjustedValue('beneficiary_name') ?? $value;
}

public function getWheelChairIdAttribute($value)
{
    return $this->getAdjustedValue('wheel_chair_id') ?? $value;
}

public function getAssistanceAgentIdAttribute($value)
{
    return $this->getAdjustedValue('assistance_agent_id') ?? $value;
}

public function getCommentAttribute($value)
{
    return $this->getAdjustedValue('comment') ?? $value;
}


}
