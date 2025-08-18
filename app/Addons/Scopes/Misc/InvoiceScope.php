<?php

namespace App\Addons\Scopes\Misc;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class InvoiceScope  implements Scope
{
    public function apply(Builder $builder, Model $model) : void {
        
        $builder->has('resource.partnerable')
        ->has('resource.currency')
        ->has('resource.tax');

    }
}
 
?>