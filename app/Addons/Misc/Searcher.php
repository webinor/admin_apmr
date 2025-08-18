<?php
namespace App\Addons\Misc;

use Illuminate\Support\Str;
use App\Models\Operations\Folder;
use App\Models\Prestations\ProductType;
use App\Models\Prestations\ServiceType;

Trait Searcher{

    public function searchInvoices( $query_search)
    {
   


        
        preg_match('#\((.*?)\)#', $query_search, $match);

      //  dd(Str::of($match[1])->trim());

      $doc_name = isset($match) && isset($match[1]) ? Str::of($match[1])->trim() : "";

      $query = Folder::query();
        
      if ($doc_name != "") {
        
        $query ->whereDocName($doc_name);

      }
       $folders = $query->whereHas('invoices',function($query) use ($query_search){
            $query->whereReference(substr($query_search, 0, 8));
        })
        ->with([
        "validator.user.employee",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
       // ->whereSlipId($slip->id)
        ->paginate(10)
        ->withQueryString();

      //  dd($folders);

      /*  $folders_success = Folder::
        whereDocName($doc_name)
        ->orWhereHas('invoices',function($query) use ($query_search){
            $query->whereReference(substr($query_search, 0, 8));
        })
        ->with([
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
      //  ->whereIdentification($query)
        ->whereStatus(1)
        ->paginate(10)
        ->withQueryString();

        $folders_reject = Folder::
        whereDocName($doc_name)
        ->orWhereHas('invoices',function($query) use ($query_search){
            $query->whereReference(substr($query_search, 0, 8));
        })
        ->with([
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
       // ->whereIdentification($query)
        ->whereStatus(0)
        ->paginate(1)
        ->withQueryString();*/

       

        $service_types  = ServiceType::select('id','code','name','fullname')->get();
        $product_types  = ProductType::select('id','code','name','fullname')->get();

       // $folders = $slip->folders;
       
       // dd($folders);
      //  $services = $slip->invoices('service_id')->distinct()->get();

      //  dd($services);

      $header_title = "Supprimer une facture";

      $query_label = "Reference ou nom de l'assuré";


      $typeahead_url =null;// url("/api/getInvoices?slip=".($slip->code));

        return compact(
            "slip",
            "query_label",
            "folders",
           // "folders_success",
           // "folders_reject",
            "service_types",
            "product_types",
            "typeahead_url",
            "header_title"
        );
    }
}


?>
