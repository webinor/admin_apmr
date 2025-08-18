<?php

namespace App\Http\Controllers\User;


use App\Models\User\LoginDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginDetailRequest;
use App\Http\Requests\UpdateLoginDetailRequest;

class LoginDetailController extends Controller
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
     * @param  \App\Http\Requests\StoreLoginDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoginDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function show(LoginDetail $loginDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(LoginDetail $loginDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoginDetailRequest  $request
     * @param  \App\Models\Misc\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoginDetailRequest $request, LoginDetail $loginDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\LoginDetail  $loginDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoginDetail $loginDetail)
    {
        //
    }
}
