@extends('layouts.app')


@section('custom_css')
    <link rel="stylesheet" href="{{ asset('zen2/css/typeahead/typeahead.css') }}">

    <style>
    .twitter-typeahead {
            width: 100% !important;
        }

        </style>
    
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des recherches </a>
          </li>
        </ul>
        <div>
          <div class="btn-wrapper">
        
   
        
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
       
          <div class="row">

     
           
          <div class="row">

            <div class="col-md-5 grid-margin stretch-card">
   
              @include('layouts.partials._search_template')
          
          </div>
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste des recherches</h4>
                
                  <div class="table-responsive">
                    <table id="log" class="sortable instances_lines table table-striped" data-url="log" data-type="log">
                      <thead>
                        <tr>
                          <th>
                            Requette
                          </th>

                          
                          <th>
                            Nombres de resultats
                          </th>

                          <th>
                            Utilisateur
                          </th>
                        
                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($searchs as $search)
                        <tr>
                          
                          <td>
                            {{$search->query}}
                          </td>

                     

                          <td>
                          
                            <div  class="fw-bold badge badge-opacity-{{ $search->get_results()["color"] }}">{{ $search->get_results()["text"] }}</div>

                           
                          </td>
 
              

                          <td>

                            @if ($search->user)
                           
                            {{$search->user->employee->full_name()}}
                                
                            @endif

                            
                          </td>
                         {{--  --}}
                          
                          <td>
                            <form>

                              @can('view', $search)

                              <a    id="print_{{$search->code}}" class="search_{{$search->code }} me-3 print" href="{{url('extract/'.$search->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                              @endcan

                              @if ($search && $search->invoice_line && $search->invoice_line->invoice)
                                  
                             
                              
                              @if ($search->invoice_line->invoice->prestationable->fullname == "PHARMACIE")
                                  
                              <a    id="edit_{{$search->code}}" class="search_{{$search->code }} me-3 edit" target="_blank" href="{{ url('product/create?search='.$search->code) }}">Nouveau médicament</a>


                              @else
                                  
                              <a    id="edit_{{$search->code}}" class="search_{{$search->code }} me-3 edit" target="_blank" href="{{ url('service/create?search='.$search->code) }}">Nouvelle prestation</a>
                            
                              @endif



                              @else

                              <a    id="edit_{{$search->code}}" class="search_{{$search->code }} me-3 edit" target="_blank" href="{{ url('product/create?search='.$search->code) }}">Nouveau médicament</a>

                              <a    id="edit_{{$search->code}}" class="search_{{$search->code }} me-3 edit" target="_blank" href="{{ url('service/create?search='.$search->code) }}">Nouvelle prestation</a>

                                  
                              @endif

                             

                             

                              {{-- @can('update', $search)
                              <a    id="edit_{{$search->code}}" class="search_{{$search->code }} me-3 edit" href="{{url('extract/'.$search->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                              @endcan --}}

                             {{--  @can('delete', $search)
                            
                              <a id="delete_{{$search->code}}" class="search_{{$search->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$search->code}}" type="hidden" value="{{$search->code}}">
                              <div id="loader" class="search_{{$search->code }}  d-none d-flex justify-content-center mt-3">
            
                              <div class="inner-loading dot-flashing"></div>
                              
                              </div> 
                               @endcan --}}
                             
                            </form> 
                          </td>

                        </tr>  
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          @if ($searchs->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $searchs ->links('layouts.partials.pagination') }}
               
                
               </div>
             </div>
           </div>
          
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('custom_modal')
@endsection



@section('custom_js')

<script src="{{ asset('zen/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('zen/js/typeahead.js') }}"></script>
<script src="{{ asset('plugins/sorttableJs/sorttable.js') }}"></script>

@endsection