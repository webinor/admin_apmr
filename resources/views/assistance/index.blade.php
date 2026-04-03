@extends('layouts.app')



@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des APMR signées</a>
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


   {{-- --}} <a href="#" data-bs-toggle="modal" data-bs-target="#filterModal" class="btn btn-info text-white"><i class="mdi mdi-export"></i>Filtrer & Exporter les données</a> 


    <a href="{{url('assistance/create')}}" class="d-none btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau servant CAS</a>

   <!-- Bouton -->
{{--    <a href="#" class="btn btn-success text-white me-0" data-bs-toggle="modal" data-bs-target="#invoiceModal">
    <i class="bi bi-file-earmark-plus"></i> Nouvelle facture
  </a> --}}



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
        
                      <div class="col-2">
                        <label for="results">Afficher</label>
                      </div>
                      <div class="col-4">
              
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
                            numero de vol
                          </th>
                          
                          <th>
                            responsable d'escale
                          </th>
                          <th>
                            personnes assistees
                          </th>

                          <th>Fiche enregistrée par</th>

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

                        //$start_index  = $assistances->count() > 0 ? ($assistances->currentPage()-1)*$assistances->perPage() + 1 : 1;
           
                        
                        if (empty(request()->except(['page', 'results']))) {
    
    $start_index = ($assistances->currentPage() - 1) * $assistances->perPage() + 1;

} else {
    // Mode filtres avec get()
    $start_index = $assistances->count() > 0 ? 1 : 0;
}
                 

                        
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
                            {{$assistance->ground_agent ? $assistance->ground_agent->company->name : ''}}
                          </td>
 
                         {{--<td>
                            {{$assistance->activity_area != null ? $assistance->activity_area->name : ''}}
                          </td>--}}

                           {{--  <td>
                          $assistance->activities 
                          </td>--}}

                       

                          <td>
                            {{$assistance->flight_number}}

                          </td>

                         

                          <td>
                            {{$assistance->ground_agent ? $assistance->ground_agent->fullName() : ''}}
                          </td>

                          <td>
                            {{ $assistance->assistance_lines->count() }}
                          </td>
                         
                          
                          <td>

                            @if ($assistance->registrator)
                           
                            {{$assistance->registrator->name." ".$assistance->registrator->last_name}}
                                
                            @endif

                            
                          </td>
                         {{--  <td>
                            {{$assistance->created_at}}
                            
                          </td> --}}

                          <td>

                            {{ $assistance->flight_date }}

                          </td>
                          
                          <td>
                            <form>

                              
                              @can('view', $assistance)
                                  
                               <a    id="print_{{$assistance->code}}" class="assistance_{{$assistance->code }} me-3 print" href="{{url('assistance/'.$assistance->code)}}" ><i class="menu-icon mdi mdi-eye"></i></a>


                              @endcan
                                  
                              
                         {{--     
                              --}}

                          @can('update', $assistance)
                                 
                              <a    id="edit_{{$assistance->code}}" class="assistance_{{$assistance->code }} me-3 edit" href="{{url('assistance/'.$assistance->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>
                              
                          @endcan

                               @can('delete', $assistance)  
                            
                               
                               <a   data-bs-toggle="modal" data-bs-target="#delete-modal" data-model-to-delete="{{ $assistance->flight_number }}" data-delete-link="{{ ('/api/assistance/'.($assistance->code)) }}" class="delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                               
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

          @if ($assistances->count() > 0 && empty(request()->except(['page', 'results'])))
             
          

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





@include('layouts.partials._modal_filter') 

        
@endsection



@section('custom_js')

<script src="{{ asset('js/app.js') }}" ></script>

<script>

document.addEventListener('DOMContentLoaded', function () {




  




  

  const fileTypeSelect = document.getElementById('file_type');
    const form = document.getElementById('form_filter');
 
        function toggleActionDiv(actionDiv) {
        if (fileTypeSelect.value === 'pdf') {
            actionDiv.style.display = 'block';
        } else {
            actionDiv.style.display = 'none';
        }
    }

    fileTypeSelect.addEventListener('change', function () {

     // console.log(fileTypeSelect);
      
      const type = this.value;
          const actionDiv = document.getElementById('action').closest('.mb-3'); // le div parent à masquer/afficher


      // Exemple d'URLs selon le type
      let url = '/apmrs/export';
      if (type === 'excel') url = "{{ url('/apmrs/export?type=excel') }}";
      else if (type === 'pdf') url = "{{ url('/apmrs/export?type=pdf') }}";
      else if (type === 'csv') url = "{{ url('/apmrs/export?type=csv') }}";

      console.log(url);
      
      form.action = url;

          // Initialisation
    toggleActionDiv(actionDiv);

      
    });


const filterButton = document.getElementById('filter-button');
const cancelButton = document.getElementById('cancel-button');
const filterLoader = document.getElementById('filter-loader');

const exportButton = document.getElementById('export-button');
const exportLoader = document.getElementById('export-loader');

const filterUrl = document.getElementById('filter_url').value;

// Fonction pour récupérer les filtres du formulaire
function getFilters() {
  const filters = {};
  form.querySelectorAll('.filter').forEach(input => {
    if (input.type === 'checkbox') {
      if (input.checked) filters[input.name] = 1;
    } else if (input.value) {
      filters[input.name] = input.value;
    }
  });
  return filters;
}

// Fonction pour mettre à jour le nombre de résultats
async function updateCount() {
  const filters = getFilters();
  filterLoader.classList.remove('d-none');  
  
  /*exportButton.classList.add('d-none');
   filterButton.classList.add('d-none');
   cancelButton.classList.add('d-none');
   exportLoader.classList.remove('d-none');*/

  try {
    const params = new URLSearchParams(filters);
    const response = await fetch(`${filterUrl}?${params.toString()}`,{
  method: 'GET', // ou 'POST' selon ton cas
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${window.localStorage.getItem('token')}`,
    // Si tu envoies aussi du JSON dans le body, ajoute :
    // 'Content-Type': 'application/json'
  }});
    const data = await response.json();

    // Affiche le nombre de résultats sur le bouton
    filterButton.querySelector('#filter-button-text').textContent = `Filtrer (${data?.count_signed})`;
    exportButton.querySelector('#export-button-text').textContent = `Exporter (${data?.count_signed})`;

  } catch (error) {
    console.error(error);
  } finally {
    filterLoader.classList.add('d-none');

      /*  exportLoader.classList.add('d-none');
      exportButton.classList.remove('d-none');
      cancelButton.classList.remove('d-none');
      filterButton.classList.remove('d-none');*/
  }
}

// On écoute tous les changements dans le formulaire
form.querySelectorAll('.filter').forEach(input => {
  input.addEventListener('change', updateCount);
});


// Si tu veux, tu peux déclencher un filtre direct au clic du bouton
filterButton.addEventListener('click', async function () {


   exportButton.classList.add('d-none');
   filterButton.classList.add('d-none');
   cancelButton.classList.add('d-none');
   exportLoader.classList.remove('d-none');
  

   form.action = "/assistance";
  form.querySelector('input[name="export"]')?.remove();
  form.submit(); // Soumet le formulaire normalement

});

// Si tu veux, tu peux déclencher un filtre direct au clic du bouton
exportButton.addEventListener('click', async function () {

    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "export";
    input.value = "1";

    form.appendChild(input);

  //form.submit(); // Soumet le formulaire normalement

  /**/
   // Afficher loader
   exportButton.classList.add('d-none');
   filterButton.classList.add('d-none');
   cancelButton.classList.add('d-none');
   exportLoader.classList.remove('d-none');

    // Récupérer les données du formulaire
    const formData = new FormData(form);
    const action = form.getAttribute("action");   // ← PROPRE

    try {
      const params = new URLSearchParams();

for (let [key, value] of formData.entries()) {
    params.append(key, value);
}

const response = await fetch(`${action}?${params.toString()}`, {
        method: 'GET', // ou POST selon ton endpoint
     //   body: formData,
      });

      if (!response.ok) throw new Error('Erreur lors de l\'export');


      const data = await response.json();
console.log(data.exportId, data.message);
 
         Echo.private(`export.${data.exportId}`)
    .subscribed(() => console.log("SUBSCRIBED OK"))
    .error(e => console.error("SUBSCRIBE ERROR", e))
         .listen('.ApmrPdfReady', (e) => {
        /*const url = `${e.filePath}`;
        const a = document.createElement('a');
        a.href = url;
        a.download =e.name;// e.type === 'single' ? 'APMR_RECAP.pdf' : 'APMR_ALL.zip';
        document.body.appendChild(a);
        a.click();
        a.remove();*/


          // Ouvrir dans un nouvel onglet
        window.open(e.filePath, '_blank');

         exportLoader.classList.add('d-none');
      exportButton.classList.remove('d-none');
      cancelButton.classList.remove('d-none');
      filterButton.classList.remove('d-none');
      
      
      
      });
      

      /*
      // Si c'est un fichier à télécharger
      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;

      console.log(url);
      

      // Nom du fichier selon le type sélectionné
      const type = formData.get('file_type') || 'export';
      a.download = `${type}-export.${type === 'excel' ? 'xlsx' : type}`;
      document.body.appendChild(a);
      a.click();
      a.remove();*/

    } catch (error) {
      console.error(error);
      alert('Une erreur est survenue lors de l\'export.');
    } finally {
      // Masquer loader
      //exportLoader.classList.add('d-none');
      //exportButton.classList.remove('d-none');
      //cancelButton.classList.remove('d-none');
      //filterButton.classList.remove('d-none');
    }

  /*  */

});
});

</script>

@endsection