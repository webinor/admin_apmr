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
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations générales de la compagnie</a>
          </li>
       
                 
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" aria-controls="audiences" role="tab" aria-selected="false">Parametres de facturation</a>
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
    <!-- The current user can update the post... 
    <a href="{{url('sales/company/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau compagnie / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
           @if ($action == 'show')
           <a href="{{url('company/'.$company->code.'/edit')}}" class="btn btn-info text-white"><i class="icon-printer"></i>Modifier les informations de cette compagnie</a>
           @endif
            <a href="{{url('company')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des compagnies</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  @if ($company && $action=='update')
                  <h4 class="card-title">Modifier la compagnie</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">compagnie modifié avec succes</h6>
                  </div>
                  @elseif(!$company && $action=='create')
                  <h4 class="card-title">Ajouter une compagnie</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">compagnie crée avec succes</h6>
                  </div>
                  <span>Ici ce sont les informations de base. Apres avoir cliqué sur "Creer", naviguez sur les differents onglets pour indiquer les information complementaires.</span>
                  @elseif($company && $action=='show')

                  <h4 class="card-title">Visualiser la compagnie</h4>

                  @endif
                  <form id="form-company" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{Auth::user()->code}}" >
                    <input id="action" type="hidden"  value="{{$company ? 'edit' : 'create'}}" >
                    <input id="url" type="hidden"  value="{{"/api/company".($company ? "/".$company->code."?_method=PUT" : "")}}" >
                    <input id="action" type="hidden" class="" value="{{ $action }}" >

                    @if ($company)
                    
                    <input id="company" type="hidden" class="form-control" value="{{$company->code}}" >
                        
                    @endif
                  
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Nom de la compagnie</label>
                        <input {{ $readonly }} type="text" value="{{ $company ? $company->name : '' }}" name="name" class=" form-control" id="name" placeholder="Raison sociaa" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="prefix">Prefixe</label>
                        <input {{ $readonly }} type="text" value="{{ $company ? $company->prefix : '' }}" name="prefix" class=" form-control" id="prefix" placeholder="prefixe" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="mensual_fee">Abonement mensual</label>
                        <input {{ $readonly }} type="number" value="{{ $company ? $company->mensual_fee : '' }}" name="mensual_fee" class=" form-control" id="mensual_fee" placeholder="150000" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="billing_address">Adresse de facturation</label>
                        <input {{ $readonly }} type="text" value="{{ $company ? $company->billing_address : '' }}" name="billing_address" class=" form-control" id="billing_address" placeholder="Adresse de facturation" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
        

        

      

          <div class="form-group">
            <label for="city">Ville</label>
            <select name="city" class="form-control" id="city" placeholder="">
          
              @forelse ($cities as $city)
          
              @if ($loop->first)
              <option value="" >Seactionnez la ville </option>
              @endif
                 
              <option value="{{$city->code}}" {{ $company && $company->city->code == $city->code ? 'selected' : '' }}>{{$city->name}}</option>
              
              @empty
          
              <option value="">Aucune ville disponible</option>
          
              @endforelse
          
            </select>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div> 

      {{--     <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="unique_identification_number">Numero d'identifiant unique</label>
              <input type="text" name="unique_identification_number" class=" form-control" id="unique_identification_number" placeholder="Numero d'identifiant unique" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>
          
          --}}

         {{--  <div class="form-group row">
            <div claaudience_tabss="col-sm-12 mb-3 mb-sm-0">
              <label for="email">Adresse email</label>
          
              <input {{ $readonly }} type="email" value="{{ $company ? $company->email : '' }}" name="email" class=" form-control" id="email" placeholder="Adresse email" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div> --}}

         

          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="logo">Selectionner un logo</label>
          
              <input {{ $readonly }} type="file" name="file" class=" form-control" id="file" placeholder="file" required>
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
              
          
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ $company ? 'Modifier' : 'Creer' }}
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
         
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Ajouter les differentes facturations</h4>
                  {{-- <span>Ces chaises sont par exemple : C,S...</span> --}}
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">facturation ajoutée avec succes</h6>
                  </div>
                  <form id="form_company_wheel_chair" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->code}}" >
                    <input readonly class="company form-control" id="company" type="hidden" value="{{ $company != null ? $company->code : '' }}" >
                    
                    <input readonly class="tab"  type="hidden" value="audiences" >
                    <input readonly class="list"  type="hidden" value="wheel_chairs_table" >
                    <input readonly class="url"  type="hidden" value="company-wheel-chair" >
                    <input readonly class="instance"  type="hidden" value="company_wheel_chair" >
                    
          
                   

                    <div class="form-group">
                      <label for="wheel_chair">Selectionnez un type de chaise <span class="text-danger">*</span></label>
                      <select name="wheel_chair" class="form-control" id="wheel_chair" placeholder="">
                    
                        @forelse ($wheel_chairs as $wheel_chair)
                    
                        @if ($loop->first)
                        <option value="" >Selectionnez un type de chaise</option>
                        @endif
                           
                        <option value="{{$wheel_chair->code}}">{{Str::upper(__($wheel_chair->name))}}</option>
                        
                        @empty
                    
                        <option value="">Aucun type de chaise disponible</option>
                    
                        @endforelse
                    
                      </select>
                      <div class="valid-feedback">
                      </div>
                      <div class="invalid-feedback">
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="price">Prix unitaire </label>
                        <input type="number" name="price" value="{{$company ? $company ->title : ''}}" class=" form-control" id="price" placeholder="par exemple 5000">
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
                  <h4 class="card-title">Facturation des types de chaises</h4>
                  
                  <div class="table-responsive">
                    <table id="wheel_chairs_table" class="table table-striped">
                      <thead>
                        <tr>
                       
                          <th>
                            #
                          </th>

                          <th>
                            Type de chaise
                          </th>

                          <th>
                            Prix unitaire
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
                        @if ($company)
                        @foreach ($company->wheel_chairs as $wheel_chair)

                        <tr>
                          <td>
                            <div>
                              {{-- <i class="{{ $wheel_chair->icon }} fa-xl"></i> --}}
                            </div>
                          </td>
                          <td>
                            {{ Str::upper(__($wheel_chair->name)) }}
                          </td>
                          <td>
                            {{ $wheel_chair->pivot->price }}
                          </td>
                          <td>
                             <form>
                            <a id="delete_{{ $wheel_chair->pivot->id }}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
                            <input id="input_{{ $wheel_chair->pivot->id }}" type="hidden" value="{{ $wheel_chair->pivot->id }}"> 
                            <div id="loader_{{ $wheel_chair->pivot->id }}" class="d-none d-flex justify-content-center mt-3">
              
                              <div class="inner-loading dot-flashing"></div>
                              
                              </div> 
                          </form> {{-- --}}
                          </td>
                        </tr>
                    @endforeach
                        @endif
                      </tbody>
                    </table>
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
                    <input readonly class="company form-control" id="company" type="hidden" value="{{ $company != null ? $company->code : '' }}" >
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
                  <h4 class="card-title">Liste des documents administratifs du compagnie</h4>
                  
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
                        @if ($company)
                        @foreach ($company->documents as $file)
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
                          @endforeach ($company->file)
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
                  <h4 class="card-title">Ajouter une note sur ce compagnie</h4>
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
<script src="{{asset('js/gyte45tghjMUKV.js')}}"></script>




@endsection