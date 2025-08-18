<?php

namespace App\Addons\AdSearch\Filters;


use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PostedOnFilter implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {

      

        if ($value == "") {
            
            return $builder;

        }

      //  $authorized_columns = ["posted_on"];
        $authorized_vals = ["b_week","b_month","a_month" , "all"];

       // $column = explode("_",$value)[0];
      //  $val = explode("_",$value)[1];

        if ( !in_array($value,$authorized_vals) ) {
            
            return $builder;

        }


       
         
        switch ($value) {
            case 'b_week':
                $builder->where("created_at", ">=" , Carbon::now()->subDays(7)->toDateTimeString());
                break;

                case 'b_month':
                    $builder->where("created_at", ">=" , Carbon::now()->subMonth()->toDateTimeString());
                    break;

                    case 'a_month':
                        $builder->where("created_at", "<=" , Carbon::now()->subMonth()->toDateTimeString());
                        break;
            
            default:
            return $builder;
                break;
        }
            
         //   dd($val);
            
          

       
        return $builder;
    }
}