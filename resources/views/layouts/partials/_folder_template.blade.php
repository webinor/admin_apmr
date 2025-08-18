<div class="row">

<div class="col-md-5 grid-margin stretch-card">
   
    @include('layouts.partials._search_template')

</div>
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
       <div class="row mb-4 ">

        <div class="col-3">

       
        <form id="form-results" class="row form-results" action="" method="get">

         <div class="row col-6">

          <div class="col-4">
            <label for="results">Afficher</label>
          </div>
          <div class="col-6">
  
            <select name="results" class="form-control" id="results">
              {{-- <option value="10" {{ Request::get("results") == 5 ? 'selected' : ''}} >5</option> --}}
              <option value="10" {{ Request::get("results") == 10 ? 'selected' : ''}} >10</option>
              <option value="25" {{ Request::get("results") == 25 ? 'selected' : ''}} >25</option>
              <option value="50" {{ Request::get("results") == 50 ? 'selected' : ''}} >50</option>
              <option value="100" {{ Request::get("results") == 100 ? 'selected' : ''}} >100</option>
            </select>
          </div>

         </div>


        {{--   <div class="row col-6 d-flex flex-column align-items-center justify-content-center" id="sort">

            <div>
           <span class="option2">
            <button id="filter_search" style="border:1px solid rgb(117, 117, 117) !important;" data-bs-toggle="dropdown" aria-expanded="false" data-bs-target="#seach_modal2" class="benchmarck dropdown-toggle py-1 bg-white text-body btn btn-primary border">
              <span class="fa-solid fa-sort" aria-hidden="true"></span> Trier par :
            </button>
            <ul class="dropdown-menu" style="">
              <li><a class="dropdown-item sorter" data-sort_by="date" data-val="Desc" href="#">Plus recents</a></li>
              <li><a class="dropdown-item sorter" data-sort_by="date" data-val="Asc" href="#">Plus Anciens</a></li>
              <li><a class="dropdown-item sorter" data-sort_by="price" data-val="Desc" href="#">Price ( Max -&gt; Min )</a></li>
              <li><a class="dropdown-item sorter" data-sort_by="price" data-val="Asc" href="#">Price ( Min -&gt; Max )</a></li>
              <li><a class="dropdown-item sorter" data-sort_by="priority" data-val="Desc" href="#">Urgence ( Max -&gt; Min )</a></li>
              <li><a class="dropdown-item sorter" data-sort_by="priority" data-val="Asc" href="#">Urgence ( Min -&gt; Max )</a></li>
            </ul>
           </span>
            </div>
      
          </div> --}}

        </form>
       
      </div>

      <div class="col-9 d-flex justify-content-between align-items-center fw-bold">
        <h6 class="card-titl fw-bold">bodereau # : {{ $slip->identification }}</h6>
       <h6 class="card-titl fw-bold">Montant total : {{ $slip->get_amount() }} Fcfa</h6>
       <h6 class="card-titl fw-bold">Prestataire : {{ $slip->get_provider() }}</h6>
       
      </div>

       </div>

    
       
        {{-- <h4 class="card-title">Prestataire :  {{$folders[0]->provider->name}}</h4> --}}
      
        <div class="table-responsive">
          <table id="folder" class="sortable instances_lines table" data-url="folder" data-type="folder">
            <thead>
              <tr>

                <th>

                </th>

                <th>
                  Index
                </th>
                <th>
                  Numero dossier
                </th>

                <th>
                  Nom du fichier
                </th>
                
          

                <th>Statut</th>

                <th>Montant total</th>
                
                <th>
                  Ajouté le
                </th>

                <th>
                  validateur
                </th>

                @if (session('user')->isAdministrator())
                <th>
                  Ajouté par
                </th>  
                @endif


               <th>Action</th>
                
              </tr>
            </thead>
            <tbody>
              @php

              $start_index  = $folders->count()>0 ? ($folders->currentPage()-1)*$folders->perPage() + 1 : 1;

                  $folder_index = $start_index;// + $index;

              @endphp

@foreach ($folders as $index => $folder)

                @php

                    $condition = ["extractor"=>[
                      "1"=>($folder -> get_save_at() == null && $folder -> has_items() == true) , "2" => ($folder -> get_save_at() != null && $folder -> has_items() == true) , "3" => $folder -> has_items() == false
                    ],
                  "validator"=>[
                      // old "1"=>($folder -> get_save_at() != null && $folder -> get_validate_at() == null && $folder -> has_items() == true) , "2" => ($folder -> get_save_at() != null && $folder -> get_validate_at() != null && $folder -> has_items() == true) , "3" => $folder -> has_items() == false
                      "1"=>($folder -> is_conform() && $folder -> get_validate_at() == null && $folder -> has_items() == true),"2"=>$folder -> get_save_at() != null && $folder -> get_validate_at() != null && $folder -> has_items() == true , "3" => (!$folder -> is_conform() && $folder -> get_validate_at() == null && $folder -> has_items() == true) , "4" => $folder -> has_items() == false,
                      "5"=>true
                    ]
                  ];
                
                @endphp

              @if ($condition[session('user')->employee->role->role_name][$status] )
                  
              
              {{-- @if ( !isset($status) || $status == $folder->status)--}}
                  
             
              @php
                  
                 // $folder_index = $start_index + $index;

              @endphp
              {{-- <tr class="{{ $folder->status == 1 ? "success" : "reject" }} fw-bold"> --}}
              <tr class="{{ $folder->isOpenClass() }} fw-bold">

                
                <td>

                  @if ($folder->isOpen())

                  <a    class="folder_{{$folder->code }} me-3 print" href="#" ><i class="menu-icon mdi mdi-email-open"></i></a>
                      
                  @else
                      
                  <a    class="folder_{{$folder->code }} me-3 print" href="#" ><i class="menu-icon mdi mdi-email"></i></a>
                  
                  @endif


                </td>

                <td>
                  {{ $folder_index++ }}
                </td>
                
                <td>
                  {{$folder->identification}}
                </td>

                <td>
                  {{$folder->doc_name}}
                </td>

                <td>
                
                  <div  class="fw-bold badge badge-opacity-{{ $folder->get_status()["color"] }}">{{ $folder->get_status()["text"] }}</div>

                 
                </td>

                <td>
                  {{ $folder->get_amount() }} FCFA
                </td>

    
                <td>
                  {{$folder->created_at}}
                  
                </td> 

                <td>
                  {{ $folder->validator ? $folder->validator->user->employee->full_name() : '' }}
                </td>


                @if (session('user')->isAdministrator())
                @if ($folder->user)
                 
                {{$folder->user->employee->full_name()}}
                    
                @endif
                @endif
                <td>

                

                  
                {{--   </td>
              
                
                <td>--}}
                  <form>

                    @can('view', $folder)
 
                    <a    id="print_{{$folder->code}}" class="folder_{{$folder->code }} me-3 print" href="{{url('extract/'.$folder->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>
                   
                    @endcan

                    {{-- @can('update', $folder)
                    <a    id="edit_{{$folder->code}}" class="folder_{{$folder->code }} me-3 edit" href="{{url('extract/'.$folder->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                    @endcan --}}

                   @can('delete', $folder)      {{----}}
                  
                   <a   data-bs-toggle="modal"
                   data-bs-target="#delete-modal" data-model-to-delete="{{ $folder->identification }}" data-delete-link="{{ ('/api/folder/'.($folder->code)) }}" class="delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                 
                   {{--  <a id="delete_{{$folder->code}}" class="folder_{{$folder->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                   --}}
                     <input id="input_{{$folder->code}}" type="hidden" value="{{$folder->code}}">
                    <div id="loader" class="folder_{{$folder->code }}  d-none d-flex justify-content-center mt-3">
  
                    <div class="inner-loading dot-flashing"></div>
                    
                    </div> 

                    @endcan
                  
                   
                  </form> 
                </td>

              </tr>  

              {{-- @endif --}}
              
              @endif
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

@if ($folders->count()>0)
             

          <div class="row align-items-center py-5">
           
            
            <div class="col-12 text-center">
               <div class="custom-pagination">
               
                
                 {{ $folders ->links('layouts.partials.pagination') }}
               
                
               </div>
             </div>
           </div>
          
@endif