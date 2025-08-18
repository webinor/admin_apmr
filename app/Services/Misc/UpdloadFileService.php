<?php

namespace App\Services\Misc;

use App\Addons\DataConstructor;
use App\Addons\Instanciator;
use App\Models\Misc\Administrative;
use App\Models\Misc\Anda;
use App\Models\Misc\Bank;
use App\Models\Misc\File;
use App\Models\Misc\Operational;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Models\Misc\Resource;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\Transfert;
use App\Models\Transit\ImportDeclaration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\WelcomeUserNotification;
use App\Services\Transit\ImportDeclarationService;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault;

class UpdloadFileService
{
    use DataConstructor ;


    public function execute($file , $destination , $path , $s3_destination , $should_upload_to_s3 = true)
    {
      //  $encrypted = encrypt($file);
       
     ///////////////////////////////////////////////////////old 
     //$file->move( $destination, $path);

     // $contents = Storage::disk('local')->get(("public/extracted_doc/".$path));

     // dd($contents);

     if ($_SERVER['SERVER_ADDR'] != "127.0.0.1" && $should_upload_to_s3 ) {

    
    Storage::disk('s3')->put('extracted_doc/' . $path, file_get_contents($file));
    //Storage::disk('s3')->put('extracted_doc/' . $path, fopen(storage_path("app/public/extracted_doc/$path"), 'r+'));
      
     //  Storage::delete(storage_path("app/public/extracted_doc/$path"));
     }
     else{

     $file->move( $destination, $path);


     }

    // $path = 'avatars/'. $user->id .'/'. $filename;

    // $s3_path =  Storage::putFile->disk('s3')->("public/extracted_doc/".$path, "public/extracted_doc/".$path, 'public');

     // $s3_path = $contents->storePublicly( $s3_destination);

     // dd($s3_path);


      return "https://extract237-pass24.s3.eu-west-3.amazonaws.com/".('extracted_doc/' . $path);
        

        // Check to see if we have a valid file uploaded
        if ($file) {
          //  FileVault::encrypt("public/ad_images/$path");
        }

    }


    public function getFileInstance($file) : File
    {
        $fileName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $extension = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_EXTENSION
        );

        $extension = $extension == "" ? "pdf" : $extension;
        $path = Str::random(15) . "." ."$extension";
        $type = $file->getClientMimeType();
        $size = $file->getSize();

        $newfile = new File();
        $newfile->code = Str::random(10);
        $newfile->name = $fileName;
        $newfile->path = $path;
        $newfile->type = $type;
        $newfile->size = $size;

        return $newfile ;
    }

}
