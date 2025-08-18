<?php

namespace App\Models\Misc;

use App\Addons\Scopes\Misc\InvoiceScope;
use App\Models\Misc\Resource;
use App\Models\Misc\InvoiceLine;
use App\Models\Operations\Folder;
use App\Models\Service;
use App\Models\Prestations\ServiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

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
  * Get the remote_inserted associated with the Invoice
  *
  * @return \Illuminate\Database\Eloquent\Relations\HasOne
  */
  public function remote_inserted(): HasOne
  {
      return $this->hasOne(RemoteInserted::class);
  }

  public function is_invoice_validated_remote(): bool
  {
    //dd($this->remote_inserted);
      return $this->remote_inserted && $this->remote_inserted->is_validated_in_remote;
  }


 public function get_amount(): int
 {
     $amount = 0;

     foreach ($this->invoice_lines as $invoice_line) {
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
   
      /**
       * Get the service_type that owns the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function service_type(): BelongsTo
      {
          return $this->belongsTo(ServiceType::class);
      }  


      /**
     * Get the parent prestationable model (user or post).
     */
    public function prestationable()
    {
        return $this->morphTo();
    }


    /**
     * Get the folder that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class,);
    }

      


}
