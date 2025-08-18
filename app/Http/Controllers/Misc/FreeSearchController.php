<?php

namespace App\Http\Controllers\Misc;

use App\Models\Misc\FreeSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\StoreFreeSearchRequest;
use App\Http\Requests\UpdateFreeSearchRequest;
use App\Services\Misc\FreeSearchService;
use Illuminate\Http\Request;

class FreeSearchController extends Controller
{


    protected  $freesearch_service;

    public function __construct(FreeSearchService $freesearch_service) {
          $this->freesearch_service = $freesearch_service;
          
          //$this->authorizeResource(Folder::class, "folder");

        }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = '')
    {
        $index_variables = $this->freesearch_service->getIndexVariables($type);
       

        return  $this->freesearch_service->getView('free_search.index', $index_variables);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Misc\StoreFreeSearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function get_search(StoreFreeSearchRequest $request)
    {
        return $this->freesearch_service->get_search($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\FreeSearch  $freeSearch
     * @return \Illuminate\Http\Response
     */
    public function show(FreeSearch $freeSearch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\FreeSearch  $freeSearch
     * @return \Illuminate\Http\Response
     */
    public function edit(FreeSearch $freeSearch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFreeSearchRequest  $request
     * @param  \App\Models\Misc\FreeSearch  $freeSearch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFreeSearchRequest $request, FreeSearch $freeSearch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\FreeSearch  $freeSearch
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreeSearch $freeSearch)
    {
        //
    }
}
