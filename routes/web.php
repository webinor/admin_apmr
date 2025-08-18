<?php

use App\Http\Controllers\AssistanceAgentController;
use App\Http\Controllers\AssistanceController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GroundAgentController;
use Illuminate\Support\Str;
use App\Models\Operations\Folder;
use App\Models\Operations\Provider;
use App\Models\Prestations\Service;
use Illuminate\Support\Facades\Route;
use App\Models\Prestations\Prestation;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Misc\PdfController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Misc\PusherController;
use App\Http\Controllers\Misc\SearchController;
use App\Http\Controllers\User\LoginUserController;
use App\Http\Controllers\Misc\FreeSearchController;
use App\Http\Controllers\Operations\SlipController;
use App\Http\Controllers\Operations\ExtractController;
use App\Http\Controllers\Operations\ServiceController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Prestations\ProductController;
use App\Http\Controllers\Prestations\ProviderController;
use App\Http\Controllers\HumanResource\EmployeeController;
use App\Http\Controllers\Misc\TherapeuticClassController;
use App\Http\Controllers\Operations\FolderController;
use App\Http\Controllers\Operations\ServiceCostController;
use App\Http\Controllers\Prestations\MedicineController;
use App\Http\Controllers\Prestations\PrestationController;
use App\Http\Controllers\Prestations\ProductCostController;
use App\Http\Controllers\WheelChairController;
use App\Models\Misc\Search;
use App\Models\Operatrions\Schedule;
use App\Models\Prestations\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
//use Faker\Generator as Faker;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Route::get("/changeLang/{locale}", function ($locale) {
    if (!in_array($locale, ["en", "fr"])) {
        abort(400);
    }

    return back()->cookie("X-Language", $locale, 60 * 24 * 30 * 12);
});


Route::resource("freesearch/{type?}", FreeSearchController::class)->only([
    "index"
]);


Route::middleware(["guest", "throttle_recaptcha:20"])->group(function () {
    Route::get("/login", [LoginUserController::class, "index"])->name("login");
    Route::post("/authenticate", [
        LoginUserController::class,
        "authenticate",
    ])->name("authenticate_user");

    Route::prefix("access")->group(function () {
        Route::get("user/{user}/{token}", [
            UserController::class,
            "showDefinePassword",
        ]);

        Route::resource("user", UserController::class);
    });

    Route::post("define_password", [
        UserController::class,
        "definePassword",
    ])->name("define_password");

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get("reset-password", "create")->name("show_reset_password");
        Route::post("reset-password", "reset");
        Route::get("define-password/{code}/{token}", "edit")->name(
            "edit_password"
        );
        Route::put("define-password", "update")->name("update_password");
    });
});

Route::middleware(["auth"])->group(function () {

    Route::resource("company", CompanyController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);

    Route::resource("city", CityController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);

    Route::resource("wheel-chair", WheelChairController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);

    Route::resource("ground-agent", GroundAgentController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);


    Route::resource("assistance-agent", AssistanceAgentController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);

    Route::resource("assistance", AssistanceController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);


    Route::resource("service_cost", ServiceCostController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);


    Route::post("/import-therapeutic-class", [TherapeuticClassController::class, "import"])->name(
        "import"
    );
    
    Route::resource("therapeutic-class", TherapeuticClassController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);


    Route::resource("active-ingredient", MedicineController::class)->only([
        "index",
        "create",
        "edit",
        "show",
    ]);


    Route::post("save-invoices", [FolderController::class, "save_invoices"]);
    Route::post("validate-invoices", [FolderController::class, "validate_invoices"]);
    

   

    Route::get("/filter", [HomeController::class, "filter"]);
    Route::get("/construct", [HomeController::class, "construct"]);
    Route::get("/scan", [HomeController::class, "scan"]);
    Route::get("/extract-old", [ExtractController::class, "extract_old"]);

    Route::post("/pusher/auth", [PusherController::class, "pusherAuth"]); /**/

    Route::controller(UserController::class)->group(function () {
        Route::get("verify-account/{user}", "verify_account");

        Route::prefix("user")->group(function () {
            Route::post("/verify-account", "store_verify");
        });
    }); 
});

Route::middleware(["auth", "device_is_verify"])->group(function () {
    Route::get("/", [HomeController::class, "index"]);

    Route::prefix("human_resource")->group(function () {
        Route::resource("employee", EmployeeController::class);
    });

    Route::get("print/{order}", [PdfController::class, "generatePDF"]);

    Route::controller(UserController::class)->group(function () {
        Route::get("/profile", "index");
        Route::post("/updateProfile", "updateProfile");
    });

    Route::match(["get", "post"], "logout", [
        LoginUserController::class,
        "logout",
    ])->name("user_logout");




}); 


Route::get("/host", function (Faker $fakerpp) {


  //  dd(Hash::make("Test"));
   // $invoice = Invoice::whereReference
  
   /** */
   $cookie_name = ".ASPXAUTH";
   $cookie_value = decrypt(session($cookie_name));

   $apiToken =  decrypt(session("accessToken"));

   //$url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/uploadFile';
   $url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/priseEnChargeUploadFile';

   // Fichier depuis S3
   $s3 = Storage::disk('s3');
   //$filename = basename(parse_url("https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/SCtnAYZFgiUXR0a.pdf", PHP_URL_PATH));
   $filename = basename(parse_url("https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/Hkw3fZ97f69QXAR.pdf", PHP_URL_PATH));
   
   
   
   $path = "extracted_doc/$filename";
   
   if (!$s3->exists($path)) {
       throw new \InvalidArgumentException("Fichier introuvable sur S3 : $path");
   }
   
   // Sauvegarder temporairement localement (curl ne lit pas les streams S3 directement)
   $tmpPath = storage_path("app/tmp_$filename");
   Storage::disk('local')->put("tmp_$filename", $s3->get($path));
   
   // Champs POST
   $formFields = [
       'pecRef' => '094CF5A6',
       'attachemebnNumber' => 1,
   ];
   
   // Initialiser cURL
   $curl = curl_init();
   
   $postFields = [
       'fileInformation' => new CURLFile($tmpPath, mime_content_type($tmpPath), $filename)
   ] + $formFields;
   
   curl_setopt_array($curl, [
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_POST => true,
       CURLOPT_POSTFIELDS => $postFields,
       CURLOPT_HTTPHEADER => [
           "Accept: application/json",
           "Authorization: Bearer $apiToken",          // "Cookie: $cookie_name=$cookie_value",
       ],
   ]);


   curl_setopt($curl, CURLINFO_HEADER_OUT, true);

 //  $info = curl_getinfo($curl, CURLINFO_HEADER_OUT);
//echo ">>> Requête envoyée :\n";
//return $info;
   $response = curl_exec($curl);

   $info = curl_getinfo($curl);
   echo ">>> Headers envoyés :\n";
   print_r($info['request_header']); // Affiche le Content-Type généré automatiquement


   $err = curl_error($curl);
   $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
   
   curl_close($curl);
   
   // Nettoyer le fichier temporaire
   unlink($tmpPath);
   
   // Affichage debug
   if ($err) {
       echo "Erreur cURL: $err";
   } else {
       echo "Code HTTP: $http_code\n";
       echo "Réponse: $response";
   }
   










   return null;/**/
//////////////////////////////////////////////////debug

/*

$cookie_name = ".ASPXAUTH";
$cookie_value = decrypt(session($cookie_name));

$url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/uploadFile';

$s3 = Storage::disk('s3');

$filename = basename(parse_url("https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/SCtnAYZFgiUXR0a.pdf", PHP_URL_PATH));
$path = "extracted_doc/$filename";

//$filePath =  storage_path("app/public/extracted_doc/$filename") ;
$formFields = [
'projetRef' => "DAA58FEA",
'attachemebnNumber' => rand(10000 , 99999 ),
];

if (!$s3->exists($path)) {
    throw new \InvalidArgumentException("Le fichier n'existe pas sur S3 : $path");
    }
    
    // Lecture du contenu du fichier depuis S3
    $stream = $s3->readStream($path);
    
    if (!$stream) {
    throw new \RuntimeException("Impossible de lire le fichier sur S3 : $path");
}

rewind($stream); // On remet le pointeur au début du fichier

// Log des parties multipart
$multipart = array_merge(
    [
        [
            'name'     => 'fileInformation',
            'contents' => '[STREAM]', // ← ne pas afficher raw
            'filename' => $filename,
        ]
    ],
    array_map(function ($key, $value) {
        return [
            'name'     => $key,
            'contents' => $value,
        ];
    }, array_keys($formFields), $formFields)
);

dump(">>> DEBUG MULTIPART:");
dump($multipart);

// Log des headers
dump(">>> DEBUG HEADERS:");
dump([
    'Accept' => 'application/json',
    'Cookie' => "$cookie_name=$cookie_value",
]);



//$_SERVER['SERVER_ADDR'] == "127.0.0.1" ? 



$client = new Client();
try {
$response = $client->request('POST', $url, [
'multipart' => $multipart,
'headers' => [
    'Accept' => 'application/json',
    'Cookie' => "$cookie_name=$cookie_value",
],
]);

return $response->getBody()->getContents();

} catch (RequestException $e) {
return $e->hasResponse()
? $e->getResponse()->getBody()->getContents()
: "Erreur lors de la requête : " . $e->getMessage();
}



*/























/*


   /////////////////////////////////////////////////////////////////////

    $cookie_name = ".ASPXAUTH";

    $cookie_value = decrypt(session($cookie_name));

    $url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/uploadFile';

    $s3 = Storage::disk('s3');

    $filename = basename(parse_url("https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/SCtnAYZFgiUXR0a.pdf", PHP_URL_PATH)); //Lien du fichier sur AWS S3
    $path = "extracted_doc/$filename";

    $filePath =  storage_path("app/public/extracted_doc/$filename") ;

    $formFields = [
    'projetRef' => "0D02A7F5",//reference de la PEC
    'attachemebnNumber' => rand(10000 , 99999 ),
];



if (!$s3->exists($path)) {
throw new \InvalidArgumentException("Le fichier n'existe pas sur S3 : $path");
}

// Lecture du contenu du fichier depuis S3
$stream = $s3->readStream($path);

if (!$stream) {
throw new \RuntimeException("Impossible de lire le fichier sur S3 : $path");
}

$client = new Client();
try {
$response = $client->request('POST', $url, [
    'multipart' => array_merge(
        [
            [
                'name'     => 'fileInformation',
                'contents' => $stream,
                'filename' => $filename,
            ]
        ],
        array_map(function ($key, $value) {
            return [
                'name'     => $key,
                'contents' => $value,
            ];
        }, array_keys($formFields), $formFields)
    ),
    'headers' => [
        'Accept' => 'application/json',
        'Cookie' => "$cookie_name=$cookie_value",
    ],
]);

return $response->getBody()->getContents();

} catch (RequestException $e) {
return $e->hasResponse()
    ? $e->getResponse()->getBody()->getContents()
    : "Erreur lors de la requête : " . $e->getMessage();
}

*/



 $cookie_name = ".ASPXAUTH";
 $cookie_value = decrypt(session($cookie_name));

  $apiToken =  decrypt(session("accessToken"));

 //return $folder = Folder::whereCode("nJenGNb3I0")->first();

 //https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/rx4hAEyPnCdqCF5.pdf

 //$s3_path = "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/rx4hAEyPnCdqCF5.pdf";

 $public_path = "WVVjG9P114pCQnP.pdf";

 //$s3_file = Storage::disk('s3')->get("extracted_doc/".($s3_path));F0DCD.jpg;;WVVjG9P114pCQnP.pdf
 $public_file = Storage::disk('public')->get("extracted_doc/".($public_path));
 $public = Storage::disk('public');


 $url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/priseEnChargeUploadFile';



$filePath = storage_path('app/public/extracted_doc/lfHTJ16pEahMaRJ.pdf');
//$filePath = public_path('F0DCD.jpg');

/*$formFields = [
    'projetRef' => 'DAA58FEA',
    'attachemebnNumber' => rand(10000 , 99999 ),
];*/

$formFields = [
    'pecRef' => 'DAA58FEA',
    'attachemebnNumber' => 1,
];

 $client = new Client();

    if (!file_exists($filePath) || !is_readable($filePath)) {
        throw new \InvalidArgumentException("Le fichier n'existe pas ou n'est pas lisible : $filePath");
    }
 

    try {

        // Construction du multipart
$multipart = array_merge(
    // Le fichier
    [[
        'name'     => 'fileInformation',
        'contents' => fopen($filePath, 'r'),
        'filename' => basename($filePath),
    ]],
    // Les autres champs du formulaire
    array_map(function($key, $value) {
        return [
            'name'     => $key,
            'contents' => $value,
        ];
    }, array_keys($formFields), $formFields)
     // Timeout, etc, peuvent être ajoutés ici
);

// DEBUG MULTIPART
dump(">>> DEBUG MULTIPART:");
dump($multipart);

// DEBUG HEADERS
dump(">>> DEBUG HEADERS:");
dump([
    'Accept' => 'application/json', 
    'Authorization' => "Bearer $apiToken", // ⬅️ Ici on ajoute le token Bearer
    'Cookie' => "$cookie_name=$cookie_value",
]);

// Envoi de la requête
$response = $client->request('POST', $url, [
    'multipart' => $multipart,
    'headers' => [
        'Accept' => 'application/json',
        'Cookie' => "$cookie_name=$cookie_value",
    ],
]);
      


        

        return $response->getBody()->getContents();

    } catch (RequestException $e) {
        if ($e->hasResponse()) {
            return $e->getResponse()->getBody()->getContents();
        }
        return "Erreur lors de la requête : " . $e->getMessage();
    }


// Vérifier la réponse
if ($response->successful()) {
    return "Upload réussi";

    unlink($tempPath);
} else {
    return $response->body();
}


  return  $response = Http::withHeaders([
    'Cookie' => "$cookie_name=$cookie_value",
])->post('https://pass24api.insighttelematics.tn/pass24/CreateProjetFacture', [
    "amount"=> 15000,
    "ticket"=> 0,
    "ref"=> "AAAAAAAA",
    "rows"=> [
        [
            "refPec"=> "AAAAAAAA",
            "amount"=> 15000,
            "details"=> [
              [
                "qty"=> 3,
                "unitPrice"=> 15000,
                "exclusion"=> 0,
                "reference"=> "AAAAAAAA",
                ///"comment"=> "string",
                "label"=> "ARTHEMETER",
               // "unitPriceRegMed"=> 0,
               // "qtyRegMed"=> 0,
               // "type"=> 0,
               // "specialRefund"=> true,
                "ligneId"=> 0
              ]
            ],
            "affection"=> 0,
            "origine"=> 0,
            "affectionAssocCodes"=> [
              "string"
            ]
          ]
    ],
    "exclusion"=> 0,
    "regmedValidation"=> true,
    "regMedUserName"=> "string",
    "motifExclusion"=> "string"
] )->json();

    dd(Hash::make("Test"));



    return array_filter($prestations , function ($prestation) use ($items) {

        return $prestation["name"] === "PHARMACIE";
        // return in_array()
        //  return array_key_exists($prestation["name"],$items) && count($items[$prestation["name"]]["labels"])>0;
         
        });
    
    
    header( 'Content-Type: text/plain' );
    echo 'Host: ' . $_SERVER['HTTP_HOST'] . "</br>" . "\n";
    echo 'Remote Address: ' . $_SERVER['REMOTE_ADDR'] . "</br>" . "\n";
  //  echo 'X-Forwarded-For: ' . $_SERVER['HTTP_X_FORWARDED_FOR'] . "</br>" . "\n";
 //   echo 'X-Forwarded-Proto: ' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . "</br>" . "\n";
    echo 'Server Address: ' . $_SERVER['SERVER_ADDR'] . "</br>" . "\n";
    echo 'Server Port: ' . $_SERVER['SERVER_PORT'] . "</br>" . "\n\n";


    return "";

   // return public_path("app/public/extracted_doc/0ryesWvKFS1Sitf.pdf");

    if (Storage::disk('public')->exists(("extracted_doc/0ryesWvKFS1Sitf.pdf"))) {
      
        Storage::disk('public')->delete(("extracted_doc/0ryesWvKFS1Sitf.pdf"));

        return "delete okay \n\n";

    }

    else{

        return "file does not exist \n\n";
 
 
     }

    if (Storage::disk('s3')->exists('extracted_doc/Lv5R0bnFdhPML37.pdf')) {
      
        Storage::delete(storage_path("app/public/extracted_doc/Lv5R0bnFdhPML37.pdf"));

        return "delete ok";

        $s3_file = Storage::disk('s3')->get("/extracted_doc/jHNK7QZKzGfiGks.pdf");
        $s3 = Storage::disk('public');
        $s3->put(("extracted_doc/jHNK7QZKzGfiGks.pdf"), $s3_file);

        return "save ok";
    }
    else{

       return "file does not exist";


    }

  

    return "ok";

    


    $folders = $query->get();//paginate(10)->withQueryString();


    $filtered = $folders->filter(function ($folder, $key) {
        return $folder->all_prestations_exist();
    });

    $filtered = $filtered->filter(function ($folder, $key) {
        return $folder->all_prices_are_coherent();
    });

    $filtered = $filtered->filter(function ($folder, $key) {
        return $folder->get_amount() >= 1000 &&  $folder->get_amount() <= 100000 ;
    });


     
    $filtered->all();
    
 
    return $filtered;



     foreach ($folders as $key => $folder) {
        if ($folder->all_prestations_exist()) {
            return true;
        }
     }

    /*$logs = Search::select('id','code')->get();

    foreach ($logs as $key => $log) {
        $log->code = Str::random(10);
        $log->save();
    }

    return 'ok';*/

  //  dd(Schedule::where('start_at' ,'>', now()->addHour() )->orderBy('start_at')->get());
   

    $file_path = storage_path("app/public/extracted_doc/cnMRSEJ936zRwHb.pdf");

   $content = (fopen($file_path, 'r+'));

  // $uploadedFile = new \Symfony\Component\HttpFoundation\File\File(storage_path("app/public/extracted_doc/cnMRSEJ936zRwHb.pdf"));
  // dd($uploadedFile);

  return Storage::disk('s3')->put("extracted_doc/test.pdf", $content);

 return  $s3_path = $uploadedFile->copy( $s3_destination);
 
 Storage::disk('s3')->put('https://extract237.s3.eu-west-3.amazonaws.com/cnMRSEJ936zRwHb.pdf' , fopen(storage_path("app/public/extracted_doc/cnMRSEJ936zRwHb.pdf"), 'r+'));

 return  fopen("/var/www/extract/storage/app/public/extracted_doc/cnMRSEJ936zRwHb.pdf", 'r+');

// Storage::get('public/extracted_doc/O1FuCvdTfD9xkye.pdf');
 
 $contents = Storage::disk('local')->get(("public/extracted_doc/MKDciLsgzFc89lK.pdf"));

/*
   // $test= "avr. 4, 2023 CALCID DENK CP | 100522 { 4950 4950 80 | 990 ! 3960";

   $parsers =  [["regex"=>'/([a-z0-9A-Z!‘\`@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?\s]*)(?:\s+\d{5,15})/is',"value"=>"$1"],["regex"=>'/20[0-2][0-9](\s)?/',"value"=>""] ];
  

    $test = "2024 OTRIVINE 005% 102276";

    foreach ($parsers as $parser) {
        # code...
      
      $test= preg_replace($parser["regex"], $parser["value"], $test);
      
      
    }

    return $test;

  //  ["order"=>4,"name"=>"quantity_sanitizer","regexes"=>['/([\d]{3,6})[\s\-]+[a-zA-Z!‘\`@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?\s]*[\s\-]+([\d]{3,6})$/is'=>'$1 1 $2']]


   return  $match = preg_replace('/([a-z0-9A-Z!‘\`@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?\s]*)(?:\d{6,15})/is',"$1",$test);
     $match = preg_replace("/\,\d{4}/","",$test);

    return $match2 = preg_replace('/([\d]{3,6})[\s\-]+[a-zA-Z!‘\`@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?\s]*[\s\-]+([\d]{3,6})$/i','$1 1 $2',$match);
*/
   
    header( 'Content-Type: text/plain' );
    echo 'Host: ' . $_SERVER['HTTP_HOST'] . "</br>" . "\n";
    echo 'Remote Address: ' . $_SERVER['REMOTE_ADDR'] . "</br>" . "\n";
  //  echo 'X-Forwarded-For: ' . $_SERVER['HTTP_X_FORWARDED_FOR'] . "</br>" . "\n";
 //   echo 'X-Forwarded-Proto: ' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . "</br>" . "\n";
    echo 'Server Address: ' . $_SERVER['SERVER_ADDR'] . "</br>" . "\n";
    echo 'Server Port: ' . $_SERVER['SERVER_PORT'] . "</br>" . "\n\n";
    
});



