<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyWheelChairRequest;
use App\Http\Requests\UpdateCompanyWheelChairRequest;
use App\Models\Company;
use App\Models\Operations\CompanyWheelChair;
use App\Models\WheelChair;

class CompanyWheelChairController extends Controller
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
     * @param  \App\Http\Requests\StoreCompanyWheelChairRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyWheelChairRequest $request)
    {
        
        $validated = $request->validated();
    // Récupérer company & wheel_chair
    $company = Company::where('code', $validated['company'])->firstOrFail();
    $wheelChair = WheelChair::where('code', $validated['wheel_chair'])->firstOrFail();

    // Vérifier si l’association existe déjà
    $exists = $company->wheel_chairs()
                      ->where('wheel_chairs.id', $wheelChair->id)
                      ->exists();

    if ($exists) {
        // ✅ Mettre à jour le prix
        $company->wheel_chairs()->updateExistingPivot($wheelChair->id, [
            'price' => $validated['price'],
            'valid_from' => now(), // ou une date du request
        ]);
    } else {
        // ✅ Créer la relation avec le prix
        $company->wheel_chairs()->attach($wheelChair->id, [
            'price' => $validated['price'],
            'valid_from' => now(), // ou une date du request
        ]);
    }

      // Charger avec pivot
      $company->load(['wheel_chairs' => function ($q) use ($wheelChair) {
        $q->where('wheel_chair_id', $wheelChair->id);
    }]);

    return response()->json([
        'success' => true,
        'exists' => $exists,
        'company_wheel_chair' => $company->wheel_chairs->first()->pivot,
        'wheelChair'=>$wheelChair,
        'message' => $exists ? "Prix mis à jour avec succès" : "Nouvelle relation créée avec succès"
    ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operations\CompanyWheelChair  $companyWheelChair
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyWheelChair $companyWheelChair)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operations\CompanyWheelChair  $companyWheelChair
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyWheelChair $companyWheelChair)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyWheelChairRequest  $request
     * @param  \App\Models\Operations\CompanyWheelChair  $companyWheelChair
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyWheelChairRequest $request, CompanyWheelChair $companyWheelChair)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operations\CompanyWheelChair  $companyWheelChair
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyWheelChair $companyWheelChair)
    {
        //
    }
}
