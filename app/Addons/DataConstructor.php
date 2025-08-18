<?php 
namespace App\Addons;

use App\Models\Operations\ExtractionSetting;
use App\Models\Operations\Provider;
use App\Models\Prestations\Prestation;
use App\Models\Prestations\Product;
use App\Models\Prestations\Service;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


trait DataConstructor {

  

  public function echom($message)  {

    //if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {
        
        echo  $message ;
        
    //}
  
    
}

function generate_identification() : string {
  

  $faker = Faker::create();
  return $faker->regexify('/[A-HJ-NP-Z][0-9]{8}/');

}


  function str_lreplace($searches, $replace, $subject)
{
  foreach ($searches as $key => $search) {
   
    
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;

  }
}

public function extract_folder_reference($text)  {

  $rows = explode(PHP_EOL, $text);

  $regex = "/(?:Dossier|DossierFile|Dossi|Doss)(?:\s*)(?:[\w\s]*)(?:\s*)([^oOiI][a-zA-Z0-9]{8})(?:\s*)(?:Réf|Ref|Rof|Pec)/i";

  $file = "UNDEFINED FOLDER";

  $reference_matched = null;

  foreach ($rows as  $row) {

  //  $this->echom("file == $row \n");
 
    $row = preg_replace("/[^A-Za-z0-9é\s]/","",$row);
    $row = preg_replace("/invoices/","",$row);

    
   

  //  $this->echom("file row == $row \n");

    if (preg_match($regex,$row,$match)) {

      
    $this->echom("matched file == $row \n");

       $file =  preg_replace("/[oO]/","0",$match[1]);

       $reference_matched = $match;

       break;
  }

}

  return [ "reference" => $file , "reference_matched"=>$reference_matched];



}


public function extract_reference($text)  {

  $rows = explode(PHP_EOL, $text);

  $regex = "/(?:Réf|Ref|Rof|PEC)(?:\s*)(?:[\w\s]*)(?:\s*)([a-zA-Z0-9]{8})/i";

  $reference = "REFERENCE INTROUVABLE";

  $reference_matched = null;

  foreach ($rows as $row) {

   // $this->echom("row == $row \n");

    $row = preg_replace("/[^A-Za-z0-9é\s]/","",$row);
    $row = preg_replace("/invoices/","",$row);

    //$row = preg_replace("/[oO]/","0",$row);

  //  $this->echom("new row == $row \n");


    if (preg_match($regex,$row,$match)) {

      $this->echom("matched reference == $row \n");

      // $reference = $match[1];
       $reference =  preg_replace("/[oO]/","0",$match[1]);

       $reference_matched = $match;

       break;
      
    }

  }

  return [ "reference" => $reference , "reference_matched" => $reference_matched ];



}

public function prestation_already_exists($prestations , $needle ) {
  
  $prestation_already_exists = false;


  foreach ($prestations as $prestation) {
          

      if ($prestation['name']==$needle) {
        
        $prestation_already_exists = true;
        break;

      }
      
    
    
  }

     return   $prestation_already_exists ;
         

}

public function fill_services(array $prestations ,$current_service , $matches_service  ,$new_prestations , $has_prestations )  {

//$has_prestations = false;
//$new_prestations = [];

  //$current_service = $switch[$matches_service[1]];


  //  $this->echom("------has_service-------yes---------\n");
    $this->echom("------matches_service-------".$matches_service[1]."---------\n");
    $this->echom("------current_service-------".$current_service."---------\n");

   // $this->echom("\n----------B----------".(json_encode($prestations))."-------------B-----------\n\n");



    foreach ($prestations as $prestation) {
      
      $new_prestation = $prestation;

     // $prestation['name']= $prestation['name'] == "UNDEFINED SERVICE"
      
    //  if ( $prestation['name']!= "UNDEFINED SERVICE"  ) {
          
        if (/*($prestation['name']==$matches_service[1] && !$this->prestation_already_exists($new_prestations , $matches_service[1] ) ) && */ 
          ( $prestation['name']==$current_service && !$this->prestation_already_exists($new_prestations , $current_service ) )) {
          
          $has_prestations = true;
          
          array_push($new_prestations , $new_prestation);
          
        //  $this->echom("\n----------TT----------".(json_encode($new_prestations))."-------------TT-----------\n\n");

        }
        else if($prestation['name']==$current_service && $this->prestation_already_exists($new_prestations , $current_service )){
          $has_prestations = true;
      }

      else if($prestation['name']!=$current_service && $this->prestation_already_exists($new_prestations , $current_service )){
        $has_prestations = true;
    }

    else if($prestation['name']!=$current_service && !$this->prestation_already_exists($new_prestations , $current_service )){
      //$has_prestations = true;
     // array_push($new_prestations , $new_prestation);

  }
      
    }

    if ($has_prestations == false) {

      array_push($new_prestations , ["folder"=>"","name"=>$current_service,"reference"=>"","amount"=>"","page_index"=>1,"invoice_string"=>""]);
   
   // $this->echom("\n--------Temp == -----".json_encode($new_prestations)."---------\n");

    }

    return $new_prestations;
    //$this->echom("\n----------Temp----------".(json_encode($new_prestations))."-------------Temp-----------\n\n");


}

    public function get_items_of_exploded_provider($extraction_settings,$texts,$provider,$prestations){



        $new_prestations = [];

        $switch = json_decode($extraction_settings->service_switcher , true);
        $extract_params = json_decode($extraction_settings->extract_params , true);

        //$prestations_exploded = $extract_params["prestations_exploded"];

        $items = json_decode($extraction_settings->items_layout , true);

        $distinct_prestations = array_key_exists("distinct_prestations",$extract_params) ? $extract_params["distinct_prestations"] : "1";

       // preg_match('/(?<=Designation)(?s)(.*$)/',$text ,$pure  , PREG_OFFSET_CAPTURE);
      //  preg_match(json_decode($extraction_settings->pure_matcher , true)['regex'],$text ,$pure);

   // $this->echom(  ($extraction_settings->pure_matcher);
    
    $should_purify_input_text = json_decode($extraction_settings->pure_matcher , true)['should_purify_input_text'];

        
   // $this->echom("--------id-----".$text."----id-----\n");
       

   foreach ($texts as $temp_prestation => $fragment) {

    $text = $fragment['items_string'];
   
    $current_service = $temp_prestation;
    $matches_service=["", $temp_prestation];

      // $this->echom("-------------current_service. == ".($current_service)."---------\n\n");

    $new_prestations =  $this->fill_services($prestations , $current_service , $matches_service  , $new_prestations , false );

    

    /*

       if ($should_purify_input_text) {

        $pure_matcher_regex = json_decode($extraction_settings->pure_matcher , true)['regex'];


        preg_match($pure_matcher_regex,$text ,$pure);
        $matchs = isset($pure[0])  ? explode(PHP_EOL, $pure[0]) : explode(PHP_EOL, $text) ;
       
        }
        else{

            $matchs = explode(PHP_EOL, $text);

        }

        */

   // $this->echom("match == $text \n");
        
       

        $items_mask =  json_decode($extraction_settings->items_mask , true);

        $lines_matchers = [];

        $service_matcher =  json_decode($extraction_settings->service_matcher , true);

        

        $excludes = json_decode($extraction_settings->exclude , true);

        $iteration = 1;

        $exploded_lines_matchers = json_decode($extraction_settings->items_mask , true)['lines_matchers']['exploded_lines_matchers'][$current_service];
        $sanitizers = json_decode($extraction_settings->items_mask , true)['line_sanitizers'] ;

        
        $pure_matcher = json_decode($extraction_settings->items_mask , true)['lines_matchers']['exploded_lines_matchers'][$current_service]['pure_matcher'];
        
        $should_purify_input_text = $pure_matcher['should_purify_input_text'];


        if ($should_purify_input_text) {

          $pure_matcher_regex = $pure_matcher['regex'];
  
          
         // dd($matchs);
          // preg_match($pure_matcher_regex,$text ,$pure);
          $matchs = preg_match($pure_matcher_regex,$text ,$pure) ? explode(PHP_EOL, $pure[0]) : explode(PHP_EOL, $text) ;
          
          }

          else{

            $matchs = explode(PHP_EOL, $text);

        }
        
        foreach ($matchs as $match) {

  $has_prestations = false;

          //    foreach (json_decode($extraction_settings->items_mask , true)['lines_matchers'][$current_service]['regex'] as $key => $lines_matcher) {              
           

              

                  foreach ($sanitizers  as $sanitizer) {

                      $regexes = $sanitizer["regexes"];

                      foreach ($regexes  as $regex => $value) {
                      
                    $match =preg_replace($regex,$value,$match);
                    
                  }
                }
       
        
        Str::of($match)->trim() ;

         

        $this->echom($match !="" ? "watch ==$match. \n\n" : "\n\n");

       

      //  dd($exploded_lines_matchers['regex']);
      

                
        preg_match($exploded_lines_matchers['regex'],$match ,$line_matched);

        
            
 
        if ($current_service!= "" && $line_matched &&  !in_array($current_service , $exploded_lines_matchers['excluded']) ) { /// single line or total

           
          //  $line_matched =preg_replace("/\s\s/"," ",$line_matched);
       // return $line_matched;
     //  $this->echom("-------------".$current_service." == ".($match)."---------\n");

         $this->echom("-------------".json_encode($line_matched)."---------\n\n\n");

         if ($current_service =="CONSULTATIONS ET VISITES MEDICALES" ) {
          
          if (!preg_match($exploded_lines_matchers['included'][0],$match ,$include_matched)) {
            break;
          }
          
           }

       //  dd($line_matched);

       $temp_item = [];
 
     //  $this->echom("-------------".$current_service." == ".($match)."---------\n");
 

       foreach ($items_mask["services"][$current_service]['titles'] as  $title) {
       
        
        if ($title['column_index'] == null || $title['default'] != null) {
            

            $temp_item[$title['name']]= $title['default'];
        
             
        } else {
            

            if ($exploded_lines_matchers["options"]["filter_empty"]==true) {
               
              $line_matched =  array_values(array_filter($line_matched, fn($value) => !is_null($value) && $value !== ""));
                
            }

      //   $this->echom("-------------".json_encode($line_matched)."---------";
            
            $temp_item 
        [$title
        ['name']]= 
        $title['sanitizer_regex'] != null ? 
        Str::of(preg_replace($title['sanitizer_regex'], "", $line_matched[$title['column_index']]))->trim() : 
        $line_matched[$title['column_index']];
        

        if (isset($title['parsers'])) {
         
              foreach ($title['parsers'] as $parser) {
                # code...
              
              $temp_item
              [$title
              ['name']]= preg_replace($parser["regex"], $parser["value"], $line_matched[$title['column_index']]);
              
              $line_matched[$title['column_index']] = $temp_item
              [$title
              ['name']];
              
            }

        }


        }
        

        
       }

       if ($current_service =="CONSULTATIONS ET VISITES MEDICALES") {
         // $this->echom("line_matched == ".json_encode($temp_item)." \n\n");
         }
       array_push($items[$current_service]['labels'],$temp_item);        
            
      }
        
       // $match != "" ? array_push($items[$current_service],$match) : null ;

  //  }

  

  // /** */ }/**/
    
}

    }

     


//$this->echom("-------final------".json_encode($new_prestations)."-----final----\n\n");

//dd($items);

return ["items" => $items , "prestations"=>$new_prestations];

    }

    
    public function construct_items($texts , ExtractionSetting $extraction_settings , Provider $provider  , array $prestations )  {
        
        /*$switch = [
            "EXAMEN LABO"=>"ANALYSES BIOLOGIQUES",
            "PHARMACY"=>"PHARMACIE",
            "IMAGERIE"=>"ACTES DE RADIOLOGIE",
            "CONSULTATTION"=>"CONSULTATIONS ET VISITES MEDICALES",
            "CONSULTATION SPECIALISTE"=>"CONSULTATIONS ET VISITES MEDICALES"
        ];*/

        $new_prestations = [];

        $switch = json_decode($extraction_settings->service_switcher , true);
        $extract_params = json_decode($extraction_settings->extract_params , true);
        
        $prestations_exploded = isset($extract_params["prestations_exploded"]) ? $extract_params["prestations_exploded"] : "0";


        if (isset($prestations_exploded) && $prestations_exploded == "1") {
          

          return $this->get_items_of_exploded_provider($extraction_settings,$texts,$provider,$prestations);

          
        }


        $distinct_prestations = array_key_exists("distinct_prestations",$extract_params) ? $extract_params["distinct_prestations"] : "1";

       // preg_match('/(?<=Designation)(?s)(.*$)/',$text ,$pure  , PREG_OFFSET_CAPTURE);
      //  preg_match(json_decode($extraction_settings->pure_matcher , true)['regex'],$text ,$pure);

   // $this->echom(  ($extraction_settings->pure_matcher);
    
    $should_purify_input_text = json_decode($extraction_settings->pure_matcher , true)['should_purify_input_text'];

        
   // $this->echom("--------id-----".$text."----id-----\n");

  // $items = json_decode($extraction_settings->items_layout , true);
       

   foreach ($texts as $key => $fragment) {

    $text = $fragment['items_string'];
   

       if ($should_purify_input_text) {

        $pure_matcher_regex = json_decode($extraction_settings->pure_matcher , true)['regex'];


        preg_match($pure_matcher_regex,$text ,$pure);
        $matchs = isset($pure[0])  ? explode(PHP_EOL, $pure[0]) : explode(PHP_EOL, $text) ;
       
        }
        else{

            $matchs = explode(PHP_EOL, $text);

        }

   // $this->echom("match == $text \n");
        
       $items = json_decode($extraction_settings->items_layout , true);
       
        $current_service="";

        $items_mask =  json_decode($extraction_settings->items_mask , true);

        $lines_matchers = [];

        $service_matcher =  json_decode($extraction_settings->service_matcher , true);

        

        $excludes = json_decode($extraction_settings->exclude , true);

        $iteration = 1;
foreach ($matchs as $match) {

  $has_prestations = false;

 /// $has_service = false;

$test = "test";

  //  $this->echom("match ==$match. \n");


    


    if (in_array($provider->provider_category_id , [2,3])) {
        
        
      //  $this->echom("--------provider_category-----".$provider->provider_category->name."----provider_category-----\n");



        $has_service = preg_match(
            $service_matcher['regex'],
            $iteration == 1 ? Str::upper($provider->provider_category->name) : $match,
            $matches_service
        );
        
      }
      else if (in_array($provider->provider_category_id , [4])) {
        
        
        //  $this->echom("--------id-----".$provider->provider_category_id."----id-----\n");
        //  $this->echom("--------provider_category-----".$provider->provider_category->name."----provider_category-----\n");
  
  
          $has_service = preg_match(
              $service_matcher['regex'],
              //$iteration == 1 ? Str::upper($prestations[0]["name"]) : $match,
              $iteration == 1 ? Str::upper($provider->provider_category->name) : $match,
              $matches_service
          );
  
          $matches_service = [
            Str::upper($prestations[0]["name"]),
            Str::upper($prestations[0]["name"])
          ];
  
  
         
          
        }
      else {

      //  $this->echom("\n--------prestations == -----".json_encode($prestations)."---------\n");


        $has_service = preg_match(
          $service_matcher['regex'],
          $match,
          $matches_service
        );

      
        //
        
      //  $this->echom("------matches_service-------".json_encode($matches_service)."---------\n");

      }
      
      

      $iteration++;

      if ($has_service && in_array("CONSULTATION" , json_decode($extraction_settings->service_mask , true)['include'])) {
       
       // $this->echom("------has_service-------yes---------\n");
       // $this->echom("------matches_service-------".json_encode($matches_service)."---------\n");
       // $this->echom("------service_mask-------".json_encode(json_decode($extraction_settings->service_mask , true)['include'])."---------\n");
        
        
      }
      
    //  $this->echom("------matches_service-------".$matches_service[1]."---------\n");


      if ($has_service && ( in_array($matches_service[1] , json_decode($extraction_settings->service_mask , true)['include']) ) ) {
        
        $new_prestations =  $this->fill_services($prestations , $switch[$matches_service[1]] , $matches_service  , $new_prestations , $has_prestations );

        $current_service = $switch[$matches_service[1]];


        
    } else {
           
  
      
              foreach (json_decode($extraction_settings->items_mask , true)['lines_matchers'] as $key => $lines_matcher) {              
           

              $sanitizers = json_decode($extraction_settings->items_mask , true)['line_sanitizers'] ;

                  foreach ($sanitizers  as $sanitizer) {

                      $regexes = $sanitizer["regexes"];

                      foreach ($regexes  as $regex => $value) {
                      
                    $match =preg_replace($regex,$value,$match);
                    
                  }
                }
       
        
        Str::of($match)->trim() ;

         

        $this->echom($match !="" ? "watch ==$match. \n" : "\n");

        if ($distinct_prestations=="0" && !$has_service ) {
          
        //  $this->echom("-------------begin adding---------\n\n\n");

          $prestation = $match != "" ? Prestation::search($match)->first() : null;

        //  $this->echom("-------------end---------\n\n\n");

        //  $this->echom("-------------".json_encode($prestation)."---------\n\n\n");


          if ($prestation) {
            $prestation = $prestation->load(['prestationable']);
            $has_service=true;

            //  $this->echom("-------------".json_encode($prestation)."---------\n\n\n");
            
            $prestation_type = get_class($prestation->prestationable);

           // $this->echom("-------------".json_encode($prestation_type)."---------\n\n\n");


            
            if ($prestation_type == Service::class) {

        $prestation->prestationable = $prestation->prestationable->load(['service_type']);


      //  $this->echom("-------------".json_encode($prestation)."---------\n\n\n");

              $matches_service= ["" ,$prestation->prestationable->service_type->get_fullname()];
            $current_service = $prestation->prestationable->service_type->get_fullname();

           $new_prestations =  $this->fill_services($prestations ,$current_service , $matches_service , $new_prestations, $has_prestations);
           
         //  $this->echom("-------------".json_encode($new_prestations)."---------\n\n\n");


            } else if($prestation_type == Product::class) {

              $matches_service= ["" ,"PHARMACY"];
            $current_service = "PHARMACIE";

           $new_prestations =  $this->fill_services($prestations ,$current_service , $matches_service , $new_prestations, $has_prestations);    
          
          }
            
     


        //    $this->echom("------current_service ==-------".($current_service)."---------\n\n\n");
          }
          else{
            $has_service=false;

            $matches_service= ["" ,"UNDEFINED SERVICE"];
            $current_service = "UNDEFINED SERVICE";

           $new_prestations =  $this->fill_services($prestations ,$current_service , $matches_service , $new_prestations, $has_prestations);    

          }

            

        }

      

                
                preg_match($lines_matcher['regex'],$match ,$line_matched);

          

              
              //  preg_match(json_decode($extraction_settings->total_matcher , true)['regex'],$match ,$total_matched);
        
            //  $this->echom("-------------".json_encode($match)."---------";
           //   $this->echom("-------------.current_service. == ".($current_service)."---------\n");
       
             if ($current_service =="CONSULTATIONS ET VISITES MEDICALES") {
            //  $this->echom("line_matched == ".json_encode($line_matched)." \n\n");
             }
 
        if ($current_service!= "" && $line_matched && $key != "total_matcher" && !in_array($current_service , $lines_matcher['excludes']) ) { /// single line or total

           
          //  $line_matched =preg_replace("/\s\s/"," ",$line_matched);
       // return $line_matched;
     //  $this->echom("-------------".$current_service." == ".($match)."---------\n");

         $this->echom("-------------".json_encode($line_matched)."---------\n\n\n");


       $temp_item = [];
 
     //  $this->echom("-------------".$current_service." == ".($match)."---------\n");
 

       foreach ($items_mask["services"][$current_service]['titles'] as  $title) {
       
        
        if ($title['column_index'] == null || $title['default'] != null) {
            

            $temp_item[$title['name']]= $title['default'];
        
             
        } else {
            

            if ($lines_matcher["options"]["filter_empty"]==true) {
               
              $line_matched =  array_values(array_filter($line_matched, fn($value) => !is_null($value) && $value !== ""));
                
            }

      //   $this->echom("-------------".json_encode($line_matched)."---------";
            
            $temp_item 
        [$title
        ['name']]= 
        $title['sanitizer_regex'] != null ? 
        Str::of(preg_replace($title['sanitizer_regex'], "", $line_matched[$title['column_index']]))->trim() : 
        $line_matched[$title['column_index']];


        if ($title['name']=='quantity') {
          $this->echom("----------------------".$line_matched[$title['column_index']]."-------------------\n\n");
        }
        

        if (isset($title['parsers'])) {
         
              foreach ($title['parsers'] as $parser) {
                # code...
              
              $temp_item
              [$title
              ['name']]= preg_replace($parser["regex"], $parser["value"], $line_matched[$title['column_index']]);
              
              $line_matched[$title['column_index']] = $temp_item
              [$title
              ['name']];
              
            }

        }


        }
        

        
       }

       array_push($items[$current_service]['labels'],$temp_item);        
            
      }
        
       // $match != "" ? array_push($items[$current_service],$match) : null ;

    }

  

   /** */ }/**/
    
}

    }

      //   $this->echom("-----Items--------".json_encode($items)."----Items-----\n\n");

if ($distinct_prestations=="0"  ) {
  # code...

         $this->echom("-----Items--------".json_encode($items)."----Items-----\n\n");


      //   $this->echom("------Semi-final------".json_encode($new_prestations)."----Semi-final----\n\n");

   $new_prestations = (collect($new_prestations)->filter(function ($prestation, $key) use ($items) {
           
        return array_key_exists($prestation["name"],$items) && count($items[$prestation["name"]]["labels"])>0;

  }))->values();
   
 //return $filtered->values();

      //  $this->echom("-------final------".json_encode($new_prestations)."-----final----\n\n");

}


//$this->echom("-------final------".json_encode($new_prestations)."-----final----\n\n");


return ["items" => $items , "prestations"=>$new_prestations];
    
}

/**/
    public function contructData($input_data, $output_data, $mask)
    {
        foreach ($mask as $key => $value) {
            array_key_exists($value, $input_data)
                ? ($output_data[$key] = $input_data[$value])
                : null;
        }

        return $output_data;
    }


    public function fillModel($columns, $instance)
    {
        foreach ($columns as $column => $value) {
            array_key_exists($column, $columns)
                ? ($instance->{$column} = $value)
                : null;
        }

        return $instance;
    }


    public function fillFields($instance, $columns, $values)
    {
        foreach ($columns as $column) {
            array_key_exists($column, $values)
                ? ($instance->{$column} = $values[$column])
                : null;
        }

        return $instance;
    }

}