<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\Misc\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    protected $company_service;

  public function __construct(CompanyService $company_service) {
        
    $this->company_service = $company_service;
      
   // $this->authorizeResource(Company::class, "employee");

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)  {
        
        $results = $request->results ? (int)$request->results : 10;
  
  
          $variables = $this->company_service->getIndexVariables($results);
         
          return  $this->company_service->getView('company.index', $variables);
  
      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $variables = $this->company_service->getCreateVariables();
         
        return  $this->company_service->getView('company.manage', $variables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $response =  $this->company_service->createCompany($request->validated() , $request);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $variables = $this->company_service->getEditVariables($company);
           
        return  $this->company_service->getView('company.manage', $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $response =  $this->company_service->updateCompany($request->validated() , $company , $request);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
