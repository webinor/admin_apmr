<?php

namespace App\Addons\AdSearch\Filters;

use App\Models\Common\Ad;
use App\Models\Settings\City;
use App\Models\Misc\JobNature;
use App\Models\Settings\Commodity;
use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CommodityFilter implements Filter
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

        
        $ids = array_values(Commodity::select('id')->whereIn('slug', $value)->get()->toArray());
        
      /* dd(Ad::wherePaymentMethod('Sell')->
        where("price",">=",10000)->
        whereHas('commodities',function ($query) use ($ids) {



              $query->whereIn('commodities.id',[3,6,5]);
  
          })->get());*/
       


        //  dd($ids);
        
        $builder->whereHas('commodities',function ($query) use ($ids) {

             $query->whereIn('commodities.id',$ids,"and");

        });
        
        return $builder;
    }
}