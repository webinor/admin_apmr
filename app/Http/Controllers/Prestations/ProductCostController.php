<?php

namespace App\Http\Controllers\Prestations;

use App\Http\Controllers\Controller;
use App\Models\Prestations\ProductCost;
use App\Http\Requests\Prestations\StoreProductCostRequest;
use App\Http\Requests\Prestations\UpdateProductCostRequest;
use App\Services\Prestations\ProductCostService;

class ProductCostController extends Controller
{
    protected  $product_cost_service;

    public function __construct(ProductCostService $product_cost_service) {
        
        $this->product_cost_service = $product_cost_service;
          
        //$this->authorizeResource(ProductCost::class, "product");

        }


    public function index()  {
        
        $index_variables = $this->product_cost_service->getIndexVariables();
       
        return  $this->product_cost_service->getView('products.index', $index_variables);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $index_variables = $this->product_cost_service->getCreateVariables();
       
        return  $this->product_cost_service->getView('products.manage', $index_variables);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Prestations\StoreProductCostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductCostRequest $request)
    {
        $response =  $this->product_cost_service->createProductCost($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestations\ProductCost  $productCost
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCost $productCost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestations\ProductCost  $productCost
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCost $productCost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Prestations\UpdateProductCostRequest  $request
     * @param  \App\Models\Prestations\ProductCost  $productCost
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductCostRequest $request, ProductCost $productCost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestations\ProductCost  $productCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCost $productCost)
    {
        //
    }
}
