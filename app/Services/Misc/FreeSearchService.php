<?php

namespace App\Services\Misc;

use Faker;
use Throwable;
use App\Models\Slip;
use App\Models\Folder;
use App\Models\Insured;
use App\Models\Provider;
use App\Models\User\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Str;
use App\Jobs\ExtractCaseJob;
use App\Models\Misc\Invoice;
use App\Models\Settings\Area;
use App\Models\Supplier\Order;
use App\Addons\DataConstructor;
use App\Events\FolderExtracted;
use App\Models\Misc\InvoiceLine;
use App\Addons\FileUploadHandler;
use App\Models\Supplier\Supplier;
use Illuminate\Support\Facades\DB;
use App\Addons\Misc\ViewsResponder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Auth;
use App\Models\Transit\TransferOrder;
use Symfony\Component\Process\Process;
use App\Models\Transit\ImportDeclaration;
use App\Services\Misc\UpdloadFileService;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\CreateVariablesResponder;
use App\Addons\Misc\ProductServiceProvider;
use App\Models\Operations\ProviderCategory;
use App\Models\Operations\ProviderType;
use App\Models\Prestations\Drug;
use App\Models\Prestations\Medicine;
use App\Models\Prestations\Prestation;
use App\Models\Prestations\Product;
use App\Models\Prestations\ProductCost;
use App\Models\Prestations\ProductType;
use App\Models\Prestations\Service;
use App\Models\Prestations\ServiceCost;
use App\Models\Prestations\ServiceType;
use App\Models\Prestations\TherapeuticClass;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FreeSearchService 

    {

    use DataConstructor , FileUploadHandler , ProductServiceProvider;


    public function getIndexVariables($type)
    {

        
        switch ($type) {
            case 'analysesbiologiques':
                $categories = [ ["name"=>"ANALYSES BIOLOGIQUES","value"=>"ANALYSES BIOLOGIQUES"],];
                break;
            

                case 'medicament'  :
                    $categories = [ ["name"=>"medicaments et autres consommables","value"=>"PHARMACIE"] ];
                   
                   
                    case 'medicaments'  :
                        $categories = [ ["name"=>"medicaments et autres consommables","value"=>"PHARMACIE"] ];
                     
                        break;

                    

                case 'actesderadiologie':
                    $categories = [ ["name"=>"ACTES DE RADIOLOGIE","value"=>"ACTES DE RADIOLOGIE"] ];
                    break;
                
            default:
                
            $categories = [// ["name"=>"toutes les categories","value"=>"*"],//hopitalzsdz



            //        
            ["name"=>"Toutes les categories","value"=>"*"],
            ["name"=>"ANALYSES BIOLOGIQUES","value"=>"ANALYSES BIOLOGIQUES"],
             //["name"=>"CONSULTATIONS ET VISITES MEDICALES","value"=>"laboratoire"],
             //["name"=>"OPERATION","value"=>"OPERATION"],
             //["name"=>"CONSULTATION GENERALE","value"=>"CONSULTATION GENERALE"],
             //["name"=>"CONSULTATION SPECIALISEE","value"=>"CONSULTATION SPECIALISEE"],
             //["name"=>"HOSPITALISATION","value"=>"HOSPITALISATION"],
             //["name"=>"OPHTALMOLOGIE","value"=>"OPHTALMOLOGIE"],
             //["name"=>"SOINS INFIRMIERS","value"=>"SOINS INFIRMIERS"],
             ["name"=>"ACTES DE RADIOLOGIE","value"=>"ACTES DE RADIOLOGIE"],


             ["name"=>"medicaments et autres consommables","value"=>"PHARMACIE"]];
                break;
        }

        
        return compact('categories');
    }
    
    public function get_search($freesearch_details)
    {

        $prestations = Prestation::search($freesearch_details['query'])
        ->get();


        $prestations = $prestations->load(['prestationable']);

     

        


        if ($prestations -> count()==0) {
            
            return [
                "status" => true,
                "data" => ["results" => [],
            ],
            ];
            
        }


       
       
        
        $results = [];
        

        foreach ($prestations as $key => $prestation) {

           

            $result = [];
            
                $prestation_type = get_class($prestation->prestationable);



                
                $result["name"]=$prestation->name;



  $coverage = $prestation->prestationable->coverage;

  if ($coverage == 0) {

    $result["coverage"]=["status"=> "uncovered" , "coverage" => "Non Couverte"];
    
  }elseif ($coverage == 1) {

    $result["coverage"]=["status"=> "covered" , "coverage" => "Couverte"];
  
  }
  else{

    $result["coverage"]=["status"=> "waiting" , "coverage" => "Avis du medecin"];

  }

 
  $category = $freesearch_details["category"];

                if ($prestation_type == Service::class && (($category != "*" && $category == $prestation->prestationable->service_type()->first()->get_fullname()) || $category == "*" ) ) {
                   

                   // dd($prestation->prestationable->service_type->get_fullname());

        $prestation->prestationable = $prestation->prestationable->load(['service_type']);

      //  return $prestation->prestationable->service_type->get_fullname();

                    $result["prestation_type"]=$prestation->prestationable->service_type->get_fullname();
                    /*
                    $quote = $prestation->prestationable->quote;

                    //  dd($quote);
          
                   ///   $provider_type = $this->invoice->folder->provider->provider_type;
          
                      $service_cost = ServiceCost:://whereProviderableType(get_class($provider_type))
                    //  ->whereProviderableId($provider_type->id)
                      wherePrestationId($prestation->id)
                      ->first();
          
                 // dd($service_cost);
          
                      $value_letter = $service_cost->value_letter;
                      $price = $quote * $value_letter;
                      */

                      array_push($results , $result);

                    
                } 
                
                
                else if($prestation_type == Product::class  ) {



                    if ( ( $category != "*" && $category == $prestation->prestationable->product_type()->first()->get_fullname() ) ||  $category == "*" ) {

                        $prestationable = $prestation->prestationable->load(['family']);
                        $prestation->prestationable = $prestationable;
    
            
                        $result["prestation_type"]=Str::upper($prestation->prestationable->family->name);
    
                        array_push($results , $result);
                    }

                   // $result["prestation_type"]="MEDICAMENT";// $prestation->prestationable->get_utility();

                  
                    
                    
                }
                


                

            
        }



       return [
        "status" => true,
        "data" => ["results" => $results,
    ],
    ];



    /*
     * 
     * 
     * $prestation = $prestation->load(['prestationable']);

      $prestation_type = get_class($prestation->prestationable);
      $provider_category = $this->invoice->folder->slip->provider->provider_category->name;//pharmacie

      if ($prestation_type == Service::class && in_array($provider_category , $this->get_products_providers()) ) {
                
        return 'non reconnue';
      
      }

      if ($prestation_type == Product::class && !in_array($provider_category , $this->get_products_providers()) ) {
                
        return 'non reconnue';
      
      }
     */


    }
    
    
    
    public function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }


    public function createServiceCost(array $service_cost_details)
    {
        try {


            DB::beginTransaction();

            /*if (
                Supplier::select("id")
                    ->where("social_reason", $service_cost_details["social_reason"])
                    ->first()
            ) {
                return [
                    "status" => false,
                    "errors" => ["social_reason" => ["Ce client existe deja"]],
                ];
            }*/

            $value_letters = $service_cost_details["value_letters"];

            $prestation = new Prestation();
            //$product = new Product();

            $service = Service::whereName($service_cost_details["service_name"])->first();
            $service_type = ServiceType::whereCode($service_cost_details["service_type"])->first();

            if ( ! $service) {


                $service = new Service();
                $service ->code = Str::random(5);
                $service -> name = $service_cost_details["service_name"];
                $service -> quote = $service_cost_details["quote"];
                $service -> coverage = $service_cost_details["coverage"];
                
                $service_type->services()->save($service);
                 
                /*return [
                    "status" => false,
                    "errors" => ["service_name" => ["Cette prestation n'existe pas dans le systeme"]],
                ];*/


            }

            foreach ($value_letters as $key => $value_letter) {
                
            $provider_type = ProviderType::whereSlug($value_letter["provider_type"])->first();
                
            
            //$provider_type = ProviderType::whereCode($service_cost_details["provider_type"])->first();
            $current_user = User::find($service_cost_details["token"]);


            if ( ! $provider_type) {
                return [
                    "status" => false,
                    "errors" => ["provider_type" => ["Ce type de prestataire n'existe pas dans le systeme :".($value_letter["provider_type"])]],
                ];
            }

            

          


            $prestation->code = Str::random(5);
            $prestation->name = $service_cost_details["service_name"];
            //$prestation->coverage = $service_cost_details["coverage"];
            //$prestation->user_id = $current_user->id;
            
            //$product->code = Str::random(5);

            //  $grug = new Drug();

            //$medicine->therapeutic_classes()->attach($therapeutic_class->id, ['price' => $service_cost_details["price"]]);

           

            //$drug->product()->save($product);

           // $service->prestation()->save($prestation);

            $prestation->prestationable_type = get_class($service);
            $prestation->prestationable_id = ($service -> id);

            $prestation->save();

            $service_cost = new ServiceCost();
            $service_cost -> providerable_type = get_class($provider_type);
            $service_cost -> providerable_id = $provider_type->id;
            $service_cost -> prestation_id = $prestation->id;
            $service_cost -> value_letter = $value_letter["value_letter"];
            $service_cost -> save();


          //  $prestation->searchable();

        }

            
            //$prestation->service_cost()->save($service_cost);

          //  $provider->providerable()->save();
        

           // $product_cost = new ProductCost();



            /*
            $nullbable_coloumns = $this->nullbable_coloumn;

            foreach ($nullbable_coloumns as $nullbable_coloumn) {
                array_key_exists($nullbable_coloumn, $service_cost_details)
                    ? ($supplier->{$nullbable_coloumn} =
                        $service_cost_details[$nullbable_coloumn])
                    : null;
            }

        $supplier = $this->fillFields($supplier, $nullbable_coloumns, $service_cost_details);
        */


          //  $current_user->suppliers()->save($supplier);

            

            DB::commit();

            return [
                "status" => true,
                "data" => ["service_cost" => $service_cost,
            ],
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteSupplier($supplier)
    {
        $result = $supplier->delete();

        if ($result) {
            return [
                "status" => true,
                "success" => [
                    "deleting" => ["Suppression effectuee avec succes"],
                ],
            ];
        }

        return [
            "status" => false,
            "errors" => [
                "deleting" => ["Erreur survenue lors de la suppression"],
            ],
        ];
    }

   
}
