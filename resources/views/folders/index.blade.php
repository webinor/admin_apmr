@extends('layouts.app')



@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des bordereauxs</a>
          </li>
        </ul>
        <div>
          <div class="btn-wrapper">
        
    <a href="{{url('extract/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau borderau</a>
   
   
    <a href="#{{-- url('extract/schedule') --}}" class="btn btn-success text-white me-0" ><i class="icon-clock"></i>Planifier une extraction</a>

        
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
       
           
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste des bordereaux</h4>
                
                  <div class="table-responsive">
                    <table id="slip" class="instances_lines table table-striped" data-url="slip" data-type="slip">
                      <thead>
                        <tr>
                          

                          <th>
                            Numero borderau
                          </th>

                          <th>
                            Ajouté par
                          </th>

                          <th>Statut</th>
                          
                          <th>
                            Ajouté le
                          </th>
                          
                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($slips as $slip)
                        <tr>
                          
                          <td>
                            {{$slip->identification}}
                          </td>


                          <td>

                            @if ($slip->user)
                           
                            {{$slip->user->employee->full_name()}}
                                
                            @endif

                            
                          </td>
                        

                          <td>
                          
                            {{-- <div  class="fw-bold badge badge-opacity-{{ $slip->get_status()["color"] }}">{{ $slip->get_status()["text"] }}</div>
 --}}
                           
                          </td>
 
              
                          <td>
                            {{$slip->created_at}}
                          </td> 

                          
                         {{--  --}}
                          
                          <td>
                            <form>

                             {{--  @can('view', $slip)@endcan --}}
                              
                              <a    id="print_{{$slip->code}}" class="slip_{{$slip->code }} me-3 print" href="{{url('slip/'.$slip->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                              

                              {{-- @can('update', $slip)
                              <a    id="edit_{{$slip->code}}" class="slip_{{$slip->code }} me-3 edit" href="{{url('extract/'.$slip->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                              @endcan --}}

                             {{--  @can('delete', $slip)
                            
                              <a id="delete_{{$slip->code}}" class="slip_{{$slip->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$slip->code}}" type="hidden" value="{{$slip->code}}">
                              <div id="loader" class="slip_{{$slip->code }}  d-none d-flex justify-content-center mt-3">
            
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

          @if ($slips->count()>0)
             
          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $slips ->links('layouts.partials.pagination') }}
               
                
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