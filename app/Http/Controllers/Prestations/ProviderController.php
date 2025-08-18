<?php

namespace App\Http\Controllers\Prestations;

use App\Models\Operations\Provider;
use App\Services\ProviderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operations\StoreProviderRequest;
use App\Http\Requests\Operations\UpdateProviderRequest;
use Illuminate\Http\Request;

class ProviderController extends Controller
{

    protected  $provider_service;

    public function __construct(ProviderService $provider_service) {

          $this->provider_service = $provider_service;
       //   $this->authorizeResource(Provider::class , 'supplier');
    }


    public function index(Request $request)  {
        
      $results = $request->results ? (int)$request->results : 10;


        $index_variables = $this->provider_service->getIndexVariables($results);
       
        return  $this->provider_service->getView('providers.index', $index_variables);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProviders()
    {
        return Provider::get()->pluck('name');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $create_variables = $this->provider_service->getCreateVariables();
       
        return  $this->provider_service->getView('providers.manage', $create_variables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProviderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProviderRequest $request)
    {
        $response =  $this->provider_service->createProvider($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
                $show_variables = $this->provider_service->getShowVariables($provider);
       
        return  $this->provider_service->getView('providers.manage', $show_variables);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider)
    {
                $edit_variables = $this->provider_service->getEditVariables($provider);
       
        return  $this->provider_service->getView('providers.manage', $edit_variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProviderRequest  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProviderRequest $request, Provider $provider)
    {
        $response =  $this->provider_service->updateprovider($request->validated() ,  $request);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
