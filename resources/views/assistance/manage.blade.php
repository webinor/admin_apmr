@extends('layouts.app')

@section('custom_css')
  {{--<link rel="stylesheet" href="{{asset('libs/vendors/select2/select2.min.css')}}">--}}
@endsection

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations générales de la fiche d'assistance</a>
          </li>
       
                 
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" aria-controls="audiences" role="tab" aria-selected="false">Liste des beneficiaires</a>
          </li>

        
          {{--  --}
         <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Historique documentaire</a>
          </li>

       
       
           
         {{--   --}}
         {{-- <li class="nav-item">
            <a class="nav-link" id="digital-tab" data-bs-toggle="tab" href="#digital" role="tab" aria-selected="false">Présence digitale</a>
          </li>
          <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">Notes</a>
          </li> --}}
        </ul>
        <div>
          <div class="btn-wrapper">
            {{--@if (Auth::user()->can('create', App\Models\Sales\company::class))--}
    <!-- The current user can update the post... -->
    <a href="{{url('sales/company/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau fiche d'assistance / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
           @if ($action == 'show')
           <a href="{{url('company/'.$assistance->code.'/edit')}}" class="btn btn-info text-white"><i class="icon-printer"></i>Modifier les informations de ce fiche d'assistance</a>
           @endif
            <a href="{{url('assistance')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des fiche d'assistances</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  @if ($assistance && $action=='update')
                  <h4 class="card-title">Modifier le fiche d'assistance</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">fiche d'assistance modifiée avec succes</h6>
                  </div>
                  @elseif(!$assistance && $action=='create')
                  <h4 class="card-title">Ajouter un fiche d'assistance</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">fiche d'assistance crée avec succes</h6>
                  </div>
                  <span>Ici ce sont les informations de base. Apres avoir cliqué sur "Modifier", naviguez sur les differents onglets pour indiquer les information complementaires.</span>
                  @elseif($assistance && $action=='show')

                  <h4 class="card-title">Visualiser le fiche d'assistance</h4>


                 

                  @endif
                  <form id="form-company" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{Auth::user()->code}}" >
                    <input id="action" type="hidden"  value="{{$assistance ? 'edit' : 'create'}}" >
                    <input id="url" type="hidden"  value="{{"/api/company".($assistance ? "/".$assistance->code."?_method=PUT" : "")}}" >

                    <input id="apmr_service_url" type="hidden" class="form-control" value="{{$apmr_url}}" >

                    @if ($assistance)
                    
                    <input id="assistance" type="hidden" class="form-control" value="{{$assistance->code}}" >
                    <input id="company" type="hidden" class="form-control" value="{{$assistance->ground_agent->company->code}}" >
                        
                    @endif


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="reference">Numero de la fiche</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance ? $assistance->reference : '' }}" name="reference" class=" form-control" id="reference" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
                  
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Nom de la compagnie</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance ? $assistance->ground_agent->company->name : '' }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Prefixe</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance->ground_agent->company ? $assistance->ground_agent->company->prefix : '' }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
        

        
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Ville</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance->ground_agent->company ? $assistance->ground_agent->company->city->name : '' }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Numero de vol</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance->flight_number ? $assistance->flight_number : '' }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Depart / Arrivee ?</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance->flight_type ? $assistance->flight_type : '' }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Chef de vol</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance->ground_agent->fullName() }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Commentaire</label>
                        <input {{ $readonly }} type="text" value="{{ $assistance->ground_agent->fullName() }}" name="name" class=" form-control" id="name" placeholder="Raison sociale" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="logo">Image de la fiche</label>
                    
                       {{--  <input {{ $readonly }} type="file" name="file" class=" form-control" id="file" placeholder="file" required>
           --}}
                         {{-- Affichage d'une miniature si image déjà existante{{ Storage::url($assistance->files->last()->path) }} --}}
                         @if($assistance->files->isNotEmpty())
                         <a href="{{ $apmr_url }}/storage/{{ $assistance->files->last()->path }}" target="_blank">
                         <div class="mb-2 text-center border rounded p-1" style="background: #f9f9f9;">
                              <img id="previewImage" src="{{ $apmr_url }}/storage/{{ $assistance->files->last()->path }}"
                                  alt="Fiche Assistance" 
                                  class="img-fluid rounded"
                                  style="max-height: 120px; object-fit: cover;">
                                </div>
                              </a>
                     @else
                         <div class="mb-2 text-center text-muted small">
                             Aucune fiche enregistrée
                         </div>
                     @endif
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div> 

          {{-- <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="begining_collaboration">Date de debut de collaboration</label>
          
              <input type="date" min="2010-01-01" max="{{date('Y-m-d')}}" name="begining_collaboration" class=" form-control" id="begining_collaboration" placeholder="Date de debut de collaboration" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div> --}}
                  
          @if (($action!='show'))
              
          
                    <div id="create_button" class="mt-3 d-none">
                      <button id="create" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ $assistance ? 'Modifier' : 'Creer' }}
                      </button>
                    </div> 
                    
                    <div id="loader" class="d-none d-flex justify-content-center mt-3">
                      
                        <div class="inner-loading dot-flashing"></div>
                      
                    </div>

                    @endif
                    
                  </form>
                </div>
              </div>
            </div>
            
          </div>
          
        </div>

        {{--  wheel_chairs  --}}

        <div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="wheel_chair"> 
         
          <div class="row d-none">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Ajouter les differents types de pieces</h4>
                  <span>Ces pieces sont les pieces principale, par exemple les chamber, les salles de bains...</span>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading"> piece ajoutee avec succes</h6>
                  </div>
                  <form id="form_wheel_chair" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="user" class="form-control" type="hidden" value="{{session('user')->code}}" >
                    <input readonly class="ad_id form-control" id="ad_id" type="hidden" value="{{ $assistance != null ? $assistance->code : '' }}" >
                    
                    <input readonly class="tab"  type="hidden" value="wheel_chair" >
                    <input readonly class="list"  type="hidden" value="wheel_chairs_table" >
                    <input readonly class="url"  type="hidden" value="wheel_chair_ad" >
                    <input readonly class="instance"  type="hidden" value="wheel_chair" >
                    
          
                   

                    <div class="form-group">
                      <label for="wheel_chair_id">Selectionnez un type de piece <span class="text-danger">*</span></label>
                      <select name="wheel_chair_id" class="form-control" id="wheel_chair_id" placeholder="">
                    
                        @forelse ($wheel_chairs as $wheel_chair)
                    
                        @if ($loop->first)
                        <option value="" >Selectionnez un type de piece</option>
                        @endif
                           
                        <option value="{{$wheel_chair->id}}">{{Str::upper(__($wheel_chair->name))}}</option>
                        
                        @empty
                    
                        <option value="">Aucun type de piece disponible</option>
                    
                        @endforelse
                    
                      </select>
                      <div class="valid-feedback">
                      </div>
                      <div class="invalid-feedback">
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="number">Prix unitaire </label>
                        <input type="number" name="number" value="{{$assistance ? $assistance ->title : ''}}" class=" form-control" id="number" placeholder="par exemple 2 chambres">
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                  
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  {{ $action =='update' ? '' : 'disabled' }} class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Ajouter
                      </button>
                    </div> 
                    
                    <div id="loader" class="d-none d-flex justify-content-center mt-3">
                      
                        <div class="inner-loading dot-flashing"></div>
                      
                    </div>
                    
                  </form>
                </div>
              </div>
            </div>
            
          </div>
          
      

          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste des bénéficiaires</h4>
          
                  {{-- Container des lignes --}}
                  <div id="form-container">
                    @foreach ($assistance->assistance_lines as $assistance_line)
                      <div class="form-row {{ $loop->index > 0 ? 'mt-2' : '' }}" data-code="{{ $assistance_line->code }}">
                        <form class="row align-items-end">
                          <input type="hidden" name="row_code[]" value="{{ $assistance_line->code }}">
                          <input id="apmr_service_url" type="hidden" class="form-control" value="{{$apmr_url}}" >
          
                          <!-- Colonne numéro -->
                          <div class="col-md-1 order-number">
                            <div class="form-group">
                              @if ($loop->iteration == 1)
                                <label>#</label>
                              @endif
                              <span class="form-control-plaintext fw-bold row-number">{{ $loop->iteration }}</span>
                            </div>
                          </div>
          
                          <!-- Bénéficiaire -->
                          <div class="col-md-3">
                            <div class="form-group">
                              @if ($loop->iteration == 1)
                                <label>Nom(s) du bénéficiaire</label>
                              @endif
                              <input type="text" name="beneficiary[]" value="{{ $assistance_line->beneficiary_name }}" class="form-control" placeholder="noms et prénoms">
                            </div>
                          </div>
          
                          <!-- Type de chaise -->
                          <div class="col-md-2">
                            <div class="form-group">
                              @if ($loop->iteration == 1)
                                <label>Type de chaise</label>
                              @endif
                              <select class="form-control" name="wheel_chair[]">
                                <option value="">Sélectionnez un type de chaise</option>
                                @foreach ($wheel_chairs as $wheel_chair)
                                  <option value="{{ $wheel_chair->code }}" {{ $assistance_line->wheel_chair_id == $wheel_chair->id ? 'selected' : '' }}>
                                    {{ $wheel_chair->slug }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
          
                          <!-- Agent CAS -->
                          <div class="col-md-3">
                            <div class="form-group">
                              @if ($loop->iteration == 1)
                                <label>Servant CAS</label>
                              @endif
                              <select class="form-control" name="agent[]">
                                <option value="">Sélectionnez le servant CAS</option>
                                @foreach ($agents as $agent)
                                  <option value="{{ $agent->code }}" {{ $assistance_line->assistance_agent_id == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->fullName() }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
          
                          <!-- Commentaire -->
                          <div class="col-md-2">
                            <div class="form-group">
                              @if ($loop->iteration == 1)
                                <label>Commentaire</label>
                              @endif
                              <input type="text" name="comment[]" value="{{ $assistance_line->comment }}" placeholder="Commentaire" class="form-control" />
                            </div>
                          </div>
          
                          <!-- Action -->
                          <div class="col-md-1 d-none">
                            <div class="form-group">
                              @if ($loop->iteration == 1)
                                <label></label>
                              @endif
                              <a href="#" class="text-secondary font-weight-bold text-xs me-2 remove-row" title="Supprimer">
                                🗑️
                              </a>
                            </div>
                          </div>
                        </form>
                      </div>
                    @endforeach
                  </div>
          
                  <!-- Bouton ajout -->
                  <div class="d-flex justify-content-end mt-3 d-none">
                    <button type="button" 
                      class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" 
                      id="add-row-btn"
                      title="Ajouter une ligne"
                      style="width: 60px; height: 60px; padding: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.15);">
                      <i class="bi bi-plus-lg fs-4"></i>
                    </button>
                  </div>
          
                </div>
              </div>
            </div>
          </div>
          

        </div>

        {{--  End wheel_chairs --}}

          

        {{--  Documents  --}

        
        <div class="tab-pane fade" id="demographics" role="tabpanel" aria-labelledby="demographics"> 
        
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body {{ $action!="show" ? '' : 'd-none' }}">
                  <h4 class="card-title">Ajouter un document</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">document crée avec succes</h6>
                  </div>
                  <form id="form_document" class="pt-3 " novalidate method="post" action="{{url('file/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{Auth::user()->code}}" >
                    <input readonly class="company form-control" id="company" type="hidden" value="{{ $assistance != null ? $assistance->code : '' }}" >
                    <input readonly class="tab"  type="hidden" value="demographics" >
                    <input readonly class="list"  type="hidden" value="documents_table" >
                    <input readonly class="instance"  type="hidden" value="file" >
                    <input type="hidden" id="instance_type" value="{{$instance_type}}">
                    <input readonly class="url"  type="hidden" value="file" >

                  
                    
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="file">Selectionner un fichier</label>
                    
                        <input type="file" name="file" multiple="multiple" class="file form-control"  placeholder="Contact telephonique" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="validity">Validité du document ( mois )</label>
                        <input type="number" name="validity" class=" form-control" id="validity" placeholder="Ex : 3" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                  

                    @if ($action != 'show')
                        
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  {{ $disabled }}  class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Ajouter le document
                      </button>
                    </div> 
                    
                    <div id="loader" class="d-none d-flex justify-content-center mt-3">
                      
                        <div class="inner-loading dot-flashing"></div>
                      
                    </div>

                    @endif
                  
                   
                    
                  </form>
                </div>
              </div>
            </div>
            
          </div>
          
          <div class="row d-none">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste des documents administratifs du fiche d'assistance</h4>
                  
                  <div class="table-responsive">
                    <table id="documents_table" class="table table-striped instances_lines"  data-url="file" data-type="file">
                      <thead>
                        <tr>
                         
                          <th>
                            Nom
                          </th>
                          
                          <th>
                            Type
                          </th>

                          <th>
                            Ajouté le 
                          </th>

                          <th>
                            Action
                          </th>

                        </tr>
                      </thead>
                      @php
                      $index = 1
                  @endphp
                      <tbody>
                        @if ($assistance)
                        @foreach ($assistance->documents as $file)
                        <tr class="line_file">
                          <td>{{ $file->name }}</td> 
                          <td>{{ $file->type }}</td>
                          <td>{{ $file->created_at }}</td> 
                            <td>
                             <form>
                              
                              <a target="_blank" id="print_{{$file->code}}" class="file_{{$file->code }}  me-3 print" href="{{url('storage/ad_images/'.$file->path)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                              @if ($action!='show')
                                  
                              <a id="delete_{{ $file->code }}" href="#"  class="file_{{$file->code }}  delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{ $file->code }}" type="hidden" value="{{ $file->code }}"> 
                              <div id="loader_{{ $file->code }}" class="file_{{$file->code }}  d-none d-flex justify-content-center mt-3">
                                
                                <div class="inner-loading dot-flashing"></div>
                                
                              </div> 
                              
                              @endif
                            </form> 
                            </td>
                          </tr>
                          @endforeach ($assistance->file)
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{--  End documents --}}

        {{--           Identite digitale            --}
        <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital"> 
         
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Ajouter une plateforme digitale</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">platform cree avec succes</h6>
                  </div>
                  <form id="form_platform" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="company form-control" id="company" type="hidden" value="" >
                    
                    <input readonly class="tab"  type="hidden" value="digital" >
                    <input readonly class="list"  type="hidden" value="platforms_table" >
                    <input readonly class="url"  type="hidden" value="platform/store" >
                    <input readonly class="instance"  type="hidden" value="platform" >
                    
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="slug">Plateforme</label>
                        <select name="slug" class="form-control form-control" id="slug" placeholder="" >

                          <option value="">Selectionnez une plateforme</option>
                          <option value="Linkedin">Linkedin</option>
                          <option value="Whatsapp">Whatsapp</option>
                          <option value="Web site">Site internet</option>
                          <option value="Facebook">Facebook</option>
                          <option value="Instagram">Instagram</option>
                        </select>      
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="link">lien</label>
                        <input type="text" name="link" class=" form-control" id="link" placeholder="Poste" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                  
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  disabled class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Ajouter la plateforme
                      </button>
                    </div> 
                    
                    <div id="loader" class="d-none d-flex justify-content-center mt-3">
                      
                        <div class="inner-loading dot-flashing"></div>
                      
                    </div>
                    
                  </form>
                </div>
              </div>
            </div>
            
          </div>
          
          <div class="row">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste des plateformes</h4>
                  
                  <div class="table-responsive">
                    <table id="platforms_table" class="table table-striped">
                      <thead>
                        <tr>
                         
                          <th>
                            plateforme
                          </th>
                          
                          <th>
                            lien
                          </th>

                          <th>
                            Action
                          </th>
                        </tr>
                      </thead>
                      @php
                      $index = 1
                  @endphp
                      <tbody>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{--          End Identite digitale        --}}
         {{--  Notes  --}

         <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="more"> 
         
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Ajouter une note sur ce fiche d'assistance</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading"> Note cree avec succes</h6>
                  </div>
                  <form id="form_note" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="company form-control" id="company" type="hidden" value="" >
                    
                    <input readonly class="tab"  type="hidden" value="more" >
                    <input readonly class="list"  type="hidden" value="notes_table" >
                    <input readonly class="url"  type="hidden" value="note/store" >
                    <input readonly class="instance"  type="hidden" value="note" >
                    
          
                   

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="note">Description</label>
                    
                        <textarea rows="4" name="note" class=" form-control" id="note" placeholder="Description du document" required></textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                  
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  disabled class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Ajouter
                      </button>
                    </div> 
                    
                    <div id="loader" class="d-none d-flex justify-content-center mt-3">
                      
                        <div class="inner-loading dot-flashing"></div>
                      
                    </div>
                    
                  </form>
                </div>
              </div>
            </div>
            
          </div>
          
          <div class="row">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste des notes</h4>
                  
                  <div class="table-responsive">
                    <table id="notes_table" class="table table-striped">
                      <thead>
                        <tr>
                       
                          <th>
                            note
                          </th>

                          <th>
                            Action
                          </th>

                        </tr>
                      </thead>
                      @php
                      $index = 1
                  @endphp
                      <tbody>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{--  End notes --}}
      </div>
    </div>
  </div>
</div>
@endsection



@section('custom_js')
<script src="{{asset('js/fon10F5SaHMUKV.js')}}"></script>



<script>
  $(document).ready(function () {


    let apmr_service_url = $("#apmr_service_url").val();

                          function createNewRow(companyId) {
                              return getWheelChairsByCompany(companyId).then(wheelChairs => {
                                  let options = `<option value="">Sélectionnez un type de chaise</option>`;
                                  wheelChairs.forEach(item => {
                                      options += `<option value="${item.code}">${item.slug}</option>`;
                                  });

                                  // Template HTML de la nouvelle ligne
                                  let rowHtml = `
                                  <div class="form-row mt-2">
                                      <form class="row align-items-end">
                                          <input type="hidden" name="row_code[]" value="${Math.random().toString(36).substring(2, 10)}">
                                          
                                          <div class="col-md-3">
                                              <input name="beneficiary[]" type="text" class="form-control" placeholder="noms et prénoms">
                                          </div>

                                          <div class="col-md-2">
                                              <select class="form-control" name="wheel_chair[]">
                                                  ${options}
                                              </select>
                                          </div>

                                          <div class="col-md-3">
                                              <select class="form-control" name="agent[]">
                                                  <option value="">Sélectionnez le servant CAS</option>
                                              </select>
                                          </div>

                                          <div class="col-md-2">
                                              <input type="text" name="comment[]" placeholder="Commentaire" class="form-control">
                                          </div>

                                          <div class="col-md-1">
                                              <a href="#" class="remove-row">🗑️</a>
                                          </div>
                                      </form>
                                  </div>`;

                                  $("#form-container").append(rowHtml);


                              });
                          }


                          function getWheelChairsByCompany(companyId) {
                              return $.get(`${apmr_service_url}/api/operations/company/${companyId}/wheelchairs`);
                          }

                          // $(document).ready(function () {
                              // Génère une ligne de formulaire avec ou sans labels
                            async  function createFormRow(withLabels ,  rowCode = "") { 

                                let companyId = $("#company").val();
                              
                                if (!companyId) {
                              alert("Veuillez sélectionner une compagnie !");
                              return; // arrête l'exécution si aucune compagnie sélectionnée
                              }

                              
                                const wheelChairs = await getWheelChairsByCompany(companyId);//.then(wheelChairs => {
                                  let options = `<option value="">Sélectionnez un type de chaise</option>`;
                                  wheelChairs.forEach(item => {
                                      options += `<option value="${item.code}">${item.slug}</option>`;
                                  });
                              
                                ///  console.log(options);

                              return  rowHtml = `
                                  <div class="form-row" data-code="${rowCode}">
                                    <form class="row align-items-end">
                                      <input type="hidden" name="row_code[]" value="${rowCode}">
                                      <!-- Colonne numéro -->
                                      <div class="col-md-1 order-number">
                                        <div class="form-group">
                                          ${withLabels ? '<label>#</label>' : ''}
                                          <span class="form-control-plaintext fw-bold row-number">1</span>
                                        </div>
                                      </div>

                                      <!-- Bénéficiaire -->
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          ${withLabels ? '<label for="beneficiary">Nom(s) du bénéficiaire</label>' : ''}
                                          <input name="beneficiary[]" type="text" class="form-control" placeholder="noms et prénoms">
                                        </div>
                                      </div>

                                      <!-- Type de chaise -->
                                      <div class="col-md-2">
                                        <div class="form-group">
                                          ${withLabels ? '<label for="wheel_chair">Type de chaise</label>' : ''}
                                          <select class="form-control" name="wheel_chair[]">
                                            ${options}
                                          {{--  --} <option value="">Type de chaise</option>
                                            @foreach ($wheel_chairs as $wheel_chair)
                                            <option value="{{ $wheel_chair->code }}">{{ $wheel_chair->slug }}</option>
                                            @endforeach{{--  --}}
                                          </select>
                                        </div>
                                      </div>

                                      <!-- Agent -->
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          ${withLabels ? '<label for="agent">Servant CAS</label>' : ''}
                                          <select class="form-control" name="agent[]">
                                            <option value="">Sélectionnez le servant CAS</option>
                                            @foreach ($agents as $agent)
                                            <option value="{{ $agent->code }}">{{ $agent->fullName() }}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                      </div>

                                      <!-- Commentaire -->
                                      <div class="col-md-2">
                                        <div class="form-group">
                                          ${withLabels ? '<label for="comment">Commentaire</label>' : ''}
                                          <input type="text" name="comment[]" placeholder="Commentaire" class="form-control" />
                                        </div>
                                      </div>

                                      <!-- Action -->
                                      <div class="col-md-1">
                                        <div class="form-group">
                                          ${withLabels ? '' : ''}
                                          <a style="font-size: 1.25rem !important;" href="#" class="text-secondary font-weight-bold text-xs me-2 remove-row" title="Supprimer">
                                            🗑️
                                          </a>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                `;

                            // });
                              }

                              // Met à jour tous les numéros de ligne
                              function updateRowNumbers() {
                                $('#form-container .form-row').each(function (index) {
                                  $(this).find('.row-number').text(index + 1);
                                });
                              }
                          
                              // Ajout de ligne
                                $('#add-row-btn').on('click', async function () {
                                        const rowCount = $('#form-container .form-row').length;
                                          
                                              const newRow = await createFormRow(rowCount === 0);
                                              $('#form-container').append(newRow);
                                              updateRowNumbers();



                                          /*       // 📌 Scroll vers la nouvelle ligne
                                            let $lastRow = $('#form-container .form-row').last();
                                            $('html, body').animate({
                                                scrollTop: $lastRow.offset().top - 50 // -50 pour avoir un petit espace au-dessus
                                            }, 500); // 500ms pour l’animation
                                            */

                                            let $lastRow = $('#form-container .form-row').last();

                                        if ($lastRow.length) { // vérifie qu'il y a au moins une ligne
                                            let offsetTop = $lastRow.offset().top;

                                            // si c'est la première ligne et qu'elle est déjà en haut, scroll minimal
                                            if ($lastRow.is(':first-child')) {
                                                offsetTop = Math.max(offsetTop - 50, 0);
                                            } else {
                                                offsetTop = offsetTop - 50; // espace au-dessus
                                            }

                                            $('html, body').animate({
                                                scrollTop: offsetTop
                                            }, 500);
                                        }

                              });

                              // Suppression de ligne
                              $(document).on('click', '.remove-row', function (e) {
                                e.preventDefault();
                              // init_remove_row($(this));
                              });


                              function init_remove_row(row) {

                                      row.closest('.form-row').remove();

                                      // Si une seule ligne reste, on remet les labels
                                      const $rows = $('#form-container .form-row');
                                      if ($rows.length === 1) {
                                        const $form = $rows.first().find('form');
                                        $form.find('.col-md-3, .col-md-2, .col-md-1').each(function () {
                                          const $group = $(this).find('.form-group');
                                          if ($group.find('label').length === 0) {
                                            const input = $group.find('input, select');
                                            const name = input.attr('name') || '';
                                            let label = '';
                                            if (name.includes('beneficiary')) label = 'Nom(s) du bénéficiaire';
                                            else if (name.includes('wheel_chair')) label = 'Type de chaise';
                                            else if (name.includes('agent')) label = 'Servant CAS';
                                            else if (name.includes('comment')) label = 'Commentaire';
                                            else if ($group.find('a.remove-row').length) label = 'Action';
                                            else if ($group.find('.row-number').length) label = '#';

                                            if (label) $group.prepend(`<label>${label}</label>`);
                                          }
                                        });
                                      }

                                      updateRowNumbers();


                              }


                               $('#form-container').on('change', 'input, select', function () {

                                          checkBeneficiaries(false);

                                        let assistance = $('#assistance').val(); 
                                        let $row = $(this).closest('.form-row');
                                        let rowCode = $row.data('code') || null;

                                        let user = $("#user").val();;

                                        

                                        let beneficiary = $row.find('input[name="beneficiary[]"]').val().trim();
                                        let wheel_chair = $row.find('select[name="wheel_chair[]"]').val();
                                        let agent = $row.find('select[name="agent[]"]').val();
                                        let comment = $row.find('input[name="comment[]"]').val();

                                        // ✅ Vérifie si les champs requis sont remplis
                                        if (!beneficiary || !wheel_chair || !agent) {
                                          //  return; // On arrête ici si un champ requis est vide
                                        }

                                        let data = {
                                          is_adjustment : 1,
                                          assistance : assistance,
                                            assistanceLine: rowCode,
                                            beneficiary: beneficiary,
                                            wheel_chair: wheel_chair,
                                            agent: agent,
                                            comment: comment,
                                            _token: '{{ csrf_token() }}',
                                            user : user
                                        };

                                      // //console.log(data);
                                        

                                        $.ajax({
                                            url: rowCode ? `${apmr_service_url}/api/operations/update-row` : `${apmr_service_url}/api/operations/create-row`,
                                            type: 'POST',
                                            data: data,
                                            headers: {
        "Accept": "application/json"
    },
                                            success: function (response) {
                                                if (!rowCode && response.code) {
                                                    // On met à jour l'ID pour les prochaines modifs
                                                    $row.data('code', response.code);
                                                    $row.attr('data-code', response.code);
                                                    $row.find('input[name="row_code[]"]').val(response.code);
                                                }
                                            },
                                            error: function () {
                                                alert('Erreur lors de l’enregistrement');
                                            }
                                        });
                          });




                          // });





                    let lineToDelete = null
                    ;

                    $(document).on('click', '.remove-row', function() {
                        // Récupérer l'id de la ligne à supprimer
                        lineToDelete = $(this);

                        // Ouvrir le modal de confirmation
                        modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                        modal.show();
                    });

                    $('#confirmDeleteBtn').on('click', function() {
                      

                        if (!lineToDelete.closest('.form-row').data('code')) {
                          
                          init_remove_row(lineToDelete);

                          let modalEl1 = document.getElementById('confirmDeleteModal');
                                let modalInstance1 = bootstrap.Modal.getInstance(modalEl1);
                                modalInstance1.hide();

                          return;

                        }

                        let lineToDeleteCode = lineToDelete.closest('.form-row').data('code');

                        //console.log(`code == ${lineToDeleteCode}`);
                        

                        $.ajax({
                            url: "{{ url('api/operations/assistance-lines') }}/" + lineToDeleteCode, // ton endpoint Laravel
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                            },
                            success: function(response) {
                                // Supprimer la ligne du DOM
                              let row =$('[data-code="'+lineToDeleteCode+'"]');//  $('[data-code="' + lineToDeleteCode + '"]');//.remove();

                            //   //console.log($('[data-code]')); 
                              
                          
                                init_remove_row(row);

                                // Fermer le modal
                                let modalEl = document.getElementById('confirmDeleteModal');
                                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                                modalInstance.hide();

                                // Optionnel : message succès
                              // alert('Ligne supprimée avec succès !');
                            },
                            error: function(xhr) {
                                alert('Erreur lors de la suppression !');
                            }
                        });
                    });


                function add_prefix(input) {

                            // console.log(input);
                              
                              let value = input.val().replace(/^[a-zA-Z]+/, ""),
                                    prefix = $("#prefix").val();

                                  //  result = str
                                  //  console.log(prefix);
                                    
                        // Si le texte ne commence pas déjà par le préfixe, on l'ajoute
                        if (!value.startsWith(prefix)) {
                            input.val(prefix + value.replace(prefix, ""));
                        }          
               }

                        $("#flight_number").keyup(function (e) { 

                            add_prefix($(this))  
                          
                        });

                    $('#company').on('change', function () {
                           
                          const categoryId = $(this).val();

                            // Vider le second select
                            //$('#ground_agent').empty().append('<option value="">Chargement...</option>');

                            // Appel AJAX si une catégorie est sélectionnée
                            if (categoryId) {
                              $.ajax({
                                url: `${protocol}${host}/api/operations/ground_agents/get_by_company?company=${categoryId}`, // ton endpoint
                                method: 'GET',
                                success: function (data) {

                                  $("#prefix").val(data.prefix);

                                  add_prefix($("#flight_number"));
                                  // Supposons que data est un tableau d'objets { id, name }
                                  $('#ground_agent').empty().append('<option value="">-- Selectionnez le chef de vol --</option>');
                                  data.ground_agents.forEach(function (item) {
                                    $('#ground_agent').append(
                                      `<option value="${item.code}">${item.first_name} ${item.last_name}</option>`
                                    );
                                  });


                                  populate_wheel_chair(data.wheel_chairs);


                                },
                                error: function () {
                                  $('#ground_agent').empty().append('<option value="">Erreur de chargement</option>');
                                }
                              });
                            } else {
                              // Si aucun ID sélectionné, on réinitialise
                              $('#ground_agent').empty().append('<option value="">-- Selectionnez le chef de vol --</option>');
                            }

                    });

                          $("#form-assistance").find("input, select").on("change", function () {
                              let $input = $(this);
                              let formData = new FormData();

                              let assistance = $("#assistance").val();
                              let fieldName = $input.attr("name");


                            

                    // ⛔ On ignore "company" et "reference"
                    if (fieldName === "company" || fieldName === "reference") {
                        return;
                    }



                      
                              formData.append("field", $input.attr("name")); // nom du champ
                              if ($input.attr("type") === "file") {
                                  formData.append("value[]", $input[0].files[0]); // fichier
                              } else {
                                  formData.append("value", $input.val()); // valeur
                              }
                              formData.append("_token", "{{ csrf_token() }}"); // CSRF Laravel
                              formData.append("assistance", assistance); // valeur


                              send_data(formData);
                              
                          });


                          $("#form-comment").find("input, select").on("change", function () {
                              let $input = $(this);
                              let formData = new FormData();

                              let assistance = $("#assistance").val();
                              let fieldName = $input.attr("name");




                      
                              formData.append("field", $input.attr("name")); // nom du champ
                            
                                  formData.append("value", $input.val()); // valeur
                              
                              formData.append("_token", "{{ csrf_token() }}"); // CSRF Laravel
                              formData.append("assistance", assistance); // valeur


                              send_data(formData);
                              
                          });

                          function populate_wheel_chair(data) {

                            // Construire les <option>
                              let options = `<option value="">Type de chaise</option>`;
                            data.forEach(item => {
                                options += `<option value="${item.code}">${item.name}</option>`;
                            });

                            // Mettre à jour tous les selects "wheel_chair[]"
                            $("select[name='wheel_chair[]']").each(function() {
                                let currentValue = $(this).val(); // garder l’ancienne valeur si possible
                                $(this).html(options);

                                // Si l'ancienne valeur existe encore dans la nouvelle liste → on la garde
                                if (currentValue && $(this).find(`option[value='${currentValue}']`).length) {
                                    $(this).val(currentValue);
                                }
                            });
                            
                          }

                          function send_data(formData) {



                      
                            $.ajax({
                                  url: "{{ url('/api/operations/assistances/update-field') }}",
                                  method: "POST",
                                  data: formData,
                                  processData: false,
                                  contentType: false,
                                  success: function (res) {
                                      //console.log("✅ Champ mis à jour :", res);

                                      if(res.success && res?.files?.length > 0){
                                const lastFile = res.files[res.files.length - 1];

                              // let lastFile = res.files[response.files.length - 1];

                    // Afficher ses propriétés
                    ////console.log(lastFile.path);
                    ////console.log(lastFile.name);
                    ////console.log(lastFile.id);
                                // Pour forcer le navigateur à recharger l'image, on ajoute un paramètre unique
                                const img = document.getElementById('previewImage');
                            
                                //let lastFile = response.files[response.files.length - 1];

                    if (lastFile) {
                        let $preview = $('#previewImage');

                        if ($preview.length) {
                            // Si l'image existe déjà → juste mettre à jour src
                          //  img.src = '/storage/' + lastFile.path + '?t=' + new Date().getTime();
                          
                          
                            $preview.attr('src', '/storage/' + lastFile.path + '?t=' + new Date().getTime());
                        } else {
                            // Sinon créer l'image dans le DOM
                            let imgHtml = `
                                <div class="mb-2 text-center border rounded p-1" style="background: #f9f9f9;">
                                    <img id="previewImage" src="${'/storage/' + lastFile.path  + '?t=' + new Date().getTime()}" 
                                        alt="Fiche Assistance" 
                                        class="img-fluid rounded"
                                        style="max-height: 120px; object-fit: cover;">
                                </div>`;
                            $('#file').closest('.form-group').prepend(imgHtml);
                        }
                    }

                          
                          
                              }

                                  },
                                  error: function (xhr) {
                                      console.error("❌ Erreur :", xhr.responseText);
                                  }
                              });
                            
                          }
                      });
                      </script>
                      

                    <script>

                    /*document.getElementById('sign').addEventListener('click', function() {
                        const modal = new bootstrap.Modal(document.getElementById('signModal'));
                        modal.show();
                    });*/


                    document.getElementById('submitSign').addEventListener('click', function() {
                        const form = document.getElementById('signForm');
                        if(form.checkValidity()) {
                            const data = {
                                //userCode: document.getElementById('userCode').value,
                                //password: document.getElementById('password').value,
                                otp: document.getElementById('otp').value
                            };

                            // Ici tu peux envoyer les données en AJAX
                            //console.log('Signature data:', data);
                            verity_otp();

                            
                        } else {
                            form.reportValidity(); // montre les erreurs de validation
                        }


                        function verity_otp() {


                          $("#otp-result").text("");
                          $(".modal-footer button").addClass("d-none");
                          $(".modal-footer .loader").removeClass("d-none");
                        
                            const otp = $("#otp").val();

                            $.ajax({
                                url: "{{ url('/api/verify-otp') }}", // route Laravel
                                type: "POST",
                                data: {
                                    otp: otp,
                                    assistance: $('#assistance').val()
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {

                                  

                                    if (response.valid) {

                                      sign_ended();
                                      //  $("#otp-result").text("OTP valide ✅").css("color", "green");
                                    } else {

                                      console.log(response);
                                      
                                      $(".modal-footer button").removeClass("d-none");
                                      $(".modal-footer .loader").addClass("d-none");

                                        $("#otp-result").text("Code de verification invalide ❌").css("color", "red");
                                    }
                                },
                                error: function(xhr, status, error) {

                                  $(".modal-footer button").removeClass("d-none");
                                  $(".modal-footer .loader").addClass("d-none");

                                    $("#otp-result").text("Erreur serveur : " + error).css("color", "red");
                                }
                            });
                        
                          
                        }


                        function sign_ended() { 


                          // Simulation de la signature réussie
                        let count = 5;
                        
                        // Remplacer le contenu du modal par le message de succès
                        const modalBody = document.querySelector("#signModal .modal-body");
                        modalBody.innerHTML = `
                            <div class="fw-bold alert alert-success text-center">
                                Signature effectuée avec succès.<br>
                                Vous serez redirigé dans <span id="countdown">${count}</span> seconde(s)...
                            </div>
                        `;

                        // Désactiver les boutons du footer
                       // document.querySelector("#signModal .modal-footer").innerHTML = "";

                        // Compte à rebours
                        const countdownInterval = setInterval(() => {
                            count--;
                            document.getElementById("countdown").textContent = count;
                            if (count <= 0) {
                                clearInterval(countdownInterval);
                                window.location.href = "{{ url('/operations/assistances') }}"; // Redirection vers l'accueil
                            }
                        }, 1000);
                          
                        }


                        function save_signature() {


                          const canvas = document.getElementById("signature-pad");
                        const ctx = canvas.getContext("2d");

                        // Exemple simple : dessiner sur le canvas
                        let drawing = false;
                        canvas.addEventListener("mousedown", () => drawing = true);
                        canvas.addEventListener("mouseup", () => drawing = false);
                        canvas.addEventListener("mousemove", (e) => {
                            if (!drawing) return;
                            ctx.lineWidth = 2;
                            ctx.lineCap = "round";
                            ctx.strokeStyle = "#000";
                            ctx.lineTo(e.offsetX, e.offsetY);
                            ctx.stroke();
                            ctx.beginPath();
                            ctx.moveTo(e.offsetX, e.offsetY);
                        });

                        // Enregistrer la signature
                        $("#save-signature").click(function () {
                            const dataUrl = canvas.toDataURL("image/png"); // convertit en base64

                            $.ajax({
                                url: "/api/signature", // route Laravel
                                type: "POST",
                                data: {
                                    signature: dataUrl
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    alert("Signature enregistrée et mail envoyé !");
                                },
                                error: function(xhr, status, error) {
                                    alert("Erreur : " + error);
                                }
                            });
                        });
                          
                        }

                    });




                      function checkBeneficiaries(from_button = true) {
                          
                        let errors = [];

                    $("#form-container .form-row").each(function () {
                        let rowNumber = $(this).find(".row-number").text().trim();

                        let beneficiaryInput = $(this).find("input[name='beneficiary[]']");
                        let wheelChairSelect = $(this).find("select[name='wheel_chair[]']");
                        let agentSelect = $(this).find("select[name='agent[]']");

                        let beneficiary = beneficiaryInput.val().trim();
                        let wheelChair = wheelChairSelect.val();
                        let agent = agentSelect.val();

                        let rowErrors = [];

                        // Vérification bénéficiaire
                        if (beneficiary === "") {
                            rowErrors.push("vous devez saisir le nom");
                            beneficiaryInput.addClass("is-invalid").removeClass("is-valid");
                        } else {
                            beneficiaryInput.addClass("is-valid").removeClass("is-invalid");
                        }

                        // Vérification type chaise
                        if (wheelChair === "") {
                            rowErrors.push("vous devez indiquer le type de chaise");
                            wheelChairSelect.addClass("is-invalid").removeClass("is-valid");
                        } else {
                            wheelChairSelect.addClass("is-valid").removeClass("is-invalid");
                        }

                        // Vérification agent CAS
                        if (agent === "") {
                            rowErrors.push("vous devez sélectionner un Servant CAS");
                            agentSelect.addClass("is-invalid").removeClass("is-valid");
                        } else {
                            agentSelect.addClass("is-valid").removeClass("is-invalid");
                        }

                        if (rowErrors.length > 0) {
                            errors.push("Bénéficiaire " + rowNumber + " : " + rowErrors.join(" et "));
                        }
                    });

                    if (from_button) {


                      if (errors.length > 0) {
                        $("#error-list").html(errors.map(err => `<li>${err}</li>`).join(""));
                        $("#errorModal").modal("show");
                        return false;
                    } else {
                        // ✅ Toutes les lignes sont valides
                        return true;
                    }


                      
                    }
                   

                        
                        };

                        function oldcheckBeneficiaries() {
                            let errors = [];

                            $("#form-container .form-row").each(function () {
                                let rowNumber = $(this).find(".row-number").text().trim();

                                let beneficiary = $(this).find("input[name='beneficiary[]']").val().trim();
                                let wheelChair = $(this).find("select[name='wheel_chair[]']").val();
                                let agent = $(this).find("select[name='agent[]']").val();

                                let rowErrors = [];

                                if (beneficiary === "") {
                                    rowErrors.push("vous devez saisir le nom");
                                }
                                if (wheelChair === "") {
                                    rowErrors.push("vous devez indiquer le type de chaise");
                                }
                                if (agent === "") {
                                    rowErrors.push("vous devez sélectionner un Servant CAS");
                                }

                                if (rowErrors.length > 0) {
                                    errors.push("Bénéficiaire " + rowNumber + " : " + rowErrors.join(" et "));
                                }
                            });

                            if (errors.length > 0) {
                                $("#error-list").html(errors.map(err => `<li>${err}</li>`).join(""));
                                $("#errorModal").modal("show");
                            } else {

                              return true;
                              // alert("✅ Toutes les lignes sont valides !");
                            }
                        };

                        function isValidWithPrefix(value, prefix) {
                        // console.log(value);
                          
                      return value.startsWith(prefix) && value.length > prefix.length;
                    }

                        $("#sign").on("click", function() {
                          //let form = $("#form-assistance")[0];
                          let form = $(".form-assistance");
                            let invalidFields = [];

                            // Vérification des inputs et selects requis
                            $(form).find("input, select").each(function() {
                                let $field = $(this);
                                let type = $field.attr("type");

                              //  console.log($field);
                                

                                if ($field.prop("required")) {
                                    if (type === "file") {

                                              // Valide si un fichier est sélectionné OU si l'image existe dans le DOM
                                              let fileSelected = $field[0].files && $field[0].files.length > 0;
                                        let imgExists = $("#previewImage").length > 0;

                                        if (!fileSelected && !imgExists) {
                                            let label = $field.closest(".form-group").find("label").text();
                                            invalidFields.push(label.trim() + " (fichier manquant)");
                                        }
                                    } else if ($field.is("select")) {
                                        if (!$field.val()) {
                                            let label = $field.closest(".form-group").find("label").text();
                                            invalidFields.push(label.trim() + " (sélection obligatoire)");
                                        }
                                    } else {


                                    //  console.log(!isValidWithPrefix($field.val(), $("#prefix").val()));
                                      
                                      if ( $field.attr("id") == "comment" && !$field.val() ) {
                                            let label = $field.closest(".form-group").find("label").text();
                                            invalidFields.push(label.trim() + " (champ requis)");
                                        }

                                        else if ($field.attr("id") == "prefix" && !isValidWithPrefix($field.val(), $("#prefix").val())) {
                                            let label = $field.closest(".form-group").find("label").text();
                                            invalidFields.push(label.trim() + " (champ requis)");
                                        }
                                    }
                                }
                            });

                            if (invalidFields.length > 0) {
                                // Afficher les erreurs dans le modal
                                let html = "<ul>";
                                invalidFields.forEach(function(field) {
                                    html += "<li>" + field + "</li>";
                                });
                                html += "</ul>";
                                $("#validationErrors").html(html);

                                // Ouvrir le modal d’erreurs
                                let errorModal = new bootstrap.Modal(document.getElementById('validationModal'));
                                errorModal.show();
                            } else { 
                                // Formulaire valide → ouvrir le modal de signature
                                //let signatureModal = new bootstrap.Modal(document.getElementById('signModal'));
                                //signatureModal.show();


                              let beneficiairesValid =  checkBeneficiaries();

                              if (beneficiairesValid) {

                                let modalRecap = new bootstrap.Modal(document.getElementById('modalRecap'));
                                modalRecap.show();

                                fill_recap_modal(modalRecap);
                                
                              }
                            

                              
                                
                            }


                                        
                        });



                        function fill_recap_modal(modalRecap) { 


                    $(".body-recap .loader").removeClass("d-none");

                    let ficheCode = $("#assistance").val(); // par ex. champ caché avec l'ID

                    // Requête AJAX pour récupérer les données
                    $.ajax({
                    url: `/api/operations/assistances/get_by_code/${ficheCode}`, // ton endpoint Laravel
                    method: "GET",
                    success: function(response) {

                    const data = response.data;

                    // console.log(data);

                    // En-tête
                    /*  $("#recapHeader").html(`
                    <p><strong>Fiche # :</strong> ${data.reference}</p>
                    <p><strong>Vol # :</strong> ${data.flight_number}</p>
                    <p><strong>Compagnie :</strong> ${data.ground_agent.company.name}</p>
                    <p><strong>Chef d'escale :</strong> ${data.ground_agent.first_name+" "+data.ground_agent.last_name}</p>
                    <p><strong>Date/Heure :</strong> ${data.flight_date} </p>
                    `);*/

                    $("#recapRef").text(data.reference);
                    $("#recapFlight").text(data.flight_number);
                    $("#recapCompany").text(data.ground_agent.company.name);
                    $("#recapAgent").text(data.ground_agent.first_name + " " + data.ground_agent.last_name);
                    $("#recapDate").text(data.flight_date);
                    $("#recapTotal").text(data.assistance_lines.length);

                    let countChaises = response.countChaises;
                    /*  let countChaises =  {
                    C: 0,
                    R: 0,
                    S: 0,
                    autres: 0
                    };*/

                    console.log(countChaises);

                    // Lignes
                    let lignesHtml = "";
                    data.assistance_lines.forEach((l , index) => {



                    const type = l.wheel_chair?.name || 'autres';

                    //  console.log(type);


                    // Incrémenter le compteur selon le type
                    if (countChaises.hasOwnProperty(type)) {
                    countChaises[type]++;
                    } else {
                    countChaises.autres++;
                    }


                    lignesHtml += `
                    <tr class="text-center">
                    <td>${index + 1}</td>
                    <td>${l.beneficiary_name}</td>
                    <td>${l.wheel_chair.name}</td>
                    <td>${l.assistance_agent.first_name+" "+l.assistance_agent.last_name}</td>
                    <td>${l.comment ?? ''}</td>
                    </tr>
                    `;


                    });

                    $("#recapLignes").html(lignesHtml);

                    // Pied de page récapitulatif
                    /*  $("#recapFooter").html(`
                    <p><strong>Total bénéficiaires :</strong> ${data.assistance_lines.length}</p>
                    <p><strong>Chaises C :</strong> ${countChaises.C}</p>
                    <p><strong>Chaises R :</strong> ${countChaises.R}</p>
                    <p><strong>Chaises S :</strong> ${countChaises.S}</p>
                    <p><strong>Autres :</strong> ${countChaises.autres}</p>
                    `);*/

                    let html = "";

                    Object.keys(countChaises).forEach(type => {
                    html += `<p><strong>${type} :</strong> ${countChaises[type]}</p>`;
                    });

                    $("#recapFooter").html(html);

                    // Affiche le modal
                    //$("#modalRecap").modal("show");
                    },
                    error: function() {
                    alert("Impossible de récupérer les données de la fiche.");
                    $(".body-recap .loader").addClass("d-none");
                    },
                    complete:function(){
                    $(".body-recap .loader").addClass("d-none");

                    }
                    });



                    }


                        /////////////////////////////


                        document.getElementById('btnSigner').addEventListener('click', function () {
                        // Fermer le modal de récap
                        const recapModal = bootstrap.Modal.getInstance(document.getElementById('modalRecap'));
                        recapModal.hide();

                        // Attendre la fin de l'animation pour ouvrir le modal de signature
                        setTimeout(() => {
                            const signatureModal = new bootstrap.Modal(document.getElementById('signModal'));
                            signatureModal.show();
                        }, 500); // délai = durée de l'animation du modal Bootstrap (0.5s par défaut)
                    });


                        ////////////////////////////




                    


                    /////////////validation des formulaire


                      // Fonction de validation générique
                    function validateField(field) {
                        const rule = field.dataset.validate;
                        const val = field.value.trim();
                        let isValid = false;

                        switch(rule) {
                            case 'required':
                                isValid = val.length > 0;
                                break;
                            case 'otp':
                                isValid = /^\d{6}$/.test(val);
                                break;
                            default:
                                if(rule.startsWith('min:')){
                                    const min = parseInt(rule.split(':')[1]);
                                    isValid = val.length >= min;
                                }
                        }

                        field.classList.toggle('is-valid', isValid);
                        field.classList.toggle('is-invalid', !isValid);
                        return isValid;
                    }

                    // Validation d’un formulaire entier
                    function validateForm(form) {
                        let isValid = true;
                        const fields = form.querySelectorAll('[data-validate]');
                        fields.forEach(field => {
                            if(!validateField(field)) isValid = false;
                        });
                        return isValid;
                    }

                    // Attacher la validation à tous les formulaires avec la classe .validated-form
                    document.querySelectorAll('.validated-form').forEach(form => {
                        const submitBtn = form.querySelector('.submit-btn');
                        submitBtn.addEventListener('click', () => {
                            if(validateForm(form)){
                                //console.log('Formulaire valide ! Envoi possible...');
                            } else {
                                //console.log('Certains champs sont invalides !');
                            }
                        });

                        // Validation live à la saisie
                        form.querySelectorAll('[data-validate]').forEach(field => {
                            field.addEventListener('input', () => validateField(field));
                        });
                    });

</script>






@endsection