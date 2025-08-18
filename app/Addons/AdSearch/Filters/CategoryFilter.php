<?php

namespace App\Addons\AdSearch\Filters;

use App\Models\Settings\City;
use App\Addons\AdSearch\Filters\Filter;
use App\Models\Misc\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CategoryFilter implements Filter
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
        

     $category ="App\\Models\\Misc\\$value";
   
        $builder->whereHasMorph('adable' ,[$category], function (Builder $query) use ($value) {

    })->get();

        
        return $builder;
    }
}