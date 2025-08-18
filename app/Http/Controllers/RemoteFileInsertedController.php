<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRemoteFileInsertedRequest;
use App\Http\Requests\UpdateRemoteFileInsertedRequest;
use App\Models\Misc\RemoteFileInserted;

class RemoteFileInsertedController extends Controller
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
     * @param  \App\Http\Requests\StoreRemoteFileInsertedRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRemoteFileInsertedRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\RemoteFileInserted  $remoteFileInserted
     * @return \Illuminate\Http\Response
     */
    public function show(RemoteFileInserted $remoteFileInserted)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\RemoteFileInserted  $remoteFileInserted
     * @return \Illuminate\Http\Response
     */
    public function edit(RemoteFileInserted $remoteFileInserted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRemoteFileInsertedRequest  $request
     * @param  \App\Models\Misc\RemoteFileInserted  $remoteFileInserted
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRemoteFileInsertedRequest $request, RemoteFileInserted $remoteFileInserted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\RemoteFileInserted  $remoteFileInserted
     * @return \Illuminate\Http\Response
     */
    public function destroy(RemoteFileInserted $remoteFileInserted)
    {
        //
    }
}
