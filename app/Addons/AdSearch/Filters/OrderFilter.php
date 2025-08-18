<?php

namespace App\Addons\AdSearch\Filters;


use Illuminate\Support\Str;
use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter implements Filter
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

        // dd(explode("_",$value));

        if ($value == "") {
            
            return $builder;

        }

        $authorized_columns = ["date","price"];
        $authorized_vals = ["asc","desc","Asc","Desc"];

        $column = explode("_",$value)[0];
        $val = explode("_",$value)[1];

        if (!in_array($column,$authorized_columns) || !in_array($val,$authorized_vals) ) {
            
            return $builder;

        }


        if ($column =="date") {
            # code...
      

         $equiv = ["date" => "created_at"];

         if (Str::lower($val) == "asc") {
            $builder->oldest($equiv[$column]);
         }
         elseif (Str::lower($val)=="desc") {
            $builder->latest($equiv[$column]);
         }
         else {
            
         }
        
          }
          elseif ($column == "price") {
            
         //   dd($val);
            $builder->orderBy($column , $val);
          }

       
        return $builder;
    }
}