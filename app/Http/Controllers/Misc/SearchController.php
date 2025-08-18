<?php

namespace App\Http\Controllers\Misc;

use App\Models\Misc\Search;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\FetchSearchesRequest;
use App\Http\Requests\StoreSearchRequest;
use App\Http\Requests\UpdateSearchRequest;
use App\Services\Misc\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected  $search_service;

    public function __construct(SearchService $search_service) {
        
        $this->search_service = $search_service;
          
        $this->authorizeResource(Search::class, "search");

        }
        


    public function index(Request $request)  {

        $results = $request->results ? (int)$request->results : 10;

        $query = $request->qry ? $request->qry : "";
        
        $index_variables = $this->search_service->getIndexVariables($results,$query,$request);
       
        return  $this->search_service->getView('search.index', $index_variables);

    }


    public function getSearches(FetchSearchesRequest $request)
    {

        return $this->search_service->getSearches($request->validated());

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
     * @param  \App\Http\Requests\StoreSearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSearchRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function show(Search $search)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function edit(Search $search)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSearchRequest  $request
     * @param  \App\Models\Misc\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSearchRequest $request, Search $search)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function destroy(Search $search)
    {
        //
    }
}
