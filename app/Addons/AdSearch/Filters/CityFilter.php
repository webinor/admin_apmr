<?php

namespace App\Addons\AdSearch\Filters;

use App\Models\Settings\City;
use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CityFilter implements Filter
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

        
         $builder->whereHas('location',function($query) use ($value){
            $query->whereCityId(City::select('id')->whereName($value)->first()->id);
        });
        $builder->with(['location:id,name,ad_id,city_id','location.city:id,name']);

        //dd($builder->get());
        return $builder;
    }
}