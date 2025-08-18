<?php

namespace App\Services;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Addons\DataConstructor;
use App\Addons\FileUploadHandler;
use Illuminate\Support\Facades\DB;
use App\Addons\Misc\ViewsResponder;
use Illuminate\Support\Facades\Auth;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\CreateVariablesResponder;
use App\Models\Category;
use App\Models\Misc\Invoice;
use App\Models\Operations\Provider;
use App\Models\Operations\ProviderCategory;
use App\Models\Operations\ProviderType;
use App\Models\Settings\Area;
use App\Models\provider\Order;
use App\Models\Transit\ImportDeclaration;
use App\Models\Transit\TransferOrder;

class ProviderService implements
  //  IndexVariablesResponder,
    CreateVariablesResponder,
    ShowVariablesResponder,
    EditVariablesResponder,
    ViewsResponder

    {

    use DataConstructor , FileUploadHandler ;

    protected $nullbable_coloumn = [
        "name",
        "activity_area_id",
        "activities",
        "city_id",
        "area_id",
        "address",
        "unique_identification_number",
        "main_phone_number",
        "auxiliary_phone_number",
        "whatsapp_phone_number",
        "email",
        "begining_collaboration",
    ] ;

    protected $resource_types = [


    //  TransferOrder::class =>"Ordre de transfert",//  "App\Models\Transit\TransferOrder"	
     //   ImportDeclaration::class => "Declaration d'importation",
	    Invoice::class => "Facture",
      //  ['name'=> 'Order de transfert', 'slug'=>'transfer_order' ,],
        ];

    public function getIndexVariables($results)
    {//ROYAL PETRO OIL REFINERY LLP
        $providers = Provider:: orderBy('name')
       // ->with(["user" , "logo"])
        ->paginate($results)
        ->withQueryString();

      //  dd($providers);
       
       $total = 0;

        

        $title = "Liste des prestataires";
        $modal_url = url("/");
        $resource=Invoice::class;
        $partner=provider::class;

        $vars = compact("total", "providers","title","modal_url","resource","partner");

        return $vars;
    }
    public function getCreateVariables()
    {
        $provider = null;
        $action = "create";
        $categories = ProviderCategory::all();
        $types = ProviderType::all();
        $disabled = "";
        $readonly = "";
        $instance_type = Provider::class;

        return compact(
            "provider",
            "categories",
            "types",
            "action",
            "disabled",
            "readonly",
            "instance_type",
        );
    }
    public function getShowVariables($provider)
    {
        $provider->load(["extraction_setting"]);
        $action = "show";
        $disabled = "disabled=disabled";
        $readonly = "readonly";
        $categories = ProviderCategory::all();
        $instance_type = provider::class;
        $resource_types = $this->resource_types;

     //   dd($provider);

        return compact(
            "provider",
            "resource_types",
            "action",
            "disabled",
            "readonly",
            "instance_type",
            "categories"
            
        );
    }
    public function getEditVariables($provider)
    {
        $provider->load(["extraction_setting"]);
        $action = "update";
        $disabled = "";
        $readonly = "";
        $categories = ProviderCategory::all();
        $types = ProviderType::all();
        $instance_type = provider::class;

        return compact(
            "provider",
            "action",
            "disabled",
            "readonly",
            "instance_type",
            "categories",
            "types"
            

        );
    }
    public function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }

    public function deleteprovider($provider)
    {
        $result = $provider->delete();

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

    public function createProvider(array $provider_details)
    {
        try {
            DB::beginTransaction();

             $category =   ProviderCategory::whereCode($provider_details["category"])->first();
          $type =   ProviderType::whereCode($provider_details["type"])->first();
            if (
                Provider::select("id")
                    ->where("name", $provider_details["name"])
                    ->first()
            ) {
                return [
                    "status" => false,
                    "errors" => ["name" => ["Ce prestataire existe deja"]],
                ];
            }


            if (!$category) {
               
                return [
                    "status" => false,
                    "errors" => ["category" => ["Cette categorie n'existe pas dans le systeme"]],
                ];
            }

            if (!$type) {
               
                return [
                    "status" => false,
                    "errors" => ["type" => ["Ce type n'existe pas dans le systeme"]],
                ];
            }

            $provider = new Provider();
        $file = null;

            $provider->code = Str::random(5);
            $provider->name = $provider_details["name"];
            $provider->provider_category_id = $category->id;
            $provider->provider_type_id = $type->id;

            $nullbable_coloumns = $this->nullbable_coloumn;

            foreach ($nullbable_coloumns as $nullbable_coloumn) {
                array_key_exists($nullbable_coloumn, $provider_details)
                    ? ($provider->{$nullbable_coloumn} =
                        $provider_details[$nullbable_coloumn])
                    : null;
            }

        $provider = $this->fillFields($provider, $nullbable_coloumns, $provider_details);


           // $current_user = User::whereCode($provider_details["token"])->first();
           // $current_user->providers()->save($provider);

           $provider->save();


           

            if (isset($provider_details["logo"])) {

                $logo = $provider_details["logo"];

                $file = $this->handleFileUpload(
                    $provider,
                    $logo,
                    Auth::guard("sanctum")->user()->id,
                    storage_path("app/public/provider_images"),
                    "logo"
                );
        
                $provider->logo()->save($file);
            }

            DB::commit();

            return [
                "status" => true,
                "data" => ["provider" => $provider,
                "logo" => $provider->logo,
                "newfile" => $file,
            ],
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateprovider(array $provider_details, $request)
    {
        $provider = provider::whereCode($provider_details["provider"])->first();

        $file = null;
     
        $nullbable_coloumns = $this->nullbable_coloumn ;

        $provider = $this->fillFields($provider, $nullbable_coloumns, $provider_details);

        $provider->save();

        $logo = $request["logo"];

        if ($logo) {

            $file = $this->handleFileUpload(
                $provider,
                $logo,
                Auth::guard("sanctum")->user()->id,
                storage_path("app/public/provider_images"),
                "logo"
            );
    
            $provider->logo()->save($file);
        }

       

        return [
            "status" => true,
            "data" => [
                "provider" => [$provider->code],
                "logo" => $provider->logo,
                "newfile" => $file,
            ],
        ];
    }
}
