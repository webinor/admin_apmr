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
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations générales du servant CAS</a>
          </li>
       
                 
         {{--  <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" aria-controls="audiences" role="tab" aria-selected="false">Parametres d'etraction</a>
          </li>

        
          
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
    <a href="{{url('sales/company/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau servant CAS / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           
           @if ($action == 'show')
           <a href="{{url('company/'.$company->code.'/edit')}}" class="btn btn-info text-white"><i class="icon-printer"></i>Modifier les informations de ce servant CAS</a>
           @endif--}}
            <a href="{{url('assistance-agent')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des servants CAS</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  @if ($assistance_agent && $action=='update')
                  <h4 class="card-title">Modifier le servant CAS</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">servant CAS modifié avec succes</h6>
                  </div>
                  @elseif(!$assistance_agent && $action=='create')
                  <h4 class="card-title">Ajouter un servant CAS</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">servant CAS crée avec succes</h6>
                  </div>
                  <span>Ici ce sont les informations de base. Apres avoir cliqué sur "Creer", naviguez sur les differents onglets pour indiquer les information complementaires.</span>
                  @elseif($assistance_agent && $action=='show')

                  <h4 class="card-title">Visualiser le servant CAS</h4>

                  @endif
                  <form id="form-company" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{Auth::user()->code}}" >
                    <input id="action" type="hidden"  value="{{$assistance_agent ? 'edit' : 'create'}}" >
                    <input id="url" type="hidden"  value="{{"/api/assistance-agent".($assistance_agent ? "/".$assistance_agent->code."?_method=PUT" : "")}}" >

                    
                    <input id="assistance_agent" type="hidden" class="form-control" value="{{$assistance_agent->code ?? ''}}" >
                        
                  
                      

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="first_name">Nom</label>
                        <input type="text" name="first_nameame" value="{{$assistance_agent ? $assistance_agent->first_name : ''}}" class=" form-control" id="first_name" placeholder="Ex : NANA" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="last_name">Prenom</label>
                        <input type="text" name="last_name" value="{{$assistance_agent ? $assistance_agent->last_name : ''}}" class=" form-control" id="last_name" placeholder="Ex : Olivier" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                   

                  

                  {{--   <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="signature">Signature</label>
                    
                        <input {{ $readonly }} type="file" name="signature" class=" form-control" id="signature" placeholder="signature" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>  --}}


          <div class="form-group">
            <label for="city">Ville</label>
            <select name="city" class="form-control" id="city" placeholder="">
          
              @forelse ($cities as $city)
          
              @if ($loop->first)
              <option value="" >Selectionnez la ville</option>
              @endif
                 
              <option value="{{$city->code}}" {{ $assistance_agent && $assistance_agent->city && $assistance_agent->city->code == $city->code ? 'selected' : '' }}>{{$city->name}}</option>
              
              @empty
          
              <option value="">Aucune ville disponible</option>
          
              @endforelse
          
            </select>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div> 
          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="email">email</label>
              <input type="email" name="email" value="{{$assistance_agent ? $assistance_agent->email : ''}}" class=" form-control" id="email" placeholder="Ex : aaa@gmal.com" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>
                  
          @if (($action!='show'))
              
          
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ $assistance_agent ? 'Modifier' : 'Ajouter' }}
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

        {{--  wheel_chairs  --}

        <div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="wheel_chair"> 
         
          <div class="row">
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
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->code}}" >
                    <input readonly class="ad_id form-control" id="ad_id" type="hidden" value="{{ $company != null ? $company->code : '' }}" >
                    
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
                        <input type="number" name="number" value="{{$company ? $company ->title : ''}}" class=" form-control" id="number" placeholder="par exemple 2 chambres">
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
                  <h4 class="card-title">Liste des differentes pieces</h4>
                  
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
                              <i class="{{ $wheel_chair->icon }} fa-xl"></i>
                            </div>
                          </td>
                          <td>
                            {{ Str::upper(__($wheel_chair->name)) }}
                          </td>
                          <td>
                            {{ $wheel_chair->pivot->number }}
                          </td>
                          <td>
                             <form>
                            <a id="delete_{{ $wheel_chair->pivot->id }}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
                            <input id="input_{{ $wheel_chair->pivot->id }}" type="hidden" value="{{ $wheel_chair->pivot->id }}"> 
                            <div id="loader_{{ $wheel_chair->pivot->id }}" class="d-none d-flex justify-content-center mt-3">
              
                              <div class="inner-loading dot-flashing"></div>
                              
                              </div> 
                          </form> 
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
                  <h4 class="card-title">Liste des documents administratifs du servant CAS</h4>
                  
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
                  <h4 class="card-title">Ajouter une note sur ce servant CAS</h4>
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
<script src="{{asset('js/enm10F5SaHMUKV.js')}}"></script>




@endsection