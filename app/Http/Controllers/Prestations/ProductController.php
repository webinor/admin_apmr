<?php

namespace App\Http\Controllers\Prestations;

use App\Models\Prestations\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\FetchProductsRequest;
use App\Http\Requests\Prestations\StoreProductRequest;
use App\Http\Requests\Prestations\UpdateProductRequest;
use App\Services\Prestations\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected  $product_service;

    public function __construct(ProductService $product_service) {
        
        $this->product_service = $product_service;
          
        $this->authorizeResource(Product::class, "product");

        }


    public function index(Request $request)  {

        $results = $request->results ? (int)$request->results : 10;
        
        //  dd($results);
  
        if ($request->qry && $request->qry != "") {
  
          $query = $request->qry ? $request->qry : "";
             
          $index_variables = $this->product_service->searchProducts($query);
  
      }

      else{

        $index_variables = $this->product_service->getIndexVariables($results);

      }
        
       
       
        return  $this->product_service->getView('products.index', $index_variables);

    }

    public function GetProducts(FetchProductsRequest $request)
    {
        return $this->product_service->GetProducts($request->validated());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $index_variables = $this->product_service->getCreateVariables($request);
       
        return  $this->product_service->getView('products.manage', $index_variables);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Prestations\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $response =  $this->product_service->createProduct($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestations\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $show_variables = $this->product_service->getShowVariables($product);
       
        return  $this->product_service->getView('products.manage', $show_variables);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestations\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $show_variables = $this->product_service->getEditVariables($product);
       
        return  $this->product_service->getView('products.manage', $show_variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Prestations\UpdateProductRequest  $request
     * @param  \App\Models\Prestations\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $response =  $this->product_service->updateProduct($request->validated() , $product);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestations\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
