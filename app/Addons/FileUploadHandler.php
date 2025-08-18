<?php 
namespace App\Addons;

use App\Services\Misc\UpdloadFileService;

trait FileUploadHandler {


    public function handleFileUpload( $file,  $location , $should_upload_to_s3 =true)
    {
        $upload_file_service = new UpdloadFileService();

        if ($file) {
            $newfile = $upload_file_service->getFileInstance($file);

            $upload_file_service->execute(
                $file,
                $location,
                $newfile->path,
                "",
                $should_upload_to_s3
            );

             return $newfile;


           

            //return $resource;

            //$resource->document()->save($newfile);
        }
    }

    public function old_handleFileUpload($resource, $file, $token , $location , $relation = "file")
    {
        $upload_file_service = new UpdloadFileService();

        if ($file) {
            $newfile = $upload_file_service->getFileInstance($file);

            $upload_file_service->execute(
                $file,
                $location,
                $newfile->path,
                ""
            );

            if (!$resource->{$relation}) {
                $newfile->user_id = $token;

                return $newfile;


            } else {
                $oldfile = $resource->{$relation};
                $oldfile->name = $newfile->name;
                $oldfile->path = $newfile->path;
                $oldfile->type = $newfile->type;
                $oldfile->size = $newfile->size;

                $oldfile->save();

                return $oldfile;
            }

            //return $resource;

            //$resource->document()->save($newfile);
        }
    }
}