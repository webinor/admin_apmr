<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\ImportTherapeuticClassRequest;
use App\Http\Requests\StoreTherapeuticClassRequest;
use App\Http\Requests\UpdateTherapeuticClassRequest;
use App\Models\Prestations\TherapeuticClass ;
use App\Services\Misc\FileService;
use App\Services\Misc\TherapeuticClassService;
use Illuminate\Http\Request;

class TherapeuticClassController extends Controller
{

    protected $therapeutic_class_service;
    protected $file_service;

    public function __construct(TherapeuticClassService $therapeutic_class_service , FileService $file_service)
    {
        $this->therapeutic_class_service = $therapeutic_class_service;
        $this->file_service = $file_service;

        $this->authorizeResource(TherapeuticClass::class, "therapeutic-class");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
            $variables = $this->therapeutic_class_service->getIndexVariables();
        

        $view = $this->therapeutic_class_service->getView("therapeutic_classes.index", $variables);

        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $variables = $this->therapeutic_class_service->getCreateVariables();

        $view = $this->therapeutic_class_service->getView("therapeutic_classes.manage", $variables);

        return $view;
    }

    public function import(ImportTherapeuticClassRequest $request)
  //  public function import(Request $request)
    {

        return $this->therapeutic_class_service->storeTherapeuticClasses($request->validated());

      /*  $uploaded_files = $this->file_service->UpdloadFile($request->validated() , "therapeutic_classes" , "csv");
        
      
        $therapeutic_classes = $uploaded_files[0];
        $fileContents = file(storage_path("app/public/csv/".$therapeutic_classes->path));*/

     //   dd($request);

        $file = $request->file("therapeutic_classes");
        $fileContents = file($file->getPathname());

        dd($file);

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);

            dd($data[0]);

            echo $data[0] . " ;; \n";
            /*Lawyer::create([
            'code' => Str::random(10),
            'registration_number' => $data[0],
            'first_name' => $data[0],
            'last_name' => $data[0],
            'city' => $data[0],
            'oath_at' => $data[0],
            'postbox' => $data[1],
            // Add more fields as needed
        ]);*/
        }


        return "ok";
        return redirect()
            ->back()
            ->with("success", "CSV file imported successfully.");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTherapeuticClassRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTherapeuticClassRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Services\TherapeuticClass  $therapeuticClass
     * @return \Illuminate\Http\Response
     */
    public function show(TherapeuticClass $therapeuticClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Services\TherapeuticClass  $therapeuticClass
     * @return \Illuminate\Http\Response
     */
    public function edit(TherapeuticClass $therapeuticClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTherapeuticClassRequest  $request
     * @param  \App\Models\Services\TherapeuticClass  $therapeuticClass
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTherapeuticClassRequest $request, TherapeuticClass $therapeuticClass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Services\TherapeuticClass  $therapeuticClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(TherapeuticClass $therapeuticClass)
    {
        //
    }
}
