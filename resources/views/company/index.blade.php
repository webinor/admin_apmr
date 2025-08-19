@extends('layouts.app')



@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des compagnies</a>
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
            {{--@if (Auth::user()->can('create', App\Models\Sales\company::class))--}}
    <!-- The current user can update the post... -->
    <a href="#" class="d-none btn btn-success text-white me-0" data-bs-toggle="modal"
    data-bs-target="#balance-modal"><i class="icon-check"></i>Solde compagnie</a>


    <a href="{{url('company/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouvelle compagnie</a>

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
                    <table id="company" class="instances_lines table table-striped" data-url="company" data-type="company">
                      <thead>
                        <tr>
                          <th></th>
                           <th>
                            Logo
                          </th> {{----}}
                          <th>
                            Nom
                          </th>
                          
                        {{--   <th>
                           Activite(s)
                          </th> --}}
                          <th>
                            Prefixe
                          </th>
                         <th>
                            Abonnement mensuel
                          </th>
                         {{--   <th>
                            Contact
                          </th> --}}
                          <th>
                            Ville
                          </th>
                         {{--  <th>
                            Ajouté le
                          </th> --}}
                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>

                        @php

                        $start_index  = $compagnies->count() > 0 ? ($compagnies->currentPage()-1)*$compagnies->perPage() + 1 : 1;
           
                        
                 
                        
                        @endphp

                        @foreach ($compagnies as $index => $company)

                        @php
                             $slip_index = $start_index  + $index;
                        @endphp
                        <tr>
                          <td>
                            {{ $slip_index }}
                          </td>
                         <td>
                            <div class="preview-thumbnail">
                            {{-- @php
                                if ($company->image_path) {
                                  $logo = asset("storage/company_images/".$company->image_path);
                                } else {
                                  $logo = asset("storage/company_images/default.png");
                                }
                               @endphp --}}

@if($company->image_path && Storage::disk('public')->exists('company_images/' . $company->image_path))
<img class="lazy img-sm profile-pic" data-src="{{ asset('storage/company_images/' . $company->image_path) }}" alt="Logo de la compagnie">
@else
<img class="lazy img-sm profile-pic" data-src="{{ asset('images/default.png') }}" alt="Image par défaut">
@endif
                            
 
                          </div>
                          
                          </td>
                          <td>
                            {{$company->name}}
                          </td>
 
                         {{--<td>
                            {{$company->activity_area != null ? $company->activity_area->name : ''}}
                          </td>--}}

                           {{--  <td>
                          $company->activities 
                          </td>--}}

                          <td>
                            {{$company->prefix}}
                          </td>

                          <td>
                            {{$company->mensual_fee}}

                          </td>

                        {{--   <td>
                            {{$company->main_phone_number}}
                          </td> 
                          
                          <td>

                            @if ($company->user)
                           
                            {{$company->user->employee->full_name()}}
                                
                            @endif

                            
                          </td>
                          --}}
                          
                          <td>
                            {{ $company ->city->name }}
                          </td>
                         {{--  <td>
                            {{$company->created_at}}
                            
                          </td> --}}
                          
                          <td>
                            <form>

                              
                                  
                              
                              <a    id="print_{{$company->code}}" class="company_{{$company->code }} me-3 print" href="{{url('company/'.$company->code.'/edit')}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                             

                            {{--  
                              <a    id="edit_{{$company->code}}" class="company_{{$company->code }} me-3 edit" href="{{url('company/'.$company->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                               --}}

                              @can('delete', $company)
                            
                              <a id="delete_{{$company->code}}" class="company_{{$company->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$company->code}}" type="hidden" value="{{$company->code}}">
                              <div id="loader" class="company_{{$company->code }}  d-none d-flex justify-content-center mt-3">
            
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

          @if ($compagnies->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $compagnies ->links('layouts.partials.pagination') }}
               
                
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