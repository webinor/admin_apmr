<?php

namespace App\Addons\AdSearch\Filters;


use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class MinPriceFilter implements Filter
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

       /// dd($value);
        
        $builder->where("price",">=",(int)$value);

        //dd($builder->get());
        return $builder;
    }
}