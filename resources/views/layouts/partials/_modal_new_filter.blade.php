 <!-- Modal -->
 <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title text-center" id="modalFilterLabel">{{ 'Affichez les PEC contenants :' }}</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body pt-0">
         <form id="form_filter" class="pt-0" novalidate method="GET" action="{{$slip ? url('slip/'. ($slip->code)) : url('check/sdvs') }}">
           @csrf
           <input id="filter_url" type="hidden" value="{{ ('/api/count-filtered-results') }}">
           <input  id="slip" name="slip" class="form-control" type="hidden" value="{{ ($slip ? $slip->code : 'aaa') }}">
           <input  id="results" name="results" class="form-control" type="hidden" value="{{ Request::get('results') }}">
           <input  id="page" name="page" class="form-control" type="hidden" value="{{ Request::get('results') }}">
           {{-- <input id="resource_code" class="form-control" type="hidden" value="{{ $resource_code }}"
           >
           <input id="resource_type" class="form-control" type="hidden" value="{{ $resource_type }}">
           
           <input id="validator_id" class="form-control" type="hidden"value="{{ session('user')->id }}">
           --}}
           
           <div class="row">
            <div class="col-md-12">
              <div class="form-group fw-bold">
                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="prestations-exist" name="prestations-exist" {{ Request::get('prestations-exist') ? 'checked' : '' }}  type="checkbox" class="filter form-check-input form-control">
                    Des prestations non reconnue
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>

                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="conform-price" name="conform-price" type="checkbox" {{ Request::get('conform-price') ? 'checked' : '' }} class="filter form-check-input form-control">
                    Des prix non conformes
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>

                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="conform-quantity" name="conform-quantity" type="checkbox" {{ Request::get('conform-quantity') ? 'checked' : '' }} class="filter form-check-input form-control">
                    Des Quantités non conformes
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>

                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="should-be-validated" name="should-be-validated" type="checkbox" {{ Request::get('should-be-validated') ? 'checked' : '' }} class="filter form-check-input form-control">
                    Des prestations devrant etre validées par un medecin
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>

                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="conform-pathologies" name="conform-pathologies" type="checkbox" {{ Request::get('conform-pathologies') ? 'checked' : '' }} class="filter form-check-input form-control">
                    Des pathologies non conformes
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>


                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="contract-linked" name="contract-linked" type="checkbox" {{ Request::get('contract-linked') ? 'checked' : '' }} class="filter form-check-input form-control">
                    PEC liées à un contrat
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>

             

                <div class="col-12 border-bottom">
               
                  <label style="font-size: .9rem;" for="min-price" class="form-label">Total Min : <strong class="min-price-value">{{ Request::get('min-price') ?? 0 }}</strong> Fcfa
                    <i class="green-icon mdi mdi-help-circle"></i>
                  </label>
                <input type="range" value="{{ Request::get('min-price') ?? 0 }}" id="min-price" class="form-control form-range prices min-price" min="0" max="200000" step="10000" name="min-price">
                
                <label style="font-size: .9rem;" for="max-price" class="form-label">Total Max : <strong class="max-price-value">{{ Request::get('max-price') ?? '200000' }}</strong> Fcfa
                  <i class="green-icon mdi mdi-help-circle"></i>
                </label>
                <input type="range" id="max-price" class="form-control form-range prices max-price" min="0" max="200000" step="10000" value="{{ Request::get('max-price') ?? '200000' }}" name="max-price">
                
                
                </div>

                <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="conform-pathology" name="conform-pathology" {{ Request::get('conform-pathology') ? 'checked' : '' }} type="checkbox" class="filter form-control form-check-input">
                    Pathologies cohérentes
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div>

              {{--   <div class="form-check border-bottom">
                  <label class="form-check-label my-4" style="font-size: .9rem;">
                    <input id="lock-filter" name="lock-filter" {{ Request::get('lock-filter') ? 'checked' : '' }} type="checkbox" class="filter form-control form-check-input">
                    Prendre en consideration les filtres lors de la validation
                  <i class="input-helper"></i>
                <i class="green-icon mdi mdi-help-circle"></i>
              </label>
                </div> --}}
              
              </div>
            </div>
           
          </div>


         </form>


        
       </div>
       <div class="modal-footer">

        <div class="menu_button">
          <button id="filter-button" type="button" class="text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
         <span id="filter-button-text">FIltrer</span>
         <div id="filter-loader" class="d-none d-flex justify-content-start">

          <div class="inner-loading dot-flashing"></div>
 
        </div>
         </button>
       </div>

         <div class="menu_button">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
         </div> 

      
     </div>
   </div>
 </div>
 </div>