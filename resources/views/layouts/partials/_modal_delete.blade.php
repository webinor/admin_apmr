 
<!-- Modal -->


<div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">{{ $header_title }} ( <span id="model-to-delete" ></span> )</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="delete_form" class="pt-0 " novalidate method="post" action="{{url('company/create')}}">
          @csrf
          <input readonly id="modal_delete_url"  type="hidden" value="" >
          <input readonly id="invoice_line"  type="hidden" value="" >

         
          <div>
            <input  id="model"  type="hidden" value="" >
            @include('layouts.partials._feedback')

          </div>
          
          <div>
            <input id="validator_id" class="form-control" type="hidden" value="{{session('user')->code}}" >
            @include('layouts.partials._feedback')
          </div>
         
          
        </form>
      </div>
      <div class="modal-footer">
        <div  class="d-none loader">
          
          <div class="inner-loading dot-flashing"></div>
        
        </div>
          <div class="menu_button" >
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>

        <div class="menu_button">
          <button id="confirm_delete"  type="button"  class=" text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
           Oui
          </button>
        </div> 
         
        
       
      </div>
    </div>
  </div>
</div>