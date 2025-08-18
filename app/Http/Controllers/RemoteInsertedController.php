<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRemoteInsertedRequest;
use App\Http\Requests\UpdateRemoteInsertedRequest;
use App\Models\Misc\RemoteInserted;

class RemoteInsertedController extends Controller
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
     * @param  \App\Http\Requests\StoreRemoteInsertedRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRemoteInsertedRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\RemoteInserted  $remoteInserted
     * @return \Illuminate\Http\Response
     */
    public function show(RemoteInserted $remoteInserted)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\RemoteInserted  $remoteInserted
     * @return \Illuminate\Http\Response
     */
    public function edit(RemoteInserted $remoteInserted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRemoteInsertedRequest  $request
     * @param  \App\Models\Misc\RemoteInserted  $remoteInserted
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRemoteInsertedRequest $request, RemoteInserted $remoteInserted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\RemoteInserted  $remoteInserted
     * @return \Illuminate\Http\Response
     */
    public function destroy(RemoteInserted $remoteInserted)
    {
        //
    }
}
