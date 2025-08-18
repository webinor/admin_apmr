<?php
namespace App\Services\Misc;


use Faker;
use Throwable;
use App\Models\Insured;
use App\Models\Services\Service;
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
use App\Addons\Misc\Searcher;
use App\Models\Misc\Seen;
use App\Models\Operations\Folder;
use App\Models\Operations\Provider;
use App\Models\Operations\Slip;
use App\Models\Prestations\ProductType;
use App\Models\Prestations\ServiceType;
use App\Services\Misc\FolderService;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;

class  CheckService  implements
    IndexVariablesResponder,
    CreateVariablesResponder,
   // ShowVariablesResponder,
    EditVariablesResponder,
    ViewsResponder

    {

    use DataConstructor , FileUploadHandler , Searcher ;

   

    public function getIndexCheck($results  = 10 , $display = "unprocessed"){

        $current_user = session('user') ? session('user') : Auth::guard('sanctum')->user();

       

        if (!session('user')) {
            // dd($current_user->isValidator());
        }

        $slips = [];

       /* $query = Slip:://query()
        //->
        oldest()
        //->whereUserId($current_user->id)
        ->whereHas('folders',function($query){
            $query->whereSaveAt(null);
        })
        ->with(["provider:id,code,name", "seen", "user.employee", 
        "folders.invoices.invoice_lines"
        
        ])
        ->paginate($results)
        ->withQueryString();
       ;*/

     //  dd($results);
    
        if ($current_user->isExtractor()) {
          
         

        }elseif($current_user->isValidator()){


            //dd($slips);
            // dd($slips[1]->folders[50]);

        }
        elseif($current_user->isAdministrator()){


        

        }


        $invoice_lines = InvoiceLine::latest()
        ->with([
            "invoice:id,code,reference,prestationable_type,prestationable_id,folder_id",
            "invoice.folder:id,code,doc_path,s3_path,doc_name,identification,slip_id",
            "invoice.folder.slip:id,code,identification,provider_id,user_id",
            "invoice.folder.slip.provider:id,code,name,provider_category_id,provider_type_id",
            "invoice.folder.slip.provider.provider_category:id,code,name",
            "invoice.folder.slip.provider.provider_type:id,code,name,slug",
          //  "validation:id,suggested_quantity,validated_quantity,suggested_price,validated_price,coverage",
          //  "invoice.invoice_lines.invoice.prestationable",
          //  "invoice.invoice_lines.invoice.folder",
            "invoice.prestationable"
            ])
         //   ->distinct('description')
            
            ->paginate($results)
            ->withQueryString();
            

       //  dd($invoice_lines);

        return $invoice_lines;
    }


    public function getIndexVariables($results = 10 , $display = "unprocessed")
    {
       
        
     //  dd($results);

       $invoice_lines = $this->getIndexCheck($results , $display);

        $typeahead_url = url("/api/getSlips");

        $query_label = "Numero de bordereau";

        $header_title = "Supprimer le bordereau";

        $slip = null;
      

        $tab_titles = $this->get_tab_titles("slip");
        $pending_title = $tab_titles["pending"];
        $executed_title = $tab_titles["executed"];

        $folder=null;


        
     


        $vars = compact("invoice_lines","slip","folder","typeahead_url","query_label" ,"header_title" , "pending_title","executed_title");

        return $vars;
    }
 
    public function get_tab_titles($menu){

        $current_user = session('user');

        if ($current_user->isExtractor()) {
  
            if ($menu == "slip") {
                $pending = "Bordereaux en attente de traitement";
                $rejected = "";
                $executed = "Bordereaux Traités";
            }
            elseif ($menu == "folder") {
                $pending = "Extractions reussies";
                $rejected = "Extractions rejetées";
                $executed = "Dossiers Traitrés";
            }
          
  
        } elseif($current_user->isValidator()) {
           
            if ($menu == "slip") {
                $pending = "Bordereaux en attente de validation";
                $rejected = "";
                $executed = "Bordereaux validés";
            }
            elseif ($menu == "folder") {
               // $pending = "Pré-validations réussies";
                $pending = "Dossiers à priori conformes";
                $rejected = "Bordereaux rejetées";
                $rejected = "Dossiers non conformes";
                $executed = "Dossiers validés";
            }
          
  
        }

        return ["pending"=>$pending,"executed"=>$executed , "rejected"=>$rejected];
    }

    public function searchCheck($query_search , $results = 10)
    {
       
        $current_user = session('user');

        $slips = [];

        if ($current_user->isExtractor()) {
          
            $slips = Slip::oldest()
            /*->whereHas('folders',function($query) use ($current_user){
                //$query->whereReference(substr($current_user, 0, 8));
                $query//->whereUserId($current_user->id);
            })*/
            //->whereUserId($current_user->id)
            ->whereIdentification($query_search)
            ->with(["provider:id,code,name", "seen", "user.employee", 
            "folders.invoices.invoice_lines"])
            ->paginate(1)
            ->withQueryString();

        }elseif($current_user->isValidator()){

            $slips = Slip::oldest()
            ->whereIdentification($query_search)

          //  ->whereOpenBy($current_user->id)
          //  ->orWhere("open_by" , null)
            ->with(["provider:id,code,name", "seen", "user.employee" , 
            "folders.invoices.invoice_lines"])
            ->paginate(1)
            ->withQueryString();

        }
        elseif($current_user->isAdministrator()){


            $slips = Slip::oldest()
            ->whereIdentification($query_search)

            ->with(["provider:id,code,name", "seen", "user.employee" , 
            "folders"])
            ->paginate(1)
            ->withQueryString();

        }

        $tab_titles = $this->get_tab_titles("slip");
        $pending_title = $tab_titles["pending"];
        $executed_title = $tab_titles["executed"];
       

        $typeahead_url = url("/api/getSlips");

        $query_label = "Numero de bordereau";

        $header_title = "Supprimer le bordereau";

        $vars = compact("slips","typeahead_url","query_label","header_title" , "pending_title" , "executed_title");

        return $vars;
    }

    
    public function getCreateVariables()
    {
        $folder = null;
        $action = "create";
        $disabled = "";
        $readonly = "";
        $service_types  = ServiceType::select('id','code','name','fullname')->get();
        $product_types  = ProductType::select('id','code','name','fullname')->get();


        return compact(
            "folder", 
            "action",
            "service_types",
            "product_types",
            "disabled",
            "readonly",
        );
    }




    public function get_filtered_invoice_lines(Request $filters)  {
        


     //   dd($slip);
       

      

             $query = InvoiceLine::query()
             
        ->with([
            "invoice:id,code,reference,prestationable_type,prestationable_id,folder_id",
            "invoice.folder:id,code,doc_path,s3_path,doc_name,identification,slip_id",
            "invoice.folder.slip:id,code,identification,provider_id,user_id",
            "invoice.folder.slip.provider:id,code,name,provider_category_id,provider_type_id",
            "invoice.folder.slip.provider.provider_category:id,code,name",
            "invoice.folder.slip.provider.provider_type:id,code,name,slug",
            "validation:id,suggested_quantity,validated_quantity,suggested_price,validated_price,coverage",
          //  "invoice.invoice_lines.invoice.prestationable",
          //  "invoice.invoice_lines.invoice.folder",
            "invoice.prestationable"])
            ->distinct();
           
           


        
            $invoice_lines = $query->get();

        

        
        
      
    
    
            if (($filters->input('prestations-exist'))) {
            
                $invoice_lines = $invoice_lines->filter(function ($invoice_line, $key) {
                    return !$invoice_line->check_if_prestation_or_service_exists();
                });
                
            }


            if (($filters->input('should-be-validated'))) {
            
                $invoice_lines = $invoice_lines->filter(function ($invoice_line, $key) {
                    return $invoice_line->should_be_validated() == '2';
                });
                
            }

            

        
        
        if (($filters->input('conform-price'))) {
            
            $invoice_lines = $invoice_lines->filter(function ($invoice_line, $key) {
                
                $item_price_suggested = $invoice_line->get_item_price_suggested();
                $provider_price =  $invoice_line->get_price();

                if ($item_price_suggested > 0 && ($provider_price > ($item_price_suggested * (1+0.15)))) {
                    // dd($invoice_line) ;
                            return  false;
                            
                         }
                     

            });

        }

        $min_price = $filters->input('min-price') ? $filters->input('min-price') : 0;
        $max_price = $filters->input('max-price') ? $filters->input('max-price') : 200000;
        
        $invoice_lines = $invoice_lines->filter(function ($invoice_line, $key) use ($min_price,$max_price) {
            return $invoice_line->get_amount() >= $min_price &&  $invoice_line->get_amount() <= $max_price;
        });

       

       

     

        if (($filters->input('lock-filter'))) {
            # code...
        }

         
     return   $invoice_lines = $invoice_lines->all();
        
        
      
        


    }


    public function getShowVariables( $results = 10 , Request $request)
    { 

     



        $filtered_invoice_lines = collect([]);

      //  dd($slip);

        

      /*  $folders = Folder::with([////"provider",

            "seen:id,seenable_type,seenable_id,user_id",
            "validator:id,seenable_type,seenable_id,user_id",
            "validator.user:id,employee_id",
            "validator.user.employee:id,role_id,first_name,last_name",
            "user:id,employee_id",
            "user.employee:id,role_id,first_name,last_name",
            "invoices.invoice_lines.validation",
            "invoices.invoice_lines.invoice.prestationable",
            "invoices.invoice_lines.invoice.folder.slip.provider.provider_type",
            "invoices.prestationable"])
           
            
            ->paginate($results)
            ->withQueryString();*/
       
        
         
       
       
            $folder_service = new FolderService();

              $filtered_invoice_lines = $this->get_filtered_invoice_lines($request , null);

              
           //   dd($filtered_invoice_lines);

      
              $invoice_lines = $folder_service->get_pagination($request , $filtered_invoice_lines);
        

            //  dd($invoice_lines);

     

        /*
        $folders_success = Folder::with([////"provider",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
        ->whereStatus(1)
        ->paginate($results)
        ->withQueryString();

        $folders_reject = Folder::with([////"provider",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
        ->whereStatus(0)
        ->paginate($results)
        ->withQueryString();

        */

     //   dd($folders_success);


       

        $service_types  = ServiceType::select('id','code','name','fullname')->get();
        $product_types  = ProductType::select('id','code','name','fullname')->get();

        $folders = null;// $slip->folders;
       
       // dd($folders);
      //  $services = $slip->invoices('service_id')->distinct()->get();

      //  dd($services);

      $typeahead_url = null;// url("/api/getInvoices?slip=".($slip->code));

      $query_label = "Reference ou nom de l'assuré";

      $header_title = "Supprimer une facture";


      $current_user = Auth::user();

      $tab_titles = $this->get_tab_titles("folder");

      $pending_title = $tab_titles["pending"];
        $rejected_title = $tab_titles["rejected"];
        $executed_title = $tab_titles["executed"];

        $slip =null;
        $folder =null;
      /*
      if (!Seen::whereSeenableType(Slip::class)->whereSeenableId($slip->id)//->whereUserId($current_user->id)->exists()) {
          # code...
          $view = new Seen();
          $view->user_id = $current_user->id ;
          $view->seenable_type = Slip::class ;
          $view->seenable_id = $slip->id ;
          $view->save() ;
      }
      */
     //->whereOpenBy($current_user->id)
            //->orWhere("open_by" , null)

       
        return compact(
            "slip",
            "pending_title",
            "rejected_title",
            "executed_title" ,
            "query_label",
            "folder",
            "invoice_lines",
          //  "folders_success",
          //  "folders_reject",
            "service_types",
            "product_types",
            "typeahead_url",
            "header_title"
        );
    }

    public function getInvoices($slip)
    {

      return  $folders = Folder::with([//"provider",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
        ->get();

    }

   
    public function getEditVariables($supplier)
    {
        $supplier->load(["logo", "documents"]);
        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "supplier",
            "action",
            "disabled",
            "readonly",
            "areas"
        );
    }
    public function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }

    public function getSlips($slip_data){

      
        $slips = $this->getIndexCheck();

      //  dd($slips->items());

        $details = [];

        foreach ($slips->items() as  $slip) {
        
         //   dd($slip);

        

            array_push(
                $details,
                Str::upper($slip["identification"])// .
                 //   " ( " .
                 //   $folder["doc_name"] .
                 //   " )"
            );
            
        }

        // dd($details);

        //  $sorted = collect($details)->sort();

        //$sorted->values()->all();

        sort($details);

        return $details;

    }

    public function deleteSlip($slip)
    {
        // $folder = folder::whereCode($folder_details['folder'])->first();

        if (!$slip) {
            return [
                "status" => false,
                "errors" => [
                    "model" => ["Dossier invalide"],
                ],
            ];
        }

        


    

        try {
            //code...

            DB::beginTransaction();

        //$result = $slip->delete();
        
        $time = 1;

        if (true) {


            $slip->delete();

            $response =  [
                "status" => true,
                "success" => "Dossier supprimé avec succès, la page rechargera dans $time seconde(s)",
                "time" => $time,
            ];
        }
        else{

            $response =  [
                "status" => false,
                "errors" => [
                    "deleting" => ["Erreur survenue lors de la suppression"],
                ],
            ];

        }


        DB::commit();

        return $response ;


     


          

        } catch (\Throwable $th) {
            DB::rollback();
                throw $th;
        }
    }



   
}
