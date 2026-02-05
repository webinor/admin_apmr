<?php

namespace App\Models\Misc;

use App\Addons\Scopes\Misc\InvoiceScope;
use App\Models\Company;
use App\Models\Misc\Resource;
use App\Models\Misc\InvoiceLine;
use App\Models\Operations\Assistance;
use App\Models\Operations\Folder;
use App\Models\Service;
use App\Models\Prestations\ServiceType;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
         "code" ,
                "invoice_number" ,
                "created_by",
                "company_id",
                "start_date",
                "end_date"

    ];

/*
   /**
 * Retrieve the model for a bound value.
 *
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \Illuminate\Database\Eloquent\Model|null
 *
public function resolveRouteBinding($value, $field = null)
{
   // dd("test");
    return $this->where('code', $value)->firstOrFail();
}
 */

/**
 * Get all of the assistnces for the Invoice
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function assistances(): HasMany
{
    return $this->hasMany(Assistance::class);
}

/**
 * Get the company that owns the Invoice
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function company(): BelongsTo
{
    return $this->belongsTo(Company::class,);
}

 /**
     * Get the invoicer that owns the Assistance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoicer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }


//    public function total()
// {
//     // Somme de toutes les lignes (quantité * prix unitaire)
//     return $this->lines->sum(function($line) {
//         return $line->quantity * $line->unit_price;
//     });
// }


 public function get_amount(): int
 {
     $amount = 0;

     

     foreach ($this->invoice_lines as $invoice_line) {

        //  dd($invoice_line->get_amount());
         $amount +=$invoice_line->get_amount();// (int)$invoice_line->quantity*(int)$invoice_line->price;
     }

     return $amount;
 }

 public function get_code(): string
   {
       return $this->code;
   }

   public function get_reference(): string
   {
       return $this->reference != __("REFERENCE INTROUVABLE") ? $this->reference : __("REFERENCE INTROUVABLE") ;
   }
   /**
    * Get all of the invoice_lines for the Invoice
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function invoice_lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }
   

    public function get_period() : string {
        
            $start = Carbon::parse($this->start_date)->startOfDay();
            $end   = Carbon::parse($this->end_date)->endOfDay();

  


       // Formater les dates : ex "12 novembre 2025"
    $startFormatted = $start->translatedFormat('d F Y');
    $endFormatted   = $end->translatedFormat('d F Y');

    return "Du $startFormatted Au $endFormatted";
    }
     

      /**
     * Get the parent prestationable model (user or post).
     */
    public function prestationable()
    {
        return $this->morphTo();
    }


      


}
