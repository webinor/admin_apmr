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
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations générales sur la principe actif</a>
          </li>
       
         {{--   <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Documents administratifs du Principe actif ( KYC ) </a>
          </li>

       
           
         --}}
         {{-- <li class="nav-item">
            <a class="nav-link" id="digital-tab" data-bs-toggle="tab" href="#digital" role="tab" aria-selected="false">Présence digitale</a>
          </li>
          <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">Notes</a>
          </li> --}}
        </ul>
        <div>
          <div class="btn-wrapper">
            {{--@if (Auth::user()->can('create', App\Models\Sales\active_ingredient::class))--}
    <!-- The current user can update the post... -->
    <a href="{{url('sales/active_ingredient/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau Principe actif / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
           @if ($action == 'show')

           @endif
            <a href="{{url('active-ingredient')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des Principe actifs</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  @if ($active_ingredient && $action=='update')
                  <h4 class="card-title">Modifier le principe actif</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Principe actif modifiée avec succes</h6>
                  </div>
                  @elseif(!$active_ingredient && $action=='create')
                  <h4 class="card-title">Ajouter le principe actif</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Principe actif crée avec succes</h6>
                  </div>
                  <span>Ici ce sont les informations de base. Apres avoir cliqué sur "Creer", naviguez sur les differents onglets pour indiquer les information complementaires.</span>
                  @elseif($active_ingredient && $action=='show')

                  <h4 class="card-title">Visualiser le Principe actif</h4>

                  @endif
                  <form id="form" class="pt-3 " novalidate method="post" action="{{url('/import-therapeutic-class')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{Auth::user()->code}}" >
                    <input id="action" type="hidden"  value="{{$active_ingredient ? 'edit' : 'create'}}" >
                    <input id="url" type="hidden"  value="{{"/api/therapeutic-class".($active_ingredient ? "/".$active_ingredient->code."?_method=PUT" : "")}}" >

                    @if ($active_ingredient)
                    
                    <input id="active_ingredient" type="hidden" class="form-control" value="{{$active_ingredient->code}}" >
                        
                    @endif
                  


                    <div class="form-group row {{ $action == 'create' ? '' : 'd-none' }}">
                      <div class="col-lg-12 mb-3 mb-sm-0">
                        <label for="generation_type" class="col-form-label">Comment souhaitez-vous ajouter le principe actif ?</label>
                        <select name="generation_type" class="form-control form-control" id="generation_type" placeholder="" >
                          <option value="">Comment souhaitez-vous ajouter le principe actif ?</option>
                          {{-- <option value="M">Creer un nouveau profile</option> --}}
                          <option value="A">Ajouter à partir d'un fichier excel</option>
                          <option value="M" {{ $action == "create" ? "" : "selected" }}>Saisie manuelle</option>

                    
                        </select>              
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    

                    <div class="file-form d-none">

                      <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <label for="active_ingredients">Importer un fichier excel (.csv)</label>
                            <input type="file" name="active_ingredients" class="file form-control"
                                id="active_ingredients" placeholder="Facture" required>
                            <div class="valid-feedback">
                            </div>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                    </div>

                    </div>

                    <div class="manual-form  {{ $action == "create" ? "d-none" : "" }}">

                     {{--  @include('layouts.partials._active_ingredient_add') --}}

                    </div>
          
                  
          @if (($action!='show'))
              
          
                    <div id="{{ $active_ingredient ? 'update_button' : 'create_button' }}" class="mt-3">
                      <button id="{{ $active_ingredient ? 'update' : 'create' }}" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ $active_ingredient ? 'Modifier' : 'Creer' }}
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

        {{--           Techniciens            --}
        <div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="audiences"> 
        
          <div class="row d-none">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Ajouter un interlocuteur</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">interlocuteur cree avec succes</h6>
                  </div>
                  <form id="form_interlocutor" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="active_ingredient form-control" id="active_ingredient" type="hidden" value="" >
                    
                    <input readonly class="tab"  type="hidden" value="audiences" >
                    <input readonly class="list"  type="hidden" value="interlocutors_table" >
                    <input readonly class="url"  type="hidden" value="interlocutor/store" >
                    <input readonly class="instance"  type="hidden" value="interlocutor" >
                  


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="first_name">Nom</label>
                        <input type="text" name="first_name" class=" form-control" id="first_name" placeholder="Nom" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="last_name">Prenom</label>
                        <input type="text" name="last_name" class=" form-control" id="last_name" placeholder="Prenom" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="job">Poste</label>
                        <input type="text" name="job" class=" form-control" id="job" placeholder="Poste" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="email">adresse email</label>
                        <input type="email" name="email" class=" form-control" id="email" placeholder="Adresse email" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="main_phone_number">Contact principal</label>
          
              <input type="number" name="main_phone_number" class=" form-control" id="main_phone_number" placeholder="Contact principal" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="auxiliary_phone_number">Contact secondaire</label>
          
              <input type="number" name="auxiliary_phone_number" class=" form-control" id="auxiliary_phone_number" placeholder="Contact secondaire" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="whatsapp_phone_number">Contact whatsapp</label>
          
              <input type="number" name="whatsapp_phone_number" class=" form-control" id="whatsapp_phone_number" placeholder="Contact secondaire" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>

                  
                    <div id="add_interlocutor_button" class="mt-3">
                      <button  id="add_interlocutor" type="button" disabled class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Ajouter l'interlocuteur
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
                  <h4 class="card-title">Liste des pieces lié à ce Principe actif</h4>
                  
                  <div class="table-responsive">
                    <table id="interlocutors_table" class="table table-striped">
                      <thead>
                        <tr>
                         
                          <th>
                            Intitulé
                          </th>
                          
                          <th>
                            Type
                          </th>

                          <th>
                            Crée le
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
                        
                        @if ($active_ingredient && $action == 'show')
                        @foreach ($active_ingredient->resources as $resource)
                        <tr class="line_file">
                          <td>{{ $resource->slug }}</td> 
                          <td>{{ $resource_types[$resource->resourceable_type] ?? $resource->resourceable_type }}</td>
                          <td>{{ $resource->created_at }}</td> 
                            <td>
                             <form>
                              
                              <a target="_blank" id="print_{{$resource->code}}" class="file_{{$resource->code }}  me-3 print" href="{{url('storage/ad_images/'.$resource->path)}}" ><i class="menu-icon mdi mdi-eye"></i></a>

                              @if ($action!='show')
                                  
                              <a id="delete_{{ $resource->code }}" href="#"  class="file_{{$resource->code }}  delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{ $resource->code }}" type="hidden" value="{{ $resource->code }}"> 
                              <div id="loader_{{ $resource->code }}" class="file_{{$resource->code }}  d-none d-flex justify-content-center mt-3">
                                
                                <div class="inner-loading dot-flashing"></div>
                                
                              </div> 
                              
                              @endif
                            </form> 
                            </td>
                          </tr>
                          @endforeach ($active_ingredient->file)
                        @endif

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{--           End Techniciens        --}}

        
      </div>
    </div>
  </div>
</div>
@endsection



@section('custom_js')
<script src="{{asset('js/Drfg7ujhy34ed.js')}}"></script>
@endsection