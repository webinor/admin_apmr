<?php

namespace App\Addons;

use App\Models\Common\Ad;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Common\SearchRequest;


final class Instanciator 
{ 
    
    public static function apply( $resource_type , $location = "Transit")
    {
        

        if (class_exists($resource_type)) {

            $new_resource_type = new  $resource_type;
            $new_resource_type->save();

            return $new_resource_type;
        }
        
        
        $decorator = static::createDecorator($resource_type);

        $decorator =   "App\\Models\\$location\\" . 
        str_replace(' ', '', 
            ucwords(str_replace('_', ' ', $resource_type)));

        if (class_exists($decorator)) {

            $new_resource_type = new  $decorator;
            $new_resource_type->save();

            return $new_resource_type;
        }


        return $decorator;
    }
    
    private static function applyDecorator( $resource_type)
    {

        
            $decorator = static::createDecorator($resource_type);

            if (static::isValidDecorator($decorator)) {

                $new_resource_type = new  $decorator;
                $new_resource_type->save();
            }


            return $new_resource_type;
    }
    
  
    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }


    private static function createDecorator($resource_type)
    {
   
    $decorator =   'App\\Models\\Transit\\' . 
    str_replace(' ', '', 
        ucwords(str_replace('_', ' ', $resource_type)));


         return $decorator;
    }


}

