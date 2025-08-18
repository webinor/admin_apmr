<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Liste des facture rattachées au bodereau  <span class="slip-identification"> {{ Request::get('slip') ?? $slip_identification }} </span></h4>
    
      <div class="table-responsive">
        <table id="folder" class="instances_lines table table-striped" data-url="folder" data-type="folder">
          <thead>
            <tr>
              <th>
                Numero dossier
              </th>

              <th>Nom du fichier</th>
              
              {{-- <th>
                Prestataire
              </th> --}}

             {{--  <th>Statut</th> --}}
              
             {{--  <th>
                Ajouté le
              </th> --}}
             {{--  <th>
                Ajouté par
              </th> --}}
             <th>Action</th>
              
            </tr>
          </thead>
          <tbody class="body-folder">
            @foreach ($folders as $folder)
            <tr>
              
              <td>
                {{$folder->identification}}
              </td>

            {{--   <td>
                {{$folder->provider->name}}
              </td> --}}
              <td>
              
                <div  class="fw-bold badge badge-opacity-{{ $folder->get_status()["color"] }}">{{ $folder->get_status()["text"] }}</div>

               
              </td>

  
              <td>
                {{$folder->created_at}}
                
              </td> 

              <td>

                @if ($folder->user)
               
                {{$folder->user->employee->full_name()}}
                    
                @endif

                
              </td>
             {{--  --}}
              
              <td>
                <form>

                  @can('view', $folder)
                  
                  <a    id="print_{{$folder->code}}" class="folder_{{$folder->code }} me-3 print" href="{{url('extract/'.$folder->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                  @endcan

                  {{-- @can('update', $folder)
                  <a    id="edit_{{$folder->code}}" class="folder_{{$folder->code }} me-3 edit" href="{{url('extract/'.$folder->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                  @endcan --}}

                 {{--  @can('delete', $folder)
                
                  <a id="delete_{{$folder->code}}" class="folder_{{$folder->code }}  delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                  <input id="input_{{$folder->code}}" type="hidden" value="{{$folder->code}}">
                  <div id="loader" class="folder_{{$folder->code }}  d-none d-flex justify-content-center mt-3">

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