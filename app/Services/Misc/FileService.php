<?php

namespace App\Services\Misc;

use App\Models\Misc\Bank;
use Illuminate\Support\Str;
use App\Addons\Instanciator;
use App\Models\Misc\Resource;
use App\Addons\DataConstructor;
use App\Models\Extract;
use App\Models\Misc\Currency;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\Transfert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FileService
{
    use DataConstructor;


    public function UpdloadFile($file_data , $key , $folder)
    {

        $filesArray = [];

        $upload_file_service = new UpdloadFileService();

        $files = [$file_data[$key]];

      //  dd($files);
      //  dd($file_data);
        foreach ($files as $file) {
            

            $newfile = $upload_file_service->getFileInstance($file);

            $upload_file_service->execute(
                $file,
                storage_path("app/public/$folder"),
                $newfile->path
            );

            if (isset($request["validity"])) {
                $file_data += [$request["validity"]];
            }

            

            $newfile = $this->fillModel($file_data, $newfile);

            array_push($filesArray, $newfile);
        }


        return $filesArray;


    }
    public function createFile($request)
    {
        try {
            DB::beginTransaction();

            $filesArray = [];
            $upload_file_service = new UpdloadFileService();
            $resource = new Resource();
            $instanciator = new Instanciator();
            $user = Auth::guard("sanctum")->user();
            $param = "";

            $file_data = ["user_id" => $user->id];
 
            if (array_key_exists("files", $request)) {
                $files = $request["files"];

                foreach ($files as $file) {
                    $newfile = $upload_file_service->getFileInstance($file);

                    $upload_file_service->execute(
                        $file,
                        storage_path("app/public/ad_images"),
                        $newfile->path
                    );

                    if (isset($request["validity"])) {
                        $file_data += [$request["validity"]];
                    }

                    if (isset($request["doc_type"])) {
                        $documentable = $instanciator->apply(
                            $request["doc_type"],
                            "Misc"
                        );
                        $file_data += [
                            "documentable_type" => get_class($documentable),
                            "documentable_id" => $documentable->id,
                        ];
                    }

                    $newfile = $this->fillModel($file_data, $newfile);

                    array_push($filesArray, $newfile);
                }
            } else {
                $columns = [
                    "transfer_order",
                    "transfer_prints",
                    "import_declaration",
                    "domiciliation_certificate",
                    "tax_compliance_certificate",
                    "commercial_register",
                    "taxpayer_card",
                    "commercial_invoice",
                    "proforma_invoice",
                    "bl_lta",
                    "customs_declaration",
                    "customs_receipt",
                    "besc",
                    "rvc",
                ];

                foreach ($columns as $key => $column) {
                    if (array_key_exists($column, $request)) {
                        $mask = [
                            "billing_date" => "emission",
                            "deadline" => "expiration",
                            "operationable_type" => "instance_type",
                        ];

                        $file = $request[$column];

                        $newfile = $upload_file_service->getFileInstance($file);

                        $upload_file_service->execute(
                            $file,
                            storage_path("app/public/ad_images"),
                            $newfile->path
                        );

                        if (isset($request["validity"])) {
                            $file_data += [$request["validity"]];
                        }

                        

                        $resource_details = [
                            "code" => Str::random(20),
                            "validation_type_id" => 1,
                            "user_id" => $user->id,
                        ];

                        if (isset($request["identifier"])) {
                            $resource_details += ['identifier' => $request["identifier"]];
                        }

                        if (isset($request["end-amount"])) {
                            $resource_details += [
                         'amount' => $request["end-amount"],
                         'currency_id' => Currency::whereCode($request["currency"])->first()->id
                    ];
                        }

                        if (isset($request["transfert"])) {
                            $transfert = Transfert::whereCode(
                                $request["transfert"]
                            )->first();

                            $resource_details = $this->contructData(
                                $request,
                                $resource_details,
                                $mask
                            );

                            $resourceable = $instanciator->apply($column);

                            $resource_details += [
                                "resourceable_type" => get_class($resourceable),
                                "resourceable_id" => $resourceable->id,
                                "operationable_id" => $transfert->id,

                                //"currency_id" => $transfert->currency_id,
                            ];

                            $resource = $this->fillModel(
                                $resource_details,
                                $resource
                            );

                            $partner = Supplier::find($transfert->supplier_id);
                        } elseif (isset($request["customer"])) {
                        }

                        $partner->resources()->save($resource);

                        $newfile = $this->fillModel($file_data, $newfile);

                        array_push($filesArray, $newfile);
                    }
                }
            }

            $param = $this->getParam($request, $request["instance_type"]);

            $instance = $this->getInstance($request["instance_type"], $param);

            $instance->documents()->saveMany($filesArray);

            DB::commit();

            return [
                "status" => true,
                "data" => ["file" => $filesArray],
            ];
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function getParam($request, $instance_type)
    {
        $param = "";

        if ($instance_type == Transfert::class) {
            $param = $request["transfert"];
        } elseif ($instance_type == Supplier::class) {
            $param = $request["supplier"];
        } elseif ($instance_type == Bank::class) {
            $param = $request["bank"];
        } elseif ($instance_type == Resource::class) {
        }

        return $param;
    }

    public function getInstance($class, $param)
    {
        return $class::whereCode($param)->first();
    }

    public function createTransfertDocument($request)
    {
        try {
            DB::beginTransaction();

            $filesArray = [];

            $param = $this->getParam($request, $request["instance_type"]);

            $instance = $this->getInstance($request["instance_type"], $param);

            $instance->documents()->saveMany($filesArray);

            DB::commit();

            return [
                "status" => true,
                "data" => ["file" => $filesArray],
            ];
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }
}
