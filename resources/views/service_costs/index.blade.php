@extends('layouts.app')



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
            {{--@if (Auth::user()->can('create', App\Models\Sales\service_cost::class))--}}
    <!-- The current user can update the post... -->
  

    <a href="{{url('service_cost/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouvelle prestation</a>

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
                  <h4 class="card-title">Liste des prestations</h4>
                  {{--<p class="card-description">
                    Add class <code>.table-striped</code>
                  </p>--}}
                  <div class="table-responsive">
                    <table id="service_cost" class="instances_lines table table-striped" data-url="service_cost" data-type="service_cost">
                      <thead>
                        <tr>
                          <th>
                            Nom de la prestation
                          </th>

                       
                          <th>
                            Type de prestataire
                          </th>

                         

                          <th>
                            Couverture
                          </th>

                          <th>
                            Lettre clé 
                          </th>

                          <th>
                            valeur clé 
                          </th>

                          <th>Cote</th>
                          <th>
                            prix
                          </th>

                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($service_costs as $service_cost)
                        <tr>
                          
                          <td>
                            {{$service_cost->get_service_name()}}
                          </td>

                          <td>
                            {{$service_cost->get_provider_type()}}
                          </td>

                          <td>
                            {{$service_cost->get_coverage()}}

                          </td>


                          <td>{{ $service_cost->get_key_letter() }}</td>
                          <td>{{ $service_cost->get_value_letter() }}</td>

                          <td>{{ $service_cost->get_quote() }}</td>

                          <td>
                            {{$service_cost->get_price()}}
                          </td>

                          <td>
                            <form>

                              
                                  
                              
                              <a    id="print_{{$service_cost->code}}" class="service_cost_{{$service_cost->code }} me-3 print" href="{{url('service_cost/'.$service_cost->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                             

                             
                              <a    id="edit_{{$service_cost->code}}" class="service_cost_{{$service_cost->code }} me-3 edit" href="{{url('service_cost/'.$service_cost->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                              

                              @can('delete', $service_cost)
                            
                              <a id="delete_{{$service_cost->code}}" class="service_cost_{{$service_cost->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$service_cost->code}}" type="hidden" value="{{$service_cost->code}}">
                              <div id="loader" class="service_cost_{{$service_cost->code }}  d-none d-flex justify-content-center mt-3">
            
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

          @if ($service_costs->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $service_costs ->links('layouts.partials.pagination') }}
               
                
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