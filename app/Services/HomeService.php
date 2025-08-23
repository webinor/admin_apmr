<?php

namespace App\Services ;

use App\Addons\Misc\IndexVariablesResponder;
use App\Models\Misc\Bank;
use App\Addons\Misc\Responder;
use App\Addons\Misc\ViewsResponder;
use App\Addons\ValidatorData;
use App\Models\AssistanceAgent;
use App\Models\AssistanceLine;
use App\Models\Company;
use App\Models\GroundAgent;
use App\Models\Operations\Folder;
use App\Models\Misc\Invoice;
use App\Models\Operations\Assistance;
use App\Models\Operations\ExtractionTime;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\Transfert;
use App\Models\Settings\ValidationType;
use App\Models\Operations\Slip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeService implements IndexVariablesResponder, ViewsResponder
{
    


    public function getView($view_name,$vars = [])
    {
        
        return view($view_name , $vars);

    }


    public function getIndexVariables()  {
        

      //$fiches=Assistance::with('assistance_lines')->get();
      $fichesCount = Assistance::count();
      $beneficiairesCount = AssistanceLine::count(); // $fiches->assistance_lines->count();
      $compagniesCount = Company::count();
      $agentsCount = GroundAgent::count();
      $assistanceAgentsCount = AssistanceAgent::count();


$fichesAvantMoisEnCours = Assistance::whereDate('flight_date', '<', Carbon::now()->startOfMonth())->count();
$nouvelles_fiches = $fichesCount - $fichesAvantMoisEnCours;

  
     return $vars =  compact('fichesCount','beneficiairesCount','compagniesCount','agentsCount' , 'nouvelles_fiches' , 'assistanceAgentsCount');

      //dd($vars);




    }



    

   

}