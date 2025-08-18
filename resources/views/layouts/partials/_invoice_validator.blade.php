

<div class="form-group row d-none">
  <div id="service-layout" class="col-sm-12 mb-3 mb-sm-0">
      <select  
          class="form-control form-control" name="edit_service" id="service" placeholder="">

          @forelse ($service_types as $service_type)
              @if ($loop->first)
                  <option value="">Selectionnez le service concerné
                  </option>
              @endif

              <option value="{{ $service_type->code }}">{{ $service_type->get_fullname() }}</option>

          @empty

              <option value="">Aucun service disponible</option>
          @endforelse

      </select>
      <div class="valid-feedback">
      </div>
      <div class="invalid-feedback">
      </div>
  </div>
</div>

<div class="form-group row d-none">
  <div id="status-layout" class="col-sm-12 mb-3 mb-sm-0">
      <select  
          class="form-control form-control" name="edit_status" id="status" placeholder="">
  
          {{-- <option value="">Indiquez le statut</option> --}}
          <option value="0">Non Couverte</option>
          <option value="1">Couverte</option>
          {{-- <option value="2">Avis du medecin</option> --}}

          
                  

      </select>
      <div class="valid-feedback">
      </div>
      <div class="invalid-feedback">
      </div>
  </div>
</div>

<div class="form-group row d-none">
  <div id="product-layout" class="col-sm-12 mb-3 mb-sm-0">
      <select  
          class="form-control form-control" name="edit_product" id="product" placeholder="">

          @forelse ($product_types as $product_type)
              @if ($loop->first)
                  <option value="">Selectionnez le product concerné
                  </option>
              @endif

              <option value="{{ $product_type->code }}">{{ $product_type->get_fullname() }}</option>

          @empty

              <option value="">Aucun product disponible</option>
          @endforelse

      </select>
      <div class="valid-feedback">
      </div>
      <div class="invalid-feedback">
      </div>
  </div>
</div>

<div id="old-file-previews-return-container" class="d-none file-previews-return-container col-lg-2 overflow-auto" style="max-height: 800px;width: 12%;" >

                           


  <div  class="save-all">

    <div  class="mt-1 d-none">
      <button  type="button"
          class="text-white w-100 btn btn-block btn-{{ $color }} fw-bold btn-lg font-weight-medium auth-form-btn">
          {{ 'Tout Enregistrer' }}
      </button>
  </div>


<div  class="d-none d-flex justify-content-center mt-3">

    <div class="inner-loading dot-flashing"></div>

</div>


  </div> 
  
    </div>


   
   

    <div class="col-lg-12 grid-margin stretch-card" {{-- style="width: 88%;" --}}>
        <div class="card">
            <div class="card-body">
     
              @if (1)

     <div class="d-flex">
      
      <div class=" row d-flex col-3">
        <div class="col-3" >Prest:</div> 
        <div class="fw-bold mb-1 col-9 name" >{{ $folder ? $folder->slip->provider->get_name() : '' }}</div>
      </div>

      <div class=" row col-5">
        <div class="col-3" >Prestations :</div> 
        <div class="fw-bold mb-2 col-9" >
          <ul class="finance_services">
            @if ($folder)
              
            @foreach ($folder->get_services()  as $service)
            <li>{{ $service }}</li>
            @endforeach

            @endif
          </ul>
        </div>
      </div>

      
          
      
      <div class=" row col-3">
        
        <div class="d-flex">
          <div class="col-3" >Assuré :</div> 
        <div class="fw-bold mb-0 col-8" >
          <span class="insured"></span>

          {{-- @include('layouts.partials._edit_template') --}}

        </div>
        </div>

        <div  class="d-flex ">
          <div class="col-3" >Bénef. :</div> 
          <div class="fw-bold mb-0 col-8" ><span class="beneficiary"></span>
          
            {{-- @include('layouts.partials._edit_template') --}}

          
          </div>
        </div>

        <div  class="d-flex">
          <div class="col-3" >Dossier :</div> 
          <div class="fw-bold mb-0 col-8" ><span class="folder_id">{{ $folder ? $folder->get_identification() :'' }}</span>

            {{-- @include('layouts.partials._edit_template') --}}

          </div>
        </div>

      </div>

     

      <div class="row col-3">
        <div class="col-3" >Ref:</div> 
        <div class="fw-bold col-7" >
          <ul class="reference_ids">
            @if ($folder)

            @foreach ($folder->invoices  as $invoice)
            <li  class="{{ $invoice->reference == "UNDEFINED REFERENCE" ? "text-danger" : "text-success" }}">{{ $invoice->reference }} <span id="api-final-{{ $invoice->reference }}"> {{ ($invoice->is_invoice_validated_remote() ) ? "( PEC validée )" : "" }}</span>
              
              @include('layouts.partials._edit_invoice_template' , ['id'=> 'reference', 'url_update'=>('/api/update-reference') , 'invoice'=>$invoice])

        </li>
            @endforeach

            @endif
            
          </ul>
        </div>
      </div>
    </div>   
    
  

                <h4 class="card-title text-center">

                  {{-- <a class="ms-3 edit blink "
                  href="#"><i
                    class="menu-icon mdi mdi-add"></i>
              </a> --}}


            

              @can('update', $folder->invoices[0])

              <a href="#" 
              data-folder="{{ $folder ? $folder->code : '' }}" 
              data-provider-category="{{ $folder->slip->provider->provider_category->get_name()  }}"
              data-table="{{ $folder ? $folder->code : '' }}" class="new-line btn btn-{{ $color }} fw-bold btn-lg text-white mb-0 me-0" type="button"><i class="mdi mdi-plus"></i>Nouvelle ligne</a>

              @endcan



             

                </h4>

               

                <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Facture créee avec succes</h6>
                </div>
               



            @include('layouts.partials._validate_invoice')


           


        
                @endif 
              
               
                
            </div>
        </div>
    </div>

