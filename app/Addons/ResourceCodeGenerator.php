<?php

namespace App\Addons ;

use Carbon\Carbon;
use App\Models\User;
use App\Jobs\SendSmsJob;
use App\Models\Common\Ad;
use Illuminate\Support\Str;
use App\Models\Misc\Mention;
use Illuminate\Http\Request;
use App\Models\Misc\Resource;
use App\Models\Finance\Invoice;
use App\Models\Setting\Company;
use App\Models\Storage\Product;
use App\Models\Storage\Supplier;
use App\Models\Storage\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales\CommercialQuote;
use App\Notifications\WelcomeUserNotification;

 trait ResourceCodeGenerator
    {
        
    
    
    
        public function generateReference( $table, $root ) 
        {
    
            $length_number = 4;
            $start_index = 0;//1;
            $ads_of_current_month = DB::table($table)->select('id')->where('created_at' , '>=' , Carbon::now()->startOfMonth())->get()->count();//get the quotes of the current month
            $start_index = 0;//1;
            do {
            $initial = sprintf("%0".$length_number."d", ($start_index + $ads_of_current_month + 1));
            $reference = $initial."/$root".date('m')."/".date("Y");
            $start_index++; 
            $ad_count = Ad::select('id')->where('reference',$reference)->count();
            } while ($ad_count > 0);
            
            return $reference ;
            
           
        }


}
