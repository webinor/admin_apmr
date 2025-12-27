<?php

namespace App\Models\Misc;

use App\Addons\Misc\ProductServiceProvider;
use Illuminate\Support\Str;
use App\Models\Misc\Invoice;
use App\Models\Operations\ProviderType;
use App\Models\Operations\Validation;
use App\Models\Prestations\Prestation;
use App\Models\Prestations\Product;
use App\Models\Prestations\Service;
use App\Models\Prestations\ServiceCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class InvoiceLine extends Model
{
    use HasFactory , ProductServiceProvider;

    // protected $guarded = [];

    protected $fillable = [
         "designation",
                            "quantity" ,
                            "unit_price" ,
                            "amount" ,
    ];


    /**
       * Get the invoice that owns the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function invoice(): BelongsTo
      {
          return $this->belongsTo(Invoice::class);
      }


      // /**
      //  * Get the validation associated with the InvoiceLine
      //  *
      //  * @return \Illuminate\Database\Eloquent\Relations\HasOne
      //  */
      // public function validation(): HasOne
      // {
      //     return $this->hasOne(Validation::class);
      // }
    


    public function get_quantity()
    {
        
       return  (int)$this->quantity >= 0 ? (int)$this->quantity  : 0  ;
        
        
    }

    public function get_price()
    {

   // dd($this->price);
       $price  =  (int)preg_replace("/,/", "",  $this->price); 
       // dd($price);
      return  $price ;//= (preg_replace("/,/", "",)); 
      // $price = (int)$this->price >= 0 ? (int)$this->price  : 0  ;
        
        
    }

    public function get_total()
    {
        
        $quantity = $this->get_quantity();
        $price = $this->get_price();

        return $quantity * $price;
        
        
    }

    

    


  

    

    public function get_amount(): int
 {
     

    // foreach ($this->thiss as $this) {
         $amount =(int)$this->quantity*(int)$this->price;
    // }

     return $amount;
 }


 public function get_sanitized_description()  {
  

  $description = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', Str::of($this->get_description())->trim() );


     preg_match("/[^0-9\s\/][\%\,\w\s\-\/\(\)\|\+]+/",$description,$sanitized_description);
  
     if (!isset($sanitized_description[0]) ) {

     // dd($this->get_description());
     return null;
     }
     return $sanitized_description[0];
  

 }

 public function get_description() : string {
  
return $this->description;

 }
    

}
