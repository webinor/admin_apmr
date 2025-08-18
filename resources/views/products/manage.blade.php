@extends('layouts.app')

@section('custom_css')

 <!-- typeahead stylesheet -->
 <link rel="stylesheet" href="{{asset('plugins/typeahead/typeahead.css')}}"> 
    
 <style>
  .twitter-typeahead{
                width: 100% !important;
              }
 </style>
 @endsection

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations générales </a>
          </li>
       
                 
         {{--  --} <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" aria-controls="audiences" role="tab" aria-selected="false">Parametres d'etraction</a>
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
            {{--@if (Auth::user()->can('create', App\Models\Sales\product::class))--}
    <!-- The current user can update the post... -->
    <a href="{{url('sales/product/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau produit / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
           @if ($action == 'show')
           <a href="{{url('product/'.$product->code.'/edit')}}" class="btn btn-info text-white"><i class="icon-printer"></i>Modifier les informations de ce produit</a>
           @endif
            <a href="{{url('product')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des produits</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  @if ($product && $action=='update')
                  <h4 class="card-title">Modifier le produit</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">produit modifié avec succes</h6>
                  </div>
                  @elseif(!$product && $action=='create')
                  <h4 class="card-title">Ajouter un produit</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">produit crée avec succes</h6>
                  </div>
                  <span>Ici ce sont les informations de base. Apres avoir cliqué sur "Creer", naviguez sur les differents onglets pour indiquer les information complementaires.</span>
                  @elseif($product && $action=='show')

                  <h4 class="card-title">Visualiser le produit</h4>

                  @endif

                  <form id="form-product" class="pt-3 " novalidate method="post" action="{{url('product/create')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{Auth::user()->code}}" >
                    <input id="action" type="hidden"  value="{{$product ? 'edit' : 'create'}}" >
                    <input id="url" type="hidden"  value="{{"/api/product".($product ? "/".$product->code."?_method=PUT" : "")}}" >
                    <input id="typeahead_url" type="hidden"  value="{{ $typeahead_url }}" >


                    @if ($product)
                    
                    <input id="product" type="hidden" class="form-control" value="{{$product->code}}" >
                        
                    @endif
                  
          

                    <div class="form-group row {{ $action == 'create' ? '' : 'd-none' }}">
                      <div class="col-lg-12 mb-3 mb-sm-0">
                        <label for="generation_type" class="col-form-label">Comment souhaitez-vous ajouter ce produit ?</label>
                        <select name="generation_type" class="form-control form-control" id="generation_type" placeholder="" >
                          <option value="">Comment souhaitez-vous ajouter ce produit ?</option>
                          {{-- <option value="M">Creer un nouveau profile</option> --}}
                          <option value="A" {{ $search ? '' : 'selected' }}>Ajouter à partir d'un fichier excel</option>
                          <option value="M" {{ $search || $product ? 'selected' : '' }}>Saisie manuelle</option>

                    
                        </select>              
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    <div class="file-form {{ ( $search || $action == 'show' || $action == 'update')  ? 'd-none' : '' }}">

                      <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <label for="medicines">Importer un fichier excel (.csv)</label>
                            <input type="file" name="medicines" class="file form-control"
                                id="medicines" placeholder="medicines" required>
                            <div class="valid-feedback">
                            </div>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                    </div>
          
                    </div>


                    
          <div class="manual-form {{ ( $search || $action == 'show' || $action == 'update' ) ? '' : 'd-none' }}">
                      
         
          {{-- --}}<div class="form-group">
            <label for="product_type">Type </label>
            <select  {{ $disabled }}  name="product_type" class="form-control" id="product_type" placeholder="">
          
              @forelse ($product_types as $product_type)
          
              
              @if ($loop->first)
              <option value="" >Indiquez le type de produit</option>
              @endif
                  
              <option value="{{$product_type->code}}"
                 {{ $product && $product->product_type->code == $product_type->code ? 'selected' : ($search && $search->invoice_line && $search->invoice_line->invoice && $search->invoice_line->invoice->prestationable && 
                 $search->invoice_line->invoice->prestationable->code == $product_type->code ? 'selected' : '' ) }}>{{$product_type->get_utility()}}</option>
              
              @empty
          
              <option>Aucune categorie</option>
          
              @endforelse
          
            </select>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div>


          <div class="form-group">
            <label for="family">Famille</label>
            <select  {{ $disabled }}  name="family" class="form-control" id="family" placeholder="">
          
              @forelse ($families as $family)
          
              
              @if ($loop->first)
              <option value="" >Indiquez la famille de ce produit</option>
              @endif
                  
              <option value="{{$family->code}}"
                 {{ $product && $product->family->code == $family->code ? 'selected' :  ''  }}>{{$family->get_name()}}</option>
              
              @empty
          
              <option>Aucune Famille disponible</option>
          
              @endforelse
          
            </select>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div>
      
          
          {{----}}


         {{--  <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="dci">DCI </label>
              <input {{ $readonly }} type="text" value="{{ $product ? $product->dci : '' }}" name="dci" class="autocomplete form-control" id="dci" placeholder="Ex : Paracetamol" required>
              @include('layouts.partials._feedback')
            </div>
          </div> --}}


          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="name">Nom commercial</label>
              <input {{ $readonly }} type="text" value="{{ $product ? $product->get_name() : ($search ? $search->query : '') }}" name="name" class=" form-control" id="name" placeholder="Malacure" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>


         {{-- -}} <div class="form-group">
            <label for="therapeutic_class">Classe Therapeutique</label>
            <select  {{ $disabled }}  name="therapeutic_class" class="form-control" id="therapeutic_class" placeholder="">
          
              @forelse ($therapeutic_classes as $therapeutic_class)
          
              @if ($loop->first)
              <option value="" >Selectionnez la Classe Therapeutique</option>
              @endif
                 
              <option value="{{$therapeutic_class->code}}" {{ $product && $product->therapeutic_class->code == $therapeutic_class->code ? 'selected' : '' }}>{{$therapeutic_class->get_name()}}</option>
              
              @empty
          
              <option>Aucune Classe Therapeutique</option>
          
              @endforelse
          
            </select>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div>{{----}}



         

          
          <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="price">prix </label>
              <input {{ $readonly }} type="number" value="{{ $product ? $product->get_price() : '' }}" name="price" class=" form-control" id="price" placeholder="Ex: 2500" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>

          {{--  --}}<div class="form-group">
            <label for="coverage">Couverture</label>
            <select  {{ $disabled }}  name="coverage" class="form-control" id="coverage" placeholder="">
          
              <option value="" >{{__("Indiquez la couverture")}}</option>
              <option value="0" {{ $product && $product->coverage == 0 ? 'selected' : '' }}>{{__("Non Couvert")}}</option>
              <option value="1" {{ $product && $product->coverage == 1 ? 'selected' : '' }}>{{__("Couvert")}}</option>
              <option value="2" {{ $product && $product->coverage == 2 ? 'selected' : '' }}>{{__("Couvert Sous Confirmation")}}</option>

          
            </select>
            <div class="valid-feedback">
            </div>
            <div class="invalid-feedback">
            </div>
          </div>{{----}}

        </div>
                  
          @if (($action!='show'))
              
          
                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ $product ? 'Modifier' : 'Enregistrer' }}
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
        
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">JSON extract</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Parametres d'extraction modifiés avec succes</h6>
                  </div>
                  <form id="form-extraction-" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="product form-control" id="product" type="hidden" value="{{ $product ? $product->code : '' }}" >
                    
                    <input readonly class="tab"  type="hidden" value="audiences" >
                    <input readonly class="list"  type="hidden" value="extraction-settings_table" >
                    <input readonly class="url"  type="hidden" value="extraction-settings" >
                    <input readonly class="instance"  type="hidden" value="extraction-setting" >
                  
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="json_extract_settings">Parametres d'extraction<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 55rem;" name="json_extract_settings" placeholder="Parametres d'extraction" value="aaa" class=" form-control" id="json_extract_settings" cols="30" rows="5">{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->extract_params }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>



                    
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Modifier les Parametres d'extraction</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Parametres d'extraction modifiés avec succes</h6>
                  </div>
                  <form id="form-extraction-setting" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="product form-control" id="product" type="hidden" value="{{ $product ? $product->code : '' }}" >
                    
                    <input readonly class="tab"  type="hidden" value="audiences" >
                    <input readonly class="list"  type="hidden" value="extraction-settings_table" >
                    <input readonly class="url"  type="hidden" value="extraction-settings" >
                    <input readonly class="instance"  type="hidden" value="extraction-setting" >
                  
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="extract_params">extract_params<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="extract_params" placeholder="Parametres d'extraction"  class="param form-control" id="extract_params" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->extract_params }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->extract_params }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="folder_matcher">folder_matcher<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="folder_matcher" placeholder="Parametres d'extraction"  class="param form-control" id="folder_matcher" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->folder_matcher }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->folder_matcher }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="reference_matcher">reference_matcher<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="reference_matcher" placeholder="Parametres d'extraction"  class="param form-control" id="reference_matcher" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->reference_matcher }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->reference_matcher }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="service_matcher">service_matcher<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="service_matcher" placeholder="Parametres d'extraction"  class="param form-control" id="service_matcher" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->service_matcher }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->service_matcher }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="service_switcher">service_switcher<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="service_switcher" placeholder="Parametres d'extraction"  class="param form-control" id="service_switcher" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->service_switcher }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->service_switcher }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="service_mask">service_mask<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="service_mask" placeholder="Parametres d'extraction"  class="param form-control" id="service_mask" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->service_mask }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->service_mask }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="pure_matcher">pure_matcher<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="pure_matcher" placeholder="Parametres d'extraction"  class="param form-control" id="pure_matcher" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->pure_matcher }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->pure_matcher }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="items_layout">items_layout<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 2.5rem;" name="items_layout" placeholder="Parametres d'extraction"  class="param form-control" id="items_layout" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->items_layout }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->items_layout }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="items_mask">items_mask<span class="text-danger">*</span></label>
                        <textarea {{ $readonly }}  style="min-height: 6.5rem;" name="items_mask" placeholder="Parametres d'extraction"  class="param form-control" id="items_mask" cols="30" rows="5" value="{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->items_mask }}" >{{ !$product || !$product->extraction_setting ? '' : $product->extraction_setting->items_mask }}</textarea>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    



                    @if ($action != 'show')

                  
                    <div id="add_extraction-setting-button" class="mt-3">
                      <button  id="add_extraction-setting" type="button" {{ $disabled }} class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Modifier les parametres d'extraction
                      </button>
                    </div> 
                    
                    
                <div class="loader d-none d-flex justify-content-center mt-3">

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
                  <h4 class="card-title">Liste des pieces lié à ce produit</h4>
                  
                  <div class="table-responsive">
                    <table id="extraction-settings_table" class="table table-striped">
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
                        
                        @if ($product = [] && $action == 'show')
                        @foreach ($product->resources as $resource)
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
                          @endforeach ($product->file)
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
                    <input readonly class="product form-control" id="product" type="hidden" value="{{ $product != null ? $product->code : '' }}" >
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
                  <h4 class="card-title">Liste des documents administratifs du produit</h4>
                  
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
                        @if ($product)
                        @foreach ($product->documents as $file)
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
                          @endforeach ($product->file)
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
                    <input readonly class="product form-control" id="product" type="hidden" value="" >
                    
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
                  <h4 class="card-title">Ajouter une note sur ce produit</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading"> Note cree avec succes</h6>
                  </div>
                  <form id="form_note" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="product form-control" id="product" type="hidden" value="" >
                    
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

{{-- <script src="{{ asset("plugins/typeahead/typeahead.bundle.min.js") }}"></script> 
<script src="{{ asset("plugins/typeahead/typeahead.js") }}"></script>  --}}
<script src="{{asset('js/GFYJiuRsCgHMUKV.js')}}"></script>




@endsection