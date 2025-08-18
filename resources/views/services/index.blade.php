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
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des prestations</a>
          </li>
          {{--<li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
          </li>
          <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">More</a>
          </li>--}}
        </ul>
        <div>
          <div class="btn-wrapper">
            {{--@if (Auth::user()->can('create', App\Models\Sales\service::class))--}}
    <!-- The current user can update the post... -->
  

    <a href="{{url('service/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouvelle prestation</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           
            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Imprimer la liste</a>--}}
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 

          <div class="col-md-5 grid-margin stretch-card">
   
            @include('layouts.partials._search_template')
        
        </div>
          {{--<div class="row">
            <div class="col-sm-12">
              <div class="statistics-details d-flex align-items-center justify-content-between">
                <div>
                  <input type="hidden" id="posts_count_hidden" name="" value="{{$posts_count ?? "0"}}">
                  <p class="statistics-title">Chiffre d'affaire</p>
                  <h3 id="posts_count" class="rate-percentage">{{$posts_count ?? '500000'}}</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                </div>
                <div>
                  <p class="statistics-title">Nouveaux clients</p>
                  <h3 class="rate-percentage">7,682</h3>
                  <p id="user" class="text-danger d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                </div>
                <div>
                  <p class="statistics-title">Nouvelles Sessions</p>
                  <h3 class="rate-percentage">68</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68</span></p>
                </div>
                {{--<div class="d-none d-md-block">
                  <p class="statistics-title">Avg. Time on Site</p>
                  <h3 class="rate-percentage">2m:35s</h3>
                  <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                </div>
                <div class="d-none d-md-block">
                  <p class="statistics-title">New Sessions</p>
                  <h3 class="rate-percentage">68.8</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                </div>
                <div class="d-none d-md-block">
                  <p class="statistics-title">Avg. Time on Site</p>
                  <h3 class="rate-percentage">2m:35s</h3>
                  <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                </div>
              </div>
            </div>
          </div> --}}
           
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class=" mb-4 ">
                    <form id="form-results " class="row form-results" action="" method="get">
            
                      <div class="col-1">
                        <label for="results">Afficher</label>
                      </div>
                      <div class="col-2">
              
                        <select name="results" class="form-control" id="results">
                         {{--  <option value="5" {{ Request::get("results") == 5 ? 'selected' : ''}} >5</option> --}}
                          <option value="10" {{ Request::get("results") == 10 ? 'selected' : ''}} >10</option>
                          <option value="25" {{ Request::get("results") == 25 ? 'selected' : ''}} >25</option>
                          <option value="50" {{ Request::get("results") == 50 ? 'selected' : ''}} >50</option>
                          <option value="100" {{ Request::get("results") == 100 ? 'selected' : ''}} >100</option>
                        </select>
                      </div>
            
                    </form>
                   
                   </div>
                  {{-- <h4 class="card-title">Liste des prestations</h4><p class="card-description">
                    Add class <code>.table-striped</code>
                  </p>--}}
                  <div class="table-responsive">
                    <table id="service" class="instances_lines table table-striped" data-url="service" data-type="service">
                      <thead>
                        <tr>
                          <th>
                            Nom de la prestation
                          </th>

                          <th>
                            Type de prestation
                          </th>

                  
                          <th>
                            Couverture
                          </th>

                          <th>
                            Lettre clé 
                          </th>

                          <th>Cote</th>
                         

                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($services as $service)
                        <tr>
                          
                          <td>
                            {{$service->get_service_name()}}
                          </td>

                          <td>
                            {{$service->get_service_type()}}

                          </td>

                          <td>
                            {{$service->get_coverage()}}

                          </td>


                          <td>{{ $service->get_key_letter() }}</td>

                          <td>{{ $service->get_quote() }}</td>

                      

                          <td>
                            <form>

                            
                              <a    id="print_{{$service->code}}" class="service_{{$service->code }} me-3 print" href="{{url('service/'.$service->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                             

                             
                              <a    id="edit_{{$service->code}}" class="service_{{$service->code }} me-3 edit" href="{{url('service/'.$service->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                              

                              @can('delete', $service) 
                            
                              <a   data-bs-toggle="modal"
                              data-bs-target="#delete-modal" data-model-to-delete="{{ $service->name }}" data-delete-link="{{ ('/api/service/'.($service->code)) }}" class="delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                            
                              {{-- <a id="delete_{{$service->code}}" class="service_{{$service->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                             --}}
                              
                              <input id="input_{{$service->code}}" type="hidden" value="{{$service->code}}">
                              <div id="loader" class="service_{{$service->code }}  d-none d-flex justify-content-center mt-3">
            
                              <div class="inner-loading dot-flashing"></div>
                              
                              </div>
                               @endcan
                              
                             
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

          @if ($services->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $services ->links('layouts.partials.pagination') }}
               
                
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

@include('layouts.partials._modal_delete') 
        
@endsection



@section('custom_js')

<script src="{{ asset('zen/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('zen/js/typeahead.js') }}"></script>
<script src="{{ asset('plugins/sorttableJs/sorttable.js') }}"></script>


@endsection