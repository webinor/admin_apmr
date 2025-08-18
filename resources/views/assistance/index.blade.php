@extends('layouts.app')



@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des APMR</a>
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
            {{--@if (Auth::user()->can('create', App\Models\Sales\assistance::class))--}}
    <!-- The current user can update the post... -->
    <a href="#" class="d-none btn btn-success text-white me-0" data-bs-toggle="modal"
    data-bs-target="#balance-modal"><i class="icon-check"></i>Solde prestataire</a>


    <a href="{{url('assistance/create')}}" class="d-none btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau servant CAS</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           
            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Imprimer la liste</a>--}}
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
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
                    <form id="form-results" class="row" action="" method="get">
            
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

                  
                  {{--<p class="card-description">
                    Add class <code>.table-striped</code>
                  </p>--}}
                  <div class="table-responsive">
                    <table id="assistance" class="instances_lines table table-striped" data-url="assistance" data-type="assistance">
                      <thead>
                        <tr>
                          <th></th>
                          {{-- <th>
                            Logo
                          </th> --}}
                          <th>
                            compagnie
                          </th>
                          
                        {{--   <th>
                           Activite(s)
                          </th> --}}
                          
                          <th>
                            numeor de vol
                          </th>
                          <th>
                            respnsable d'escale
                          </th>
                          <th>
                            personnes assistee
                          </th>

                          <th>
                            Date
                          </th>
                         {{--  <th>
                            Ajouté le
                          </th> --}}
                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>

                        @php

                        $start_index  = $assistances->count() > 0 ? ($assistances->currentPage()-1)*$assistances->perPage() + 1 : 1;
           
                        
                 
                        
                        @endphp

                        @foreach ($assistances as $index => $assistance)

                        @php
                             $assistance_index = $start_index  + $index;
                        @endphp
                        <tr>
                          <td>
                            {{ $assistance_index }}
                          </td>
                         {{-- -}} <td>
                            <div class="preview-thumbnail">
                            @php
                                if ($assistance->logo) {
                                  $logo = asset("storage/assistance_images/".$assistance->logo->path);
                                } else {
                                  $logo = asset("storage/assistance_images/default.png");
                                }
                               @endphp
                            
                                <img data-src="{{$logo}}" alt="image" class="lazy img-sm profile-pic">
                            {{--<span  class=" logged-out user-login-status user-login-status-{->user->id}}">●</span>--}
 
                          </div>
                          
                          </td>{{----}}
                          <td>
                            {{$assistance->fullName()}}
                          </td>
 
                         {{--<td>
                            {{$assistance->activity_area != null ? $assistance->activity_area->name : ''}}
                          </td>--}}

                           {{--  <td>
                          $assistance->activities 
                          </td>--}}

                       

                          <td>
                            {{$assistance->email}}

                          </td>

                          <td>
                            {{$assistance->main_phone_number}}
                          </td>
                          
                          <td>

                            @if ($assistance->user)
                           
                            {{$assistance->user->employee->full_name()}}
                                
                            @endif

                            
                          </td>
                         {{--  <td>
                            {{$assistance->created_at}}
                            
                          </td> --}}

                          <td>

                          </td>
                          
                          <td>
                            <form>

                              
                                  
                              
                              <a    id="print_{{$assistance->code}}" class="assistance_{{$assistance->code }} me-3 print" href="{{url('assistance/'.$assistance->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                             

                             
                              <a    id="edit_{{$assistance->code}}" class="assistance_{{$assistance->code }} me-3 edit" href="{{url('assistance/'.$assistance->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                              

                              @can('delete', $assistance)
                            
                              <a id="delete_{{$assistance->code}}" class="assistance_{{$assistance->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$assistance->code}}" type="hidden" value="{{$assistance->code}}">
                              <div id="loader" class="assistance_{{$assistance->code }}  d-none d-flex justify-content-center mt-3">
            
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

          @if ($assistances->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $assistances ->links('layouts.partials.pagination') }}
               
                
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

@endsection