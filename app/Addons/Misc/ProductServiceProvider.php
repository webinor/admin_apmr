<?php
namespace App\Addons\Misc;



trait ProductServiceProvider{

    public function get_products_providers() : array {


        return ["pharmacie" , "*"];
        
    }

    public function get_services_providers() : array {


        return ["hopital","laboratoire","imagerie","*"];
        
    }
}