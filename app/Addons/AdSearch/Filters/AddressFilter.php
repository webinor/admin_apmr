<?php

namespace App\Addons\AdSearch\Filters;

use App\Models\Settings\City;
use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AddressFilter implements Filter
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

      //  dd($value);
        
         $builder->whereHas('location',function($query) use ($value){

            $city = City::select('id')->whereName($value)->first();
            if ($city) {
                $query->whereCityId($city->id);
                $query->orWhere('name', 'like', "%$value%");
            }
            else{

              //  dd("%$value%");
                $query->where('name', 'like', "%$value%");
             

            }
            
            
            

        });
        $builder->with(['location:id,name,ad_id,city_id','location.city:id,name']);

        //dd($builder->get());
        return $builder;
    }
}