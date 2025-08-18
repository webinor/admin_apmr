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

    protected $guarded = [];


    /**
       * Get the invoice that owns the Invoice
       *
       * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
       */
      public function invoice(): BelongsTo
      {
          return $this->belongsTo(Invoice::class);
      }


      /**
       * Get the validation associated with the InvoiceLine
       *
       * @return \Illuminate\Database\Eloquent\Relations\HasOne
       */
      public function validation(): HasOne
      {
          return $this->hasOne(Validation::class);
      }
    


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

    

    public function get_coverage_class()
    {

      if ($this->invoice && $this->invoice->folder && $this->invoice->folder->validate_at) {
        
        $coverage = $this->validation->coverage;

        switch ($coverage) {
          case '0':
            $class = 'uncovered';
            break;

            case '1':
              $class = 'covered';
              break;
          
          default:
            $class = 'uncovered';
            break;
        }

        return $class;
        
      }

         $prestation =   $this->search_prestation();

  if (!$prestation) {
    $class = 'should_validate';
  }
  else{

    $prestation = $prestation->load(['prestationable']);


    $prestation_type = get_class($prestation->prestationable);
    $provider_category = $this->invoice->folder->slip->provider->provider_category->name;//pharmacie

    if ($prestation_type == Service::class && in_array($provider_category , $this->get_products_providers()) ) {
              
      return 'should_validate';
    
    }

    if ($prestation_type == Product::class && !in_array($provider_category , $this->get_products_providers()) ) {
              
      return 'should_validate';
    
    }
    

    $coverage = $prestation->prestationable->coverage;

    if ($coverage == '0') {
      $class = 'uncovered';

    } 
    elseif($coverage == '1' && $this->check_if_invoice_line_is_conform()){
      $class = 'covered';

    }

    elseif($coverage == '1' && !$this->check_if_invoice_line_is_conform()){
      $class = 'should_validate';

    }
    
    elseif($coverage == '2' ) {
      $class = 'should_validate';

    }
    

  }

    return $class;


    }

    public function get_observation()
    {

      return $this->observation;

    } 

    public function get_matching_prestation(){

      $prestation =   $this->search_prestation();

      if (!$prestation) {
        return 'non reconnue';
      }

      $prestation = $prestation->load(['prestationable']);

      $prestation_type = get_class($prestation->prestationable);
      $provider_category = $this->invoice->folder->slip->provider->provider_category->name;//pharmacie

      if ($prestation_type == Service::class && in_array($provider_category , $this->get_products_providers()) ) {
                
        return 'non reconnue';
      
      }

      if ($prestation_type == Product::class && !in_array($provider_category , $this->get_products_providers()) ) {
                
        return 'non reconnue';
      
      }
     // dd($provider_category);

      return $prestation->name;


    }

    public function check_if_prestation_or_service_exists() : bool {
      

      $prestation =   $this->search_prestation();
    
      if (!$prestation) {
        return false;
      }

      
    
      $prestation = $prestation->load(['prestationable']);

      $prestation_type = get_class($prestation->prestationable);
      $provider_category = $this->invoice->folder->slip->provider->provider_category->name;//pharmacie

      if ($prestation_type == Service::class && in_array($provider_category , $this->get_products_providers()) ) {
                
        return false;
      
      }

      if ($prestation_type == Product::class && !in_array($provider_category , $this->get_products_providers()) ) {
                
        return false;
      
      }


      return true;

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
    public function check_if_prestation_or_service_is_conform() : bool {
      

      $prestation =   $this->search_prestation();
    
      if ($prestation) {
        return true;
      }

      return false;
    
    //  $prestation = $prestation->load('prestationable');
    
    //  $coverage = $prestation->prestationable->coverage;

    }

    public function check_if_invoice_line_is_conform() : bool {
      

      $invoice_line_is_conform = true;
   
      if (!$this->check_if_prestation_or_service_exists() || !$this->check_if_prestation_is_covered() || !$this->check_if_pathology_is_conform() || !$this->check_if_quantity_is_conform() || !$this->check_if_price_is_conform()  ) {
        $invoice_line_is_conform = false;
      }

      return $invoice_line_is_conform;
    
    //  $prestation = $prestation->load('prestationable');
    
    //  $coverage = $prestation->prestationable->coverage;

    }


    public function check_if_prestation_is_covered() : bool {

      return $this->get_coverage() == '1' ;
    }


   

    public function get_default_coverage_text()
    {



      $prestation =   $this->search_prestation();
    
      if (!$prestation) {
        return '';
      }
    
      $prestation = $prestation->load(['prestationable']);

    $prestation_type = get_class($prestation->prestationable);
    $provider_category = $this->invoice->folder->slip->provider->provider_category->name;//pharmacie

    if ($prestation_type == Service::class && in_array($provider_category , $this->get_products_providers()) ) {
              
      return '';
    
    }

    if ($prestation_type == Product::class && !in_array($provider_category , $this->get_products_providers()) ) {
              
      return '';
    
    }
    
      $coverage = $prestation->prestationable->coverage;
    
      if ($coverage == 0 /*|| !$this->check_if_invoice_line_is_conform()*/) {
        return 'Non Couverte';
      }elseif ($coverage == 1) {
        return 'Couverte';
      }
    
      return 'Avis du Medecin';

    }

      public function get_coverage_text()
    {


      if ($this->invoice && $this->invoice->folder && $this->invoice->folder->validate_at) {
        
        $coverage = $this->validation->coverage;

        $text = '';
        switch ($coverage) {
          case '0':
            $text = 'Non Couverte';
            break;

            case '1':
              $text = 'Couverte';
              break;

              case '2':
                $text = 'Avis du medecin';
                break;
          
          default:
            $text = '';
            break;
        }

        return $text;
        
      }



      return $this->get_default_coverage_text();

  


    }

    public function get_coverage()
    {

      if ($this->invoice && $this->invoice->folder && $this->invoice->folder->validate_at ) {

       
        $validation = $this->validation()->first();
      //  dd($this->validation->coverage);
        
        return  $validation->coverage;

      }

        $prestation = $this->search_prestation();


  if (!$prestation) {
    return '';
  }

  $prestation = $prestation->load('prestationable');


  if (!$prestation->prestationable) {
    return '';
  }


  $coverage = $prestation->prestationable->coverage;

  return $coverage;

  


    }

    public function save_query($query, $results , $invoice_line)
    {

      if (Search::whereInvoiceLineId($invoice_line->id)->exists()) {
        
        return null;

      }

     

      try {
        //code...
      
        DB::beginTransaction();
      
      $search_results_array = [];
      
      foreach ($results as $key => $result) {
        
       // dd($results);
        
        $search_result = new SearchResult();
        $search_result->prestation_id = $result->id ;


       // $search_result = [
        //  'prestation_id'=>$result->prestation_id
        //];

     
        array_push($search_results_array , $search_result);
        
      }

     // SearchResult::saveMany($search_results_array);


    //    dd($search_results_array);



      $search = new Search();
      $search->code = Str::random(15);
      $search->query = $query;
      $search->invoice_line_id = $invoice_line->id;
      $search->user_id = session('user')->id;
      $search->save() ;

      $search->search_results()->saveMany($search_results_array);
      //$search->search_results()->save($search_results_array);
       

      DB::commit();

    } catch (\Throwable $th) {
      DB::rollback();
      throw $th;
    }


    }


    public function get_suggest_total($include_uncovered_prices = true)
    {

      $price = $this->get_suggest_price($include_uncovered_prices) ;
      if ($price == '') {
        return 0;
      }
     
      return $price * $this->get_quantity();

    }

    public function search_prestation()
    {

      if ($this->get_sanitized_description() == "" || $this->get_sanitized_description() == null) {

      //  dd($this->get_description());

      //  return null;
      }

      if ($this->get_sanitized_description() == "" || $this->get_sanitized_description() == null) {

       //   dd("Hey");
  
        //  return null;
        }

       // echo $this->get_sanitized_description()."-----------".$this->get_description()."</br>";

      return  $prestation = Prestation::search($this->get_sanitized_description())
      //->with('prestationable')
      ->first();



    }

      public function get_suggest_price($include_uncovered_prices = true)
      {


      
          $prestation =   $this->search_prestation();

    

  if (!$prestation) {

    $this->save_query($this->description , []  , $this);

    return '';
  }

 // dd($prestation);
  $this->save_query($this->description , [$prestation]  , $this);

  
  return $this->get_item_price_suggested($include_uncovered_prices);
        
        
    }


    public function get_gap()  {

      $item_price_suggested = $this->get_item_price_suggested();
      $provider_price =  $this->get_price();

      $gap = 0;

    

      if ($item_price_suggested == 0 || $provider_price == 0) {
        return null;
      }
      $gap =  ((($provider_price - $item_price_suggested))/$provider_price)*100  ;

      return number_format((float)$gap, 2, ',', '');

    }

    public function get_color_gap() : string {


      $gap = $this->get_gap();

     
      return $gap > 15.0 ? 'danger' : 'success' ;

    }


    public function get_item_price_suggested($include_uncovered_prices=true) : float {
      
       $prestation =   $this->search_prestation();

      if (!$prestation ) {
        return 0;
      }



     // dd($include_uncovered_prices);

      $prestation = $prestation->load('prestationable');


      if ( ! $include_uncovered_prices && in_array($prestation->prestationable->coverage ,['0' ] )) {
        return 0;
      }

      
      if (!$this->invoice->prestationable) {
        
        return 0;
      //  dd($this->invoice);
        
      }
      //$prestation_type = $prestation->prestationable->get_prestationable_type();
      
      $prestation_type = $this->invoice->prestationable->get_prestationable_type();
      

        if ($prestation_type == "service") {
           
           

            $quote = $prestation->prestationable->quote;

          //  dd($quote);

            $provider_type = $this->invoice->folder->slip->provider->provider_type;


            if (!$provider_type) {
           //  dd($this->invoice->folder->slip->provider);
            }
            $service_cost = ServiceCost::whereProviderableType(get_class($provider_type))
            ->whereProviderableId($provider_type->id)
            ->wherePrestationId($prestation->id)
            ->first();

            if (!$service_cost) {
             return 0;
            }
      

            $value_letter = $service_cost->value_letter;
            $price = $quote * $value_letter;

          //  return $price;
            
        }
        else{




        
        
        //$quantity = $this->get_quantity();
        if (!$prestation -> prestationable -> price) {
          $price = 0;
        }

     $price = $prestation -> prestationable -> price  ;

      }

      return $price * (1 + .15);

      //  return $this->get_quantity() * $price;

    }


    public function check_if_price_is_conform () {

      return $this->get_gap() <= 15 ; 
    }


    public function check_if_quantity_is_conform () {

      return $this->get_quantity()  <= 10 ; 
      
    }

    public function check_if_pathology_is_conform () {

      return true;//$this->get_quantity()  <= 20 ; 
      
    }


    public function get_suggested_observations()  {
      
      $observations = [];

      if ($this->get_coverage() == 2) {
        return $observations;
      }

      /*if ($this->get_coverage() == 0 ) {//|| !$this->check_if_invoice_line_is_conform()
        $observation = new Observation();
        $observation->title = "prestation non couverte";

        array_push($observations,$observation);
      }*/

      if (!$this->check_if_prestation_or_service_exists()){
        $observation = new Observation();
        $observation->title = "prestation non reconnue";

        array_push($observations,$observation);
        return $observations;
      }

      if (false/*!$this->invoice->folder->pathology->is_prestation_conform($prestation)*/) {
        $observation = new Observation();
        $observation->title = "cette prestation est inadequate pour cette pathologie";

        array_push($observations,$observation);
      }

      if ($this->get_quantity() > 10 /*$this->invoice->folder->pathology->get_average_quantity($prestation)*/) {
        $observation = new Observation();
        $observation->title = "quantités trop élevées pour cette pathologie";

        array_push($observations,$observation);
      }

      if ($this->get_gap() > 15) {
        $observation = new Observation();
        $observation->title = "prix unitaire trop élevé";

        array_push($observations,$observation);
      }
     // dd($observation);

      return $observations;

    }

    

}
