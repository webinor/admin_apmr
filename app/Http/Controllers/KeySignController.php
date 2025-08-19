<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKeySignRequest;
use App\Http\Requests\UpdateKeySignRequest;
use App\Models\Misc\KeySign;

class KeySignController extends Controller
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
     * @param  \App\Http\Requests\StoreKeySignRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKeySignRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\KeySign  $keySign
     * @return \Illuminate\Http\Response
     */
    public function show(KeySign $keySign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\KeySign  $keySign
     * @return \Illuminate\Http\Response
     */
    public function edit(KeySign $keySign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKeySignRequest  $request
     * @param  \App\Models\Misc\KeySign  $keySign
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKeySignRequest $request, KeySign $keySign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\KeySign  $keySign
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeySign $keySign)
    {
        //
    }
}
