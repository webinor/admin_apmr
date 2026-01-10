<?php

namespace App\Services\Misc;

use App\Models\Misc\Tax;
use App\Models\User\User;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Str;
use App\Models\Misc\Invoice;
use App\Addons\ValidatorData;
use App\Models\Misc\Currency;
use App\Models\Misc\Resource;
use App\Models\Sales\Customer;
use App\Models\Supplier\Order;
use App\Addons\DataConstructor;
use App\Models\Misc\InvoiceLine;
use App\Addons\FileUploadHandler;
use App\Addons\Misc\FieldsFiller;
use App\Models\Supplier\Supplier;
use Illuminate\Support\Facades\DB;
use App\Addons\Misc\ViewsResponder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Settings\PaymentMethod;
use App\Models\Settings\ValidationType;
use App\Models\Transit\ImportDeclaration;
use Illuminate\Notifications\Notification;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Addons\Misc\CreateVariablesResponder;
use App\Models\Company;
use App\Models\Operations\Folder;
use App\Models\Prestations\ProductType;
use App\Models\Prestations\ServiceType;
use Illuminate\Http\Request;

class InvoiceService 
{
    use DataConstructor , FileUploadHandler  ;

     function getIndexVariables(Request $request)
    {

        $companies = Company::orderBy("name")->get();
          $invoices = Invoice::with(['company:id,name,city_id','invoicer'])->get();

        //   dd($invoices);

         $vars = compact(
            "companies","invoices"
        );

        return $vars;

    }

        function getEditVariables(Request $request , Invoice $invoice)
    {

       
        $invoice->load(['invoice_lines','company.wheel_chairs']);

        $companies = Company::orderBy("name")
            ->get();

       
       
        $action = "update";
        $disabled =  "";
        $readonly =  "";
        $invoice_url = "";

        //   dd($invoices);

         $vars = compact(
        "invoice",
        "invoice_url",
        "companies",
        "action",
        "disabled",
        "readonly"
        );

        return $vars;

    }

      function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }


    public function update_reference($invoice_data){


        try {
            

            DB::beginTransaction();

            $reference = $invoice_data['reference'];
            $invoice = invoice::whereCode($invoice_data['invoice'])->first();


            $invoice->reference = Str::upper($reference);
            $invoice->save();

            DB::commit();

             

            return [
                "status" => true,
                "success" => [
                    "data" => ["Sauvegarde effectuee avec succes"],
                ],
            ];

        
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }


    } 

    public function preview($data){

    }


        public function deleteInvoice(Invoice $invoice)
    {
        try {
            DB::beginTransaction();
            //$invoice->is_active = false;
            $invoice->delete();

            //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////

            DB::commit();

            return [
                "status" => true,
                //"data" => $invoice,
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

}
