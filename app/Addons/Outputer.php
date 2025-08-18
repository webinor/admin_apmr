<?php

namespace App\Addons;


class Outputer {

    public function echom($message)  {

        if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {
            
            echo  $message ;
            
        }
      
        
    }

}

