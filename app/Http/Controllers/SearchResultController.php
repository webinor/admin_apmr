<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSearchResultRequest;
use App\Http\Requests\UpdateSearchResultRequest;
use App\Models\Misc\SearchResult;

class SearchResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreSearchResultRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSearchResultRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\SearchResult  $searchResult
     * @return \Illuminate\Http\Response
     */
    public function show(SearchResult $searchResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\SearchResult  $searchResult
     * @return \Illuminate\Http\Response
     */
    public function edit(SearchResult $searchResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSearchResultRequest  $request
     * @param  \App\Models\Misc\SearchResult  $searchResult
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSearchResultRequest $request, SearchResult $searchResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\SearchResult  $searchResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchResult $searchResult)
    {
        //
    }
}
