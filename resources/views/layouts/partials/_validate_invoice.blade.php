<form class="forms-invoice">

  @csrf
  <input id="token" type="hidden" class="form-control"
      value="">
  <input id="url" type="hidden"
      value="">
  <input id="current-folder" type="hidden" class="resource form-control"
      value="{{ $folder ? $folder->code : '' }}">
  <input id="action" type="hidden" class="action form-control"
      value="">
  <input class="form-control" id="resource_code" type="hidden"
      value="">



 
<div class="table-responsive">
  <table id="{{ $folder ? $folder->code : '' }}" class="table table-hove invoice-table" data-url="{{ url('api/update-invoice') }}" >
    <thead>
      <tr>
        <th>#</th>
        <th>Designation</th>
        <th>Status</th>
        <th>Quantité</th>
        <th>Prix unitaire prest.</th>
        <th>Prix total prest.</th>
        <th>Prix unitaire sugg.</th>
        <th>Prix total sugg.</th>
        <th>Ecart</th>
        <th>Observation</th>
        <th>Action</th>

        {{-- <th>Prestation</th> --}}

        {{-- @can('update', $folder)
            
        <th>Action</th>

        @endcan --}}
      </tr>
    </thead>
    <tbody>
      @php
          $index=1;
          $total = 0;
          $total_suggested = 0;
      @endphp

      @if ($folder)
          
     
      @foreach ($folder->invoices  as $invoice)



      @foreach ($invoice->invoice_lines as $invoice_line)

      @php
          $total += $invoice_line->get_total();
          $total_suggested += $invoice_line->get_suggest_total(false);
          $gap= $invoice_line->get_gap();
      @endphp
          
      <tr class="{{ $invoice_line->get_coverage_class() }}" id="{{ $invoice_line->code }}" data-invoice="{{ $invoice->code }}" data-invoice-line="{{ $invoice_line->code }}"
        data-description="{{ $invoice_line->description }}"
        data-status="{{ $invoice_line->get_coverage_text() }}"
        data-quantity="{{ $invoice_line->get_quantity() }}"
        data-price="{{ $invoice_line->get_price() }}"
        data-total="{{ $invoice_line->get_total()  }}"
        data-provider-category="{{ $invoice_line->invoice->folder->slip->provider->provider_category->get_name()  }}"
        data-observation="{{ $invoice_line->get_observation()  }}"
        data-prestation_type="{{ $invoice_line->invoice->prestationable->get_prestationable_type()}}"
        data-service="{{ $invoice_line->invoice->prestationable->get_fullname() }}"
        
        data-item-price-suggested="{{ $invoice_line->get_suggest_price() >0 ? $invoice_line->get_suggest_price() : 0 }}"
        data-item-quantity-suggested="{{ $invoice_line->get_quantity() }}"

        
        data-provider-price="{{ $invoice_line->get_price() }}"

        
        >
        <td>{{ $index++ }}</td>
        <td data-bs-toggle="tooltip" data-bs-title="{{ $invoice_line->get_matching_prestation() }}" >{{ $invoice_line->description }}</td>
        <td data-coverage="{{ $invoice_line->get_coverage() }}">{{ $invoice_line->get_coverage_text() }}</td>
        <td >{{ $invoice_line->get_quantity() }}</td>
        <td >{{ $invoice_line->get_price() }}</td>
        <td class="partial-total">{{ $invoice_line->get_total()  }}</td>
       {{--  <td >{{ $invoice_line->invoice->prestationable->get_fullname() }}</td> --}}
       <td >{{ $invoice_line->get_suggest_price() >0 ? $invoice_line->get_suggest_price() :'' }}</td>
       <td class="partial-total-suggested" >{{ $invoice_line->get_suggest_total() >0 ? $invoice_line->get_suggest_total() :'' }}</td>
       <td>
        @if ( $gap == null )
            
      {{ $gap }}
       
       

        @elseif( $gap >= 0 && $gap > 15)

        <div  class="fw-bold badge badge-opacity-danger">
          {{ $gap }} %
          <i class="mdi mdi-menu-up"></i>
        </div>

        @else

        <div  class="fw-bold badge badge-opacity-success">
          {{ $gap }} %
          <i class="mdi mdi-menu-down"></i>
        </div>
            
        @endif
        
      
      </td>
       <td>
       <ul>
        @foreach ($invoice_line->get_suggested_observations() as $observation)
            <li class="fw-bold" >{{ $observation->title }}</li>
        @endforeach
       </ul>
       </td>

        @can('update', $folder)

        <td>

          @if ($invoice_line->get_coverage() != 0 && $invoice_line->get_coverage() != '' )
              
          

          @if ($folder && !$folder->validate_at)
          <a class="edit-item-validate me-3 edit blink" data-folder="{{ $folder->code }}"
              href="#"><i
                class="menu-icon mdi mdi-table-edit"></i>
          </a>
          @endif

          {{--  <a  class="delete-item-validate delete blink d-none" href="#"><i
              class="menu-icon mdi mdi-close-circle"></i></a> 
          <input id="input" type="hidden"
            value=""> --}}
          <div class="blink loader d-none d-flex justify-content-center mt-3">

          <div class="inner-loading dot-flashing"></div>
          </div>

          @endif


        </td>
            
        @endcan  {{----}}

      </tr>
      
@endforeach
@endforeach

@php
    $total_gap =  $folder->get_gap($total , $total_suggested) ;
@endphp
<tr id="line-total" class=" fw-bold">
<td></td>
<td></td>
<td></td>
<td></td>
<td>Total</td>
<td id="all-total">{{ $total }}</td>
<td></td>
<td id="all-total-suggested">{{ $total_suggested }}</td>
<td>
  
  @if ( $total_gap == null )
            
      {{ $total_gap }}
       
       

        @elseif( $total_gap >= 0 && $total_gap > 15)

        <div id="global-gap" class="fw-bold badge badge-opacity-danger">
          {{ $total_gap }} %
          <i class="mdi mdi-menu-up"></i>
        </div>

        @else

        <div id="global-gap" class="fw-bold badge badge-opacity-success">
          {{ $total_gap }} %
          <i class="mdi mdi-menu-down"></i>
        </div>
            
        @endif
</td>

</tr>

@endif




     

    

      {{-- <tr>
        <td></td>
        <td></td>
        <td class="text-succes">Total</td>
        <td><label class="badge badge-success">12000</label></td>
      </tr> --}}
      
    </tbody>
  </table>
</div>

<div class="row justify-content-end">
 
  @can('update', $folder)
      
  <div  class="col-md-4 mt-3">
    {{--
    
    <button  type="button"
      data-is-draft="1"  class="save-invoice text-white w-100 btn btn-block btn-{{ 'info' }} fw-bold btn-lg font-weight-medium auth-form-btn">
        {{ 'Enregistrer comme brouillon' }}
    </button>
    
    --}}

   @if ($folder && !$folder->validate_at)


   <button  type="button"
   data-is-draft="1"    class="validate-invoice draft text-white w-100 btn btn-block btn-info fw-bold btn-lg font-weight-medium auth-form-btn">
     {{ 'Enregister comme brouillon' }}
 </button>
       
   <button  type="button"
   data-is-draft="0" class="validate-invoice text-white w-100 btn btn-block btn-{{ $color }} fw-bold btn-lg font-weight-medium auth-form-btn">
   {{ 'Terminer la validation' }}
</button>


   @endif

    <div class="d-none loader d-flex justify-content-center mt-3">

      <div class="inner-loading dot-flashing"></div>
    
  </div>
</div>

  @endcan


</div>


{{-- <div class="row justify-content-end">
 
  @can('update', $folder)
      
  <div  class="col-md-4 mt-3">
    <button  type="button"
      data-is-draft="1"  class="save-invoice text-white w-100 btn btn-block btn-{{ 'info' }} fw-bold btn-lg font-weight-medium auth-form-btn">
        {{ 'Enregistrer comme brouillon' }}
    </button>

    <button  type="button"
      data-is-draft="0"  class="save-invoice text-white w-100 btn btn-block btn-{{ $color }} fw-bold btn-lg font-weight-medium auth-form-btn">
        {{ 'Enregistrer cette facture' }}
    </button>

    <div class="d-none loader d-flex justify-content-center mt-3">

      <div class="inner-loading dot-flashing"></div>
    
  </div>
</div>

  @endcan


</div> --}}






</form>