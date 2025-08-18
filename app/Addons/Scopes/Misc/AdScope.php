<?php

namespace App\Addons\Scopes\Misc;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class AdScope  implements Scope
{
    public function apply(Builder $builder, Model $model) : void {
        
       // $builder->wherePrice('>',0);

    }
}
 
?>