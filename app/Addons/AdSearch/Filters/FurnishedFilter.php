<?php

namespace App\Addons\AdSearch\Filters;

use App\Models\Settings\City;
use App\Addons\AdSearch\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FurnishedFilter implements Filter
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

        switch ($value) {
            case 'no':
                $payment_method = "Month" ;
                break;

                case 'yes':
                    $payment_method = "Night" ;
                    break;

                    case 'sell':
                        $payment_method = "Sell" ;
                        //dd($payment_method);
                        break;
            
            default:
                $payment_method = "Month" ;
                break;
        }

         $builder->wherePaymentMethod($payment_method);
        return $builder;
    }
}