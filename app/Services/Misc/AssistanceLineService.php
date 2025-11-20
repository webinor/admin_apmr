<?php

namespace App\Services\Misc;

use App\Jobs\SendSmsJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Misc\Resource;
use App\Models\Operations\Mission;
use App\Models\Operations\Step;
use App\Models\City;
use App\Models\Company;
use App\Models\Storage\Reception;
use App\Models\Storage\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Storage\StockMovement;
use App\Models\Storage\ProductSupplier;
use App\Models\Storage\ProductWarehouse;
use App\Notifications\WelcomeUserNotification;

use App\Addons\DataConstructor;
use App\Addons\FileUploadHandler;
use App\Addons\Misc\CreateVariablesResponder;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\ViewsResponder;
use App\Models\AssistanceAgent;
use App\Models\Operations\Assistance;
use App\Models\Operations\AssistanceLine;
use App\Models\Registrator;
use App\Models\User\User;
use App\Models\WheelChair;
use Carbon\Carbon;

class AssistanceLineService implements
    //IndexVariablesResponder,
    CreateVariablesResponder, ShowVariablesResponder, EditVariablesResponder, ViewsResponder
{
    use DataConstructor, FileUploadHandler;

    protected string $apmerServiceBaseUrl;

    public function __construct()
    {
        // À mettre dans .env par exemple USER_SERVICE_BASE_URL
        $this->apmerServiceBaseUrl = config("services.apmr_service.base_url");
    }

    function getIndexVariables()
    {

    }
    function getCreateVariables()
    {
       
    }
    function getShowVariables($folder)
    {
        
    }
    function getEditVariables($assistance)
    {
        
    }
    function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }
    function delete($model)
    {

    }

    public function createCity(array $company_details)
    {
    }

    public function updateCity(array $company_details)
    {
       
    }

    public function deleteAssistanceLine(AssistanceLine $assistance_line)
    {
        try {
            DB::beginTransaction();
            $assistance_line->delete();

            //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////

            DB::commit();

            return [
                "status" => true,
                "data" => $assistance_line,
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
