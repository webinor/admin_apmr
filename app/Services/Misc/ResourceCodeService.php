<?php

namespace App\Services\Misc ;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

 trait ResourceCodeService
    {
        
    
    
    
    public function generateResourceCode( $table='resources', $root,$length_number = 2 ) 
    {


        $start_index = 0;//1;
        $resources_of_current_month = DB::table($table )->select('id')->where('created_at' , '>=' , Carbon::now()->startOfMonth())->get()->count();//get the quotes of the current month
        $start_index = 0;//1;
        do {
        $initial = sprintf("%0".$length_number."d", ($start_index + $resources_of_current_month + 1));
        $code = $initial."/$root".date('m')."/".date("Y");
        $start_index++; 
        $res_count = DB::table($table )->select('id')->where('identifier',$code)->count();
        } while ($res_count > 0);
        
        return $code ;
        
       
    }


}
