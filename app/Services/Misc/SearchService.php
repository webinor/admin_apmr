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
use App\Models\Misc\Search;
use App\Models\Operations\Folder;
use App\Models\Operations\Provider;
use App\Models\Operations\Slip;
use App\Models\Prestations\Prestation;
use App\Models\Prestations\ProductType;
use App\Models\Prestations\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SearchService implements
   // IndexVariablesResponder,
    CreateVariablesResponder,
    ShowVariablesResponder,
    EditVariablesResponder,
    ViewsResponder

    {

    use DataConstructor , FileUploadHandler ;

   



    public function getSearches($slip_data)
    {  

      
        
        $searchs = Search::select("query")
        ->whereDoesntHave('search_results',function($query) {
           // $query->where('save_at' , null);
       })
     
     /*  ->with(['invoice_line:id,invoice_id'
       ,'invoice_line.invoice:id,prestationable_type,prestationable_id',
       'invoice_line.invoice.prestationable:id,code,fullname',
       "user.employee" , 
        "search_results",])*/
      //  ->paginate($results)
        ->get();


        $details = [];

        foreach ($searchs as  $search) {
        
         //   dd($search);

        

            array_push(
                $details,
                Str::upper($search->query)// .
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

    public function getIndexVariables($results = 10 , $query=null , Request $request)
    {
       

        if (!$query) {
            
              $searchs = Search::oldest()
        ->whereDoesntHave('search_results',function($query) {
           // $query->where('save_at' , null);
       })
     
       ->with(['invoice_line:id,invoice_id'
       ,'invoice_line.invoice:id,prestationable_type,prestationable_id',
       'invoice_line.invoice.prestationable:id,code,fullname',
       "user.employee" , 
        "search_results",])
        ->get();
      ///  ->paginate($results)
      //  ->withQueryString();
            

        } else {
            
              $searchs = Search::oldest()
        ->whereDoesntHave('search_results',function($query) {
           // $query->where('save_at' , null);
       })
     ->where('query','like',"%$query%")
       ->with(['invoice_line:id,invoice_id'
       ,'invoice_line.invoice:id,prestationable_type,prestationable_id',
       'invoice_line.invoice.prestationable:id,code,fullname',
       "user.employee" , 
        "search_results",])
        ->get();
        ///  ->paginate($results)
        //  ->withQueryString();
            

        }


      //  dd($searchs);

        
 

$filtered = $searchs->filter(function ($value, $key) {
    //dd($value->query);
    return !Prestation::search($value->query)->first() ;
});
 
$filtered = $filtered->all();
      

      //  dd($dd);


            
       $currentPage = $request->page ?? 1;
       $itemsPerPage = $results ;
       
       // You should manually make a slice of array for current page
       $items = array_slice($filtered, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
       //$items = $filtered->forPage($currentPage, $itemsPerPage);
       //$pagination = new LengthAwarePaginator($items, count($filtered), $itemsPerPage, $currentPage);
            
       $searchs= new LengthAwarePaginator($items, count($filtered), $itemsPerPage, $currentPage, [
           'path' => $request->url(),
           'query' => $request->query(),
       ]);

      //  $searchs = $this->getIndexSearchs($results , $display);

        $typeahead_url = url("/api/getSearches");

        $query_label = "Prestation ou médicament";

        $header_title = "Supprimer le bordereau";

       // dd($searchs);
     //   $folder=Folder::find(28);
     
      /**return  $folder->load(["user:id,code",
       "insured:id,code,name,beneficiary",
       "provider:id,code,name,address,category_id",
       "provider.category:id,code,name,slug",
       "invoices:id,code,reference,index_page,service_id,folder_id",
       "invoices.service:id,code,name",
       "invoices.invoice_lines:id,code,invoice_id,description,quantity,price"
       ]);/**/

 
        $vars = compact("searchs","typeahead_url","query_label");

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
    public function getShowVariables($slip)
    {
       // dd($slip);
       /* $slip->with([
        "folders.user.employee",
        "folders.provider",
        "folders.invoices.invoice_lines.invoice.prestationable",
        "folders.invoices.invoice_lines.invoice.folder.slip",
        "folders.invoices.prestationable"])
        ->paginate(10)
        ->withQueryString();*/

        $folders = Folder::with(["provider",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
        ->paginate(10)
        ->withQueryString();

        $service_types  = ServiceType::select('id','code','name','fullname')->get();
        $product_types  = ProductType::select('id','code','name','fullname')->get();

       // $folders = $slip->folders;
       
       // dd($folders);
      //  $services = $slip->invoices('service_id')->distinct()->get();

      //  dd($services);

        return compact(
            "slip",
            "folders",
            "service_types",
            "product_types"
        );
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
