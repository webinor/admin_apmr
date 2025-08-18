 
<!-- Modal -->
<div class="modal fade" id="model_update" tabindex="-1" aria-labelledby="model_updateLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="model_updateLabel">Modifier la reference</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_updater" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
          @csrf
          <input id="modal_url"  type="hidden" value="{{ '$modal_url' }}" >
          
        
       {{--    <input id="resource_code" class="form-control" type="hidden" value="{{ $resource_code }}" >
          <input id="resource_type" class="form-control" type="hidden" value="{{ $resource_type }}" >
          <input id="validator_id" class="form-control" type="hidden" value="{{session('user')->id}}" > --}}


        </form>
      </div>
      <div class="modal-footer">
         <div class="menu_button" >
          <button id="cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div> {{-- --}}

        <div class="menu_button">
          <button id="update" data-resource-code="{{'$resource_code'}}" type="button"  class=" text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
           Enregistrer
          </button>
        </div> 
        
        <div id="loader" class="d-none d-flex justify-content-start">
          
            <div class="inner-loading dot-flashing"></div>
          
        </div>
      </div>
    </div>
  </div>
</div>