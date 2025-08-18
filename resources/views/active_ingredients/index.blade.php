@extends('layouts.app')



@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des dci</a>
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
            {{--@if (Auth::user()->can('create', App\Models\Sales\Customer::class))--}}
    <!-- The current user can update the post... -->
    <a href="{{url('active-ingredient/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau principe actif</a>

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
                  <h4 class="card-title">Liste des dci</h4>
                  {{--<p class="card-description">
                    Add class <code>.table-striped</code>
                  </p>--}}
                  <div class="table-responsive">
                    <table class="instances_lines table table-striped">
                      <thead>
                        <tr>
                         {{-- <th>
                            Logo
                          </th>--}}
                          <th>
                            Nom
                          </th>

                       
                          
                       
                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($active_ingredients as $active_ingredient)
                        <tr>
                          
                         {{-- <td>
                            {{$active_ingredient->image_url}}
                          </td>--}}
                          <td>
                            {{$active_ingredient->get_dci()}}
                          </td>

                       

                         {{--  <td>

                            @if ($active_ingredient->user->employee)
                           
                            {{$active_ingredient->user->employee->first_name}}   {{$active_ingredient->user->employee->last_name}}
                                
                            @endif

                            
                          </td> 
                          <td>
                            {{$active_ingredient->created_at}}
                            
                          </td>

                          --}}
                          
                          <td>
                            <form>

                              <a id="print_{{$active_ingredient->code}}" class="me-3 print" href="{{url('active_ingredient/'.$active_ingredient->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>


                              <a id="edit_{{$active_ingredient->code}}" class="me-3 edit" href="{{url('active_ingredient/'.$active_ingredient->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>

                             {{-- --}} @can('delete', $active_ingredient)

                              <a id="delete_{{$active_ingredient->code}}" class="delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$active_ingredient->code}}" type="hidden" value="{{$active_ingredient->code}}">
                              <div id="loader" class="d-none d-flex justify-content-center mt-3">
            
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

          @if ($active_ingredients->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $active_ingredients ->links('layouts.partials.pagination') }}
               
                
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



@section('custom_js')

@endsection