 
<!-- Modal -->
<div class="modal fade" id="balance-modal" tabindex="-1" aria-labelledby="modalBalanceLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBalanceLabel">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">



        <form id="form_modal" class="pt-3 d-none" novalidate method="post" action="{{url('company/create')}}">
          @csrf
          <input id="modal_url"  type="hidden" value="{{ $modal_url }}" >
         {{-- <input id="resource_code" class="form-control" type="hidden" value="{{ $resource_code }}" >
          <input id="resource_type" class="form-control" type="hidden" value="{{ $resource_type }}" >  --}}
          <input id="validator_id" class="form-control" type="hidden" value="{{session('user')->id}}" >
        </form>

            
        <div class="row ">
          <div class="col-lg-12 grid-margin stretch-card  justify-content-center">
            <div class="table-responsive">
              <table id="supplier" class="text-center instances_lines table table-striped" data-url="supplier" data-type="supplier">
                <thead>
                  <tr>
                    <th>
                      Logo
                    </th>
                    <th>
                      Raison sociale
                    </th>
                    <th>
                      Solde {{-- <strong>( {{ currency()->getUserCurrency() }} )</strong> --}}
                    </th>

                  {{--  <th>Action</th> --}}
                    
                  </tr>
                </thead>
                <tbody>
                  @foreach ($due_suppliers as $supplier)
                  @php
                      
                      $total+=$supplier->getBalance();
                  @endphp
                  <tr>
                    <td>
                      <div class="preview-thumbnail">
                      @php
                          if ($supplier->logo) {
                            $logo = asset("storage/supplier_images/".$supplier->logo->path);
                          } else {
                            $logo = asset("storage/supplier_images/default.png");
                          }
                         @endphp
                      
                          <img data-src="{{$logo}}" alt="image" class="lazy img-sm profile-pic">
                      {{--<span  class=" logged-out user-login-status user-login-status-{->user->id}}">●</span>--}}

                    </div>
                    
                    </td>{{----}}
                    <td>
                      {{$supplier->social_reason}}
                    </td>

                    <td>
                      {{ $supplier->getBalance() > 0 ? currency($supplier->getBalance() , currency()->getUserCurrency() ) : 0 }} 
                    </td>
                    
                    <td>
                      {{-- <form>

                        @can('view', $supplier)
                            
                        
                        <a    id="print_{{$supplier->code}}" class="supplier_{{$supplier->code }} me-3 print" href="{{url('supplier/'.$supplier->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                        @endcan

                        @can('update', $supplier)
                        <a    id="edit_{{$supplier->code}}" class="supplier_{{$supplier->code }} me-3 edit" href="{{url('supplier/'.$supplier->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                        @endcan
                       
                      </form>  --}}
                    </td>

                  </tr>  
                  @endforeach
                  <tr class="fw-bold">
                    <td>Total</td>
                    <td>*</td>
                    <td>{{ $total > 0 ? currency($total , currency()->getUserCurrency()) : 0 }}</td>
                    {{-- --}}<td></td> 

                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
         <div class="menu_button" >
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div> {{-- 

        <div class="menu_button">
          <button id="validate" data-resource="{{$resource}}" data-partner="{{$partner}}" type="button"  class=" text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
           Oui
          </button>
        </div> --}}
        
        <div id="loader" class="d-none d-flex justify-content-start">
          
            <div class="inner-loading dot-flashing"></div>
          
        </div>
      </div>
    </div>
  </div>
</div>