 
<!-- Modal -->
<div class="modal fade" id="supplier_invoice_modal" tabindex="-1" aria-labelledby="supplier_invoice_modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supplier_invoice_modalLabel">Indiquez le montant à regler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_mention" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
          @csrf
          <input id="token" class="form-control" type="hidden" value="{{session('user')->code}}" >
         
          <div class="form-group">
            <label for="amount" class=" col-form-label">Montant ( XAF )</label>
            <input {{ $readonly }} id="amount" type="number" class="form-control" value="" placeholder="Ex : 300000"/>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div>
 
          
        </form>
      </div>
      <div class="modal-footer">
         <div class="menu_button" >
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div> {{-- --}}

        <div class="menu_button">
          <button id="pay" data-resource-code="{{$invoice->resource->code}}" type="button"  class=" text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
           Enregistrer le paiement
          </button>
        </div> 
        
        <div id="loader" class="d-none d-flex justify-content-start">
          
            <div class="inner-loading dot-flashing"></div>
          
        </div>
      </div>
    </div>
  </div>
</div>