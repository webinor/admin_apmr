<?php

namespace App\Addons\AdSearch\Filters;


use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class MaxPriceFilter implements Filter
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

       //dd($value);

       if ($value!="400000") {
        
        $builder->where("price","<=",(int)$value);
        
       }
        
        

        //dd($builder->get());
        return $builder;
    }
}