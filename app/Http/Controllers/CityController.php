<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use App\Services\Misc\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $city_service;

    public function __construct(CityService $city_service) {
          
      $this->city_service = $city_service;
        
     // $this->authorizeResource(Company::class, "employee");
  
      }
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index(Request $request)  {
          
          $results = $request->results ? (int)$request->results : 10;
    
    
            $variables = $this->city_service->getIndexVariables($results);
           
            return  $this->city_service->getView('city.index', $variables);
    
        }
  
      /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function create()
      {
          
          $variables = $this->city_service->getCreateVariables();
           
          return  $this->city_service->getView('city.manage', $variables);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $response =  $this->city_service->createcity($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $variables = $this->city_service->getEditVariables($city);
           
          return  $this->city_service->getView('city.manage', $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCityRequest  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        $response =  $this->city_service->updateCity($request->validated() , $city);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }
}
