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


      <input  id="total_file" type="hidden"
      value="{{ $folder->get_total_file() }}">
      <input  id="rate" type="hidden"
      value="{{ $folder->get_rate() }}">




<div class="table-responsive">
  <table id="{{ $folder ? $folder->code : '' }}" class="table instances_lines table-hove invoice-table" data-url="{{ url('api/update-invoice') }}" >
    <thead>
      <tr>
        <th>#</th>
        <th>Designation</th>
        <th>Quantité</th>
        <th>Prix unitaire</th>
        <th>Prix total ( XAF )</th>
        <th>Prestation</th>
        
        @can('update', $folder)
            
        <th>Action</th>

        @endcan
      </tr>
    </thead>
    <tbody>
      @php
          $index=1;
          $total = 0;
      @endphp

      @if ($folder)
          
     
     
      @foreach ($folder->invoices  as $invoice)



      @foreach ($invoice->invoice_lines as $invoice_line)

      @if (!$invoice_line->invoice->prestationable)
          
      @endif

      @php
          $total += $invoice_line->get_total();
      @endphp
          
      <tr id="{{ $invoice_line->code }}" data-invoice="{{ $invoice->code }}" data-invoice-line="{{ $invoice_line->code }}"
        data-folder="{{ $folder ? $folder->code : '' }}"
        data-description="{{ $invoice_line->description }}"
        data-quantity="{{ $invoice_line->get_quantity() }}"
        data-price="{{ $invoice_line->get_price() }}"
        data-total="{{ $invoice_line->get_total()  }}"
        data-provider-category="{{ $invoice_line->invoice->folder->slip->provider->provider_category->get_name()  }}"
        data-prestation-type="{{ $invoice_line->invoice->prestationable ? $invoice_line->invoice->prestationable->get_prestationable_type() : "inconnu"}}"
        data-prestation-code="{{ $invoice->prestationable ? $invoice->prestationable->get_code() : "inconnu"}}"
      
        data-previous-service="{{ $invoice_line->invoice->prestationable ? $invoice_line->invoice->prestationable->get_fullname() : 'inconnu' }}"
        data-service="{{ $invoice_line->invoice->prestationable ? $invoice_line->invoice->prestationable->get_fullname() : 'inconnu' }}"
        >
        <td>{{ $index++ }}</td>
        <td >{{ $invoice_line->description }}</td>
        <td >{{ $invoice_line->get_quantity() }}</td>
        <td >{{ $invoice_line->get_price() }}</td>
        <td class="partial-total">{{ $invoice_line->get_total()  }}</td>
        <td >{{ $invoice_line->invoice->prestationable ? $invoice_line->invoice->prestationable->get_fullname() : "indiquez le type de prestation" }}</td>
       
        @can('update', $folder)

        <td>

          @if (!$invoice->remote_inserted){{-- on donne la possibilite de modifier ou supprimer si on a pas encore inserer dans l'ERP --}}
              
          
          <a  class="api-{{ $invoice->get_reference() }} edit-item me-3 edit blink "
              href="#"><i
                class="menu-icon mdi mdi-table-edit"></i>
          </a>

          {{-- 

           <a  class="delete-item delete blink" href="#"><i
              class="menu-icon mdi mdi-close-circle"></i></a> 
          
          --}}

          <a data-bs-toggle="modal" data-bs-target="#delete-modal" data-model-to-delete="{{ $invoice_line->description }}" data-invoice-line="{{ $invoice_line->code }}" data-delete-link="{{ ('/api/invoice_line/'.($invoice_line->code)) }}" class="api-{{ $invoice->get_reference() }} delete blink" href="#"><i
            class="menu-icon mdi mdi-close-circle"></i></a> 

              
          <input id="input" type="hidden"
            value="">
          <div class="blink loader d-none d-flex justify-content-center mt-3">

          <div class="inner-loading dot-flashing"></div>
          </div>

@endif

        </td>
            
        @endcan

      </tr>
      
@endforeach
@endforeach

<tr id="line-total">
<td></td>
<td>Total </td>
<td></td>
<td></td>
<td id="all-total" class="{{ $folder->get_total_status() }} fw-bold">{{ $total }}</td>
<td></td>
<td></td>
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
      
  @if (!$folder->save_at)
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
@endif

  @endcan


</div>






</form>