<?php

namespace App\Addons\Misc;


interface FieldsFiller {


    public function fillFields(object $instance, array $columns, array $values) ;
}



