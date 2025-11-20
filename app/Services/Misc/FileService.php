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

    }
    public function createFile($request)
    {
    }

    public function getParam($request, $instance_type)
    {
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
