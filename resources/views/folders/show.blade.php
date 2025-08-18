@extends('layouts.app')

@section('custom_css')
 <!-- bootstrap 5 stylesheet -->
 <!-- fontawesome 6 stylesheet -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

{{--  <link rel="stylesheet" href="{{asset('plugins/typeahead/typeahead.css')}}"> 
 <link rel="stylesheet" href="{{asset('plugins/LC-Lightbox/css/lc_lightbox.css')}}">
 <link rel="stylesheet" href="{{asset('plugins/LC-Lightbox/skins/light.css')}}"> --}}
 
 <link rel="stylesheet" href="{{asset('plugins/web-pdf-viewer/css/pdfjs-viewer.css')}}">
 <!-- Optional Toolbar Stylesheet -->
 <link rel="stylesheet" href="{{asset('plugins/web-pdf-viewer/css/pdftoolbar.css')}}">
 

 <style>

.covered{
  background-color: rgba(80, 202, 80, 0.308);
}

.uncovered{
  background-color: rgba(202, 80, 80, 0.308);
}

 canvas {
  width: 100%;
  height: 100%;
}
.fullscreen {
  z-index: 9999;
  position: fixed;
  margin: 0 auto;
  width: 90%;
  height: 90%;
  top: 5%;
  left: 5%;
  background-color: #0FF;
}

#file-previews-return .items-preview{

  display: flex;
  flex-direction: column;
  align-items: center;

}
              .twitter-typeahead{
                width: 100% !important;
              }
            

    .col-form-label{
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .dropzone {
        border: dashed 4px #ddd !important ;
        background-color: #f2f6fc;
        border-radius: 15px;
    }

    .dropzone .dropzone-container {
        /*padding: 2rem 0;*/
        width: 100%;
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        color: #8c96a8;
        z-index: 20;
    }

    .dropzone .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        visibility: hidden;
        cursor: pointer;
    }

    .file-icon{
        font-size: 60px;
    }
    .hr-sect {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        margin: 8px 0px;
    }
    .hr-sect:before,
    .hr-sect:after {
        content: "";
        flex-grow: 1;
        background: #ddd;
        height: 1px;
        font-size: 0px;
        line-height: 0px;
        margin: 0px 8px;
    }

     .items-preview{
  max-width: 100%;
  transform: scale(0);
  transition: 0.5s;
}

 .items-preview.animate{
  transform: scale(1);
}
</style>
@endsection

@section('content')

    <div class="row">
      
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        {{-- <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                                aria-controls="overview" aria-selected="true">Importer des dossiers</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences"
                                aria-controls="audiences" role="tab" aria-selected="true">Vue d'ensemble du dossier
                              </a>
                        </li>
                       @if (0)
                       <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab"
                            aria-selected="false">Dossiers rejetés 
                    </li>


                      
                         <li class="nav-item">
            <a class="nav-link" id="digital-tab" data-bs-toggle="tab" href="#digital" role="tab" aria-selected="false">Dossiers dupliqués
              <span class="badge fw-bold text-white text-bg-warning">0</span></a>

            </a>
          </li> 
          
          @endif
         {{--  <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">Mentions du devis</a>
          </li> --}}
                    </ul>
                    <div>
                        <div class="btn-wrapper">
                            <a href="{{ url('extract') }}" class="btn btn-primary text-white"><i
                                class="icon-book"></i>Liste des dossiers</a>
                        </div>
                    </div>
                </div>

                <div>
                  <input type="hidden" id="folder_path" value="{{ url($folder->doc_path) }}">
                  <input type="hidden" id="folder_page" value="{{ $folder->invoice_index }}">
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade " id="overview" role="tabpanel" aria-labelledby="overview">
                        <div id="audience_tab" class="row justify-content-center">
                          <div class="col-md-4 grid-margin stretch-card">
                            <div class="card" >
                                <div class="card-body row justify-content-center">
                                    

                                  <div class="form-group row m-0 align-items-center">
                                    <label for="provider" class="col-lg-3 col-form-label mb-0 fw-bold">Prestataire</label>
                                    <div id="provider-input" class="col-lg-9">
                                      <input type="text" id="provider" name="provider" class="form-control fw-bold" placeholder="{{ __("Indiquez le prestataire ici") }}" aria-label="{{ __("Indiquez le prestataire ici") }}" aria-describedby="email-addon">
                                      @include('layouts.partials._feedback')                     
                                                   
                                    </div>
                                  </div>
                                   
                                
                                                
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 grid-margin stretch-card">
                          <div class="card" >
                             {{--  <div id="file-previews" class="card-body row justify-content-center" style="background-color: #8c96a8">
                                  

                                              
                                  
                              </div> --}}
                          </div>
                      </div>

                        <div id="drag-drop-area" class="col-md-12 grid-margin stretch-card">
                          <div class="card" >
                              <div class="card-body"style="
                              min-height: 50vh;
                              background-color: #8c96a861;
                              display: flex;
                              flex-direction:column;
                              align-items: center;
                              justify-content: center;">
                                  {{-- <h4 class="card-title">Enregistrement d'une facture</h4> --}}
                                  
                                  <div class="container p-0 pb-5 m-0">
                                      <div class="row justify-content-center">
                                       
                                          <div id="dropzone-init" class="col-md-10">
                                              <div class="bg-white p-2 rounded shadow-sm border">
                                               
                                                  <div class="dropzone d-block">
                                                  <form id="form-files" class="pt-3 " novalidate method="post" action="{{url('invoice/parse')}}">
                                                      @csrf

              <input readonly class="url"  type="hidden" value="extract" >
              <input type="hidden" class=" form-control" id="instance_type" value="\App\Models\Extract">
                                                          <label for="files" class="dropzone-container">
                                                              <div class="file-icon d-none"><i class="fa-solid fa-file-circle-plus text-primary"></i></div>
                                                              <div  class="text-center {{-- pt-3 px-5 --}}">
                                                                  <p class="w-80 h5 text-dark fw-bold">Déposez vos fichiers ici pour importer.</p>
                                                                  
                                                             {{-- <div id="save_files_button" class="mt-3">
                                                                          <button id="upload-files" type="button"
                                                                          class=" d-none additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                                                          Uploader les fichier
                                                                      </button> 
                                                              </div>  --}}
                                                          </div> 
                                                          <div class="hr-sectpp mx-2"></div>
                                                          <div id="save_files_button" class="d-none d-flex align-items-center    {{-- mt-3 --}}">
                                                            <button id="upload-files" type="button"
                                                            class=" d-none additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                                            Uploader les fichier
                                                        </button> 
                                                </div> 
                                                          </label>
                         {{--  <input id="files" name="files" multiple="multiple" class="file-input file form-control">--}}

                                                      <input id="files" multiple="multiple" name="files" type="file" class="file-input  form-control" /> 
                                                

                                                      <div class="loader d-flex justify-content-center mt-3">

                                                          <div class="inner-loading dot-flashing"></div>
          
                                                      </div>
                                                
                                                  </form>
                                              </div>
                                              </div>
                                          </div>

                                          <div id="file-previews" class="card-body row justify-content-center">
                                  

                                              
                                  
                                          </div>
                                      </div>
                                  </div>
                                  
                              </div>
                          </div>
                      </div>


                

                        </div>

                    </div>

                    {{--           Techniciens            --}}
                    <div class="tab-pane fade show active" id="audiences" role="tabpanel" aria-labelledby="audiences">

                      {{-- <div class="row">
                        @include('layouts.partials._summary')
                      </div> --}}
 

                        <div class="row">

                        @include('layouts.partials._invoice', ['color'=>'primary'])
                          

                        </div>

                       
                    </div>
                    {{--           End Techniciens        --}}



                    {{--  Documents  --}
          
                 
                    <div class="tab-pane fade" id="demographics" role="tabpanel" aria-labelledby="demographics">

                        <div class="row  ">

                 
                            
                            <div class="col-md-6 grid-margin stretch-card">
                                <div class="card">
                                    <div  id="bad"  class="card-body">
                                        <div class="d-none alert alert-success" role="alert">
                                            <h6 class="alert-heading">Facture rattachée avec succès</h6>
                                        </div>
                                        <form id="form_related_resources" class="pt-3 d-none" novalidate method="post"
                                            action="{{ url('company/create') }}">
                                            @csrf
                                            <input id="token" class="form-control" type="hidden"
                                                value="{{ session('user')->id }}">
                                            <input readonly class="current_resource form-control" id="current_resource"
                                                type="hidden" value="">

                                            <input readonly class="tab" type="hidden" value="demographics">
                                            <input readonly class="list" type="hidden" value="related_resources_table">
                                            <input readonly class="url" type="hidden" value="related_resource" >
                                            
                                            <input readonly class="instance" type="hidden" value="related_resource">

                                            <div class="form-group">
                                                <label for="related_resource">Selectionnez une piece à laquelle cette facture est rattachée<span
                                                        class="text-danger">*</span></label>
                                                <select name="related_resource" class="form-control" id="related_resource">

                                                    @forelse ([] as $related_resource)
                                                     @if ($loop->index == 0)
                                                            <option value="">Selectionnez la piece à rattacher
                                                            </option>
                                                        @endif

                                                        <option value="{{ $related_resource->code }}">
                                                            {{ __($related_resource->identifier .(" (" ).($related_resource->partnerable->social_reason)." )") }} </option>

                                                    @empty

                                                        <option value="">Aucune piece disponible</option>
                                                    @endforelse

                                                </select>
                                                <div class="valid-feedback">
                                                </div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>


                                            <div id="create_button" class="mt-3">
                                                <button id="create" type="button"
                                                    class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                                    Rattacher
                                                </button>
                                            </div>

                                            <div id="loader" class="loader d-none d-flex justify-content-center mt-3">

                                                <div class="inner-loading dot-flashing"></div>

                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                           
                        </div>

                        <div class="row d-none">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Liste des documents dont cette facture est rattachée</h4>
                                        <div class="table-responsive">
                                            <table id="related_resources_table" class="table table-striped">
                                                <thead>
                                                    <tr>

                                                        <th>
                                                            Numero
                                                        </th>

                                                        <th>
                                                            Type de document
                                                        </th>


                                                        <th>
                                                            Action
                                                        </th>

                                                    </tr>
                                                </thead>
                                                @php
                                                    $index = 1;
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
                                        <form id="form_platform" class="pt-3 " novalidate method="post"
                                            action="{{ url('company/create') }}">
                                            @csrf
                                            <input id="token" class="form-control" type="hidden"
                                                value="{{ session('user')->id }}">
                                            <input readonly class="supplier form-control" id="supplier" type="hidden"
                                                value="">

                                            <input readonly class="tab" type="hidden" value="digital">
                                            <input readonly class="list" type="hidden" value="platforms_table">
                                            <input readonly class="url" type="hidden" value="platform/store">
                                            <input readonly class="instance" type="hidden" value="platform">


                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="slug">Plateforme</label>
                                                    <select name="slug" class="form-control form-control"
                                                        id="slug" placeholder="">

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
                                                    <input type="text" name="link" class=" form-control"
                                                        id="link" placeholder="Poste" required>
                                                    <div class="valid-feedback">
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    </div>
                                                </div>
                                            </div>



                                            <div id="create_button" class="mt-3">
                                                <button id="create" type="button" disabled
                                                    class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
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
                                                    $index = 1;
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
          <!-- Button trigger modal -->

          <a href="#" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#exampleModal">Créer un nouvelle mention</a>
         
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Ajouter des mentions à ce devis</h4>
                  <div id="mention_new" class="d-none alert alert-success " role="alert">
                    <h6 class="alert-heading">Mention cree avec succes</h6>
                  </div>

                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Mention ajouté avec succes</h6>
                  </div>
                  <form id="form_note" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="commercial_quote form-control" id="commercial_quote" type="hidden" value="" >
                    
                    <input readonly class="tab"  type="hidden" value="more" >
                    <input readonly class="list"  type="hidden" value="mentions_table" >
                    <input readonly class="url"  type="hidden" value="mention/store" >
                    <input readonly class="instance"  type="hidden" value="mention" >
                    
          
                   

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="mention">Mention</label>
                        <select id="mention" name="mention" class="form-control">
                        
                          @forelse ($mentions as $mention)
                        
                            @if ($loop->first)
                            <option value="" >Selectionnez une mention à ajouter au devis</option>
                            @endif
                               
                            <option value="{{$mention->id}}" >{{$mention->slug}}</option>
                            
                            @empty
                        
                            <option value="">Aucune mention disponible</option>
                        
                            @endforelse
                        </select>
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
                  <h4 class="card-title">Liste des mentions du devis</h4>
              
                  <div class="table-responsive">
                    <table id="mentions_table" class="table table-striped">
                      <thead>
                        <tr>
                       
                          <th>
                            Intitulé
                          </th>

                          <th>
                            Description
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

@section('custom_modal')
  @include('layouts.partials._modal_update')
@endsection

@section('custom_js')


<script src="{{ asset("plugins/typeahead/typeahead.bundle.min.js") }}"></script> 
<script src="{{ asset("plugins/typeahead/typeahead.js") }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
{{-- --}}<script src="{{ asset("js/upd_table_local.js") }}"></script>{{--  --}}
{{-- --}}<script src="{{ asset("js/upd_validate_table_local.js") }}"></script>{{--  --}}

<script src="{{asset('js/GTR345FdscdYHnI.js')}}"></script>


{{-- <script src="{{ asset("js/srdsdvsDFYK.min.js.min.js") }}"></script> --}}
{{--<script src=" {{  asset("plugins/LC-Lightbox/js/lc_lightbox.lite.js") }}"></script>--}}
<script src="{{  asset("plugins/web-pdf-viewer/js/pdfjs-viewer.js") }}"></script>


<script>
  $(function () {
    
    
    $(".invoice_updater").click(function (e) { 
      e.preventDefault();

// or
const myModal = new bootstrap.Modal('#model_update', {});

myModal.show();

var prestation_layout=$(`#${$(this).data('prestation_type')}-layout`).html();

//console.log(prestation_layout);<label for="Prestation">Prestation</label>     ${prestation_layout}

var form_updater_layout = `

<input id="prestation" class="form-control" type="hidden" value="${$(this).data('prestation_code')}" >
<input id="url" class="form-control" type="hidden" value="${$(this).data('url')}" >
<input id="invoice" class="form-control" type="hidden" value="${$(this).data('invoice')}" >
  

   <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="reference">Reference</label>
              <input  type="text" value="${$(this).data('reference')}" name="reference" class=" form-control" id="reference" placeholder="SRHE45RF" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>`;

$("#form_updater").html(form_updater_layout);

//$(`#form_updater #${$(this).data('prestation_type')}`).val('VV3w8');
$(`#form_updater #${$(this).data('prestation_type')}`).val(`${$(this).data('prestation_code')}`);
//console.log(${$(this).data('prestation_code')});



});




    $('body').on('click','.invoice_pic',function(e) {
    // Change Selector Here
   // console.log($(this));
    
    //$(this).toggleClass('fullscreen');

    lc_lightbox('.elem', {
  wrap_class:'lcl_fade_oc',
  gallery :false,
  thumb_attr:'data-lcl-thumb',
  skin:'light',
  // more options here

});

  });

  });
</script>
<script>



        /**
        pdfjsLib.getDocument('/var/www/extract/storage/app/public/extracted_doc/UOWT86jT4XuRDC4.pdf').promise.then(function getPdfHelloWorld(_pdf) {

        var pdf = _pdf;

          console.log(pdf.numPages);
          
        }).catch(function (error) { 
          console.log(error);
        });/**/


        const display_pdf = (urls , tab = "audience") => {

          for (let i = 0; i < urls.length; i++) {
          
            const url = urls[i];
            
        
        var index =0;
              // Using DocumentInitParameters object to load binary data.
              var loadingTask = pdfjsLib.getDocument(url['link']);
              loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');
                
                console.log(pdf);
                
                // Fetch the first page
                var pageNumber = url['page'];
                pdf.getPage(pageNumber).then(function(page) {
              //  console.log('Page loaded');

              //  const filePreviews = document.getElementById('file-previews-return');
              // const filePreviewsContainer = document.getElementById('file-previews-return-container');

              console.log(page);
              
 
            //  console.log($(`#old-file-previews-return-container`));

              //const filePreviewsContainer = $(`#${tab} .file-previews-return-container`)[0];// document.getElementById('file-previews-return-container');

                const filePreviewsContainer = $(`#old-file-previews-return-container`)[0];// document.getElementById('file-previews-return-container');

                console.log(`filePreviewsContainer == ${filePreviewsContainer}`);

                var scale = 5;
        var viewport = page.getViewport({scale: scale});
        // Prepare canvas using PDF page dimensions
        var header = create_header(index , url['link']);
        var canvas = create_canvas(viewport);
        var wrapper =  create_wrapper(viewport , scale ,index);
        var items_container =  create_items_container(viewport , scale ,index);
        //var description =  create_description();

        //console.log(description);
        
        /**/
        wrapper.appendChild(header);
        wrapper.appendChild(canvas);
        //items_container.appendChild(wrapper);
        console.log(items_container);

        //filePreviewsContainer.appendChild(items_container[0]);

        $(items_container[0]).insertBefore(".save-all");

        console.log(items_container.attr('id'));

        let file_previews_return = $(`#${items_container.attr('id')} .file-previews-return`)

        console.log(file_previews_return);

        file_previews_return.append(wrapper);

        var context = canvas.getContext('2d');

        //console.log(wrapper.style.width);

                
              /*	var scale = 5;
                var viewport = page.getViewport({scale: scale});

                // Prepare canvas using PDF page dimensions
                var canvas = $("#pdfViewer")[0];
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;*/



            

                // Render PDF page into canvas context
                var renderContext = {
                  canvasContext: context,
                  viewport: viewport
                };
                var renderTask = page.render(renderContext);
                renderTask.promise.then(function () {
                  console.log('Page rendered');
                

                  /*if ((index+1) == $(".items-preview").length) {
                  console.log('All Pages rendered');
          $("#form-files .loader").addClass('d-none');
          $("#save_files_button").removeClass("d-none");
                  }*/

                  setTimeout(()=> wrapper.classList.add("animate"), index*500);
                // index++;
                  
                // $("#dropzone-init").addClass('d-none');
                });
                });
              }, function (reason) {
                // PDF loading error
                console.error(reason);
              },);

            }
            }

            var link = $("#folder_path").val(),
            page = parseInt($("#folder_page").val());
            display_pdf([{'link' : link , 'page':page}]);



          // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        // The workerSrc property shall be specified.
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';


        </script>
            <script>
            $(".additionnal_details").click(function (e) {

        let form =  $(this).closest("form").attr('id'),
            tab =  $(`#${form} .tab`).val(),
            table =   $(`#${form} .list`).val(),
            instance =   $(`#${form} .instance`).val(),
            url =   $(`#${form} .url`).val();


        add_details_request(form , tab , table , url , instance)

        });

        const create_canvas = (viewport) => {


        const canvas = document.createElement('canvas');



        canvas.width = viewport.width;
        canvas.height = viewport.height;
        canvas.style.width = "100%";
        canvas.style.height = "100%";
              

        return canvas;
            

        //filePreviews.appendChild(preview);

        }

        const create_items_container = (viewport , scale ,index) => {



          const items_container = $(`<div id="container-${index}" class="col-md-12 grid-margin stretch-card ">
            <div class="card" style="background-color: #d3d3d3c2;">
                <div id="good" class="card-body p-1 d-flex flex-column align-items-center justify-content-center">
                  

                  <div  class="row justify-content-center file-previews-return"  style="max-height: 300px;">

        

                  </div>

                
                  
                </div>
            </div>
        </div>`);

        return items_container;



        }


        const create_wrapper = (viewport , scale ,index) => {



            const wrapper = document.createElement('div');
            wrapper.className = 'items-preview col-lg-2 mb-5';
            wrapper.id = 'item-'+index;
          // wrapper.style.marginBottom = '4rem';
            wrapper.style.width = Math.floor(viewport.width/(scale*4.5)) + 'pt';
            wrapper.style.height = Math.floor(viewport.height/(scale*4.5)) + 'pt';
            
            //setTimeout(()=> wrapper.classList.add("animate"), index*500);

            //filePreviews.appendChild(preview);

            return wrapper;

        }

        const create_header = (index , link = "#") => {


          const header = $(`<div class="text-center">
          <a  class="elem invoice_pic show blink me-2" target="_blank" href="${link}"
          title="image 2"
   data-lcl-txt="Description 2"

   data-lcl-author="Author 2"

   data-lcl-thumb="thumb2.jpg"
          "><i
        class="menu-icon mdi mdi-eye"></i></a>

        <!--<a  class="remove-file-from-list blink" data-index="${index}" href="#"><i
        class="menu-icon mdi mdi-close-circle"></i></a>-->
          </div>`)[0];

          
          
          
          //console.log(header);
          

              return header;

        }


        const create_description = (file,pdf) => {


          if (file) {
            
            const description = document.createElement('div');
              description.className = 'shadow card-body p-0 ps-2 fw-bold bg-white';  
              description.style.width = "85%";

              $(description).append(`<p class="p-0 mb-0 card-text">${file.name}</p>`);
              $(description).append(`<p class="p-0 mb-0 card-text">${pdf.numPages} Pages</p>`);

              return description;
          }


        }

        const removeFileFromFileList = (form ,container , index) => {
          const dt = new DataTransfer();
          const input = document.getElementById('files');
          const { files } = input;

          
          
          for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (index !== i) {
              dt.items.add(file); // here you exclude the file. thus removing it.
            }
          }
          
          /* Assigning data transfer object files to the 'input' variable will not write the data transfer files to it because it doesn't have the reference to the element: Instead write, */
          document.getElementById('files').files = dt.files; // Assign the updates list
          
          $(`#${container}  #item-${index}`).remove();

          if ($(`#${container}  .items-preview`).length == 0) {
            
          $(`#${form} #save_files_button`).addClass("d-none");


          }
        // console.log(document.getElementById('files').files  );
          
        }

        $( "#file-previews, #file-previews-return" ).on( "click", ".remove-file-from-list", function(e) {
        // console.log( $( this ).text() );

        var form = 'form-files';
        var container = 'file-previews';
        var index = $(this).data('index');
        
        console.log(`remove file at index ${index}`);
        
          removeFileFromFileList(form, container , index)
        e.preventDefault();
        });



        const addCaseFile = (data) => {

        let card = $("#good"),
        bad = $("#bad"),
        good_ref = ``,
        services = ``,
        prestation_lines = {},
        prestation_total = {};

        display_pdf(data.pdf_links);




        };



       
</script>

<script>
  $(function () {

  


    $(".save-invoice").click(function (e) {

e.preventDefault();

var lines = [],
invoices = {};

// console.log($(this).parents(".forms-invoice").find(".invoice-table"));

var table = $(this).parents(".forms-invoice").find(".invoice-table").attr('id');

// console.log(table);



/*   $.each($(`#${table} tbody tr`), function (indexInArray, row) { 
   
 // console.log(valueOfElement);

      line['description']=$(row).data('description');
      line['quantity']=$(row).data('quantity');
      line['price']=$(row).data('price');

      lines.push(line);

    

  
 });

console.log(lines);*/

$(`#${table} tbody tr`).each(function (index, row) {


     if ($(row).data('description')) {
      
     
     if (invoices[$(row).data('invoice')]) {

     
      
     } else {

      invoices[$(row).data('invoice')] = [];

     }

     invoices[$(row).data('invoice')].push({'description':$(row).data('description'),
        'quantity':$(row).data('quantity'),
        'price':$(row).data('price'),
        'prestation':$(row).data('service'),
      }) ;

    }
    

      
      //return line;


});/**/

// console.log(invoices);


init_save_folder(invoices , $(this));


});




    function init_save_folder(invoices , current_button) {



current_button.addClass("d-none");
current_button.siblings('.loader').removeClass('d-none');

//console.log(current_button.siblings('.loader'));



//console.log(invoices);




  var data_send = {},
  url = '/api/save-invoices';

  data_send['invoices'] = invoices;
  data_send['folder'] = $("#current-folder").val();;


    let onSuccess = function(data, textStatus, xhr) {
        if (data.status) {

        } else {
          $.each(data.errors, function(key, value) {
            $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
            $(` #${key} `).addClass('is-invalid');

          });

        }

      },

      onError = function(xhr) {
        console.log(xhr);
        
        //handleXhrError(xhr)
      },

      onComplete = function(xhr, textStatus) {


        current_button.siblings('.loader').addClass('d-none');


      };



    ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);

    sendAjaxRequest(ajaxRequest);

}


$(".validate-invoice").click(function (e) {

e.preventDefault();

var lines = [],
invoices = {};

// console.log($(this).parents(".forms-invoice").find(".invoice-table"));

var table = $(this).parents(".forms-invoice").find(".invoice-table").attr('id');

// console.log(table);



/*   $.each($(`#${table} tbody tr`), function (indexInArray, row) { 
   
 // console.log(valueOfElement);

      line['description']=$(row).data('description');
      line['quantity']=$(row).data('quantity');
      line['price']=$(row).data('price');

      lines.push(line);

    

  
 });

console.log(lines);*/

$(`#${table} tbody tr`).each(function (index, row) {


     if ($(row).data('description')) {
      
     
     if (invoices[$(row).data('invoice')]) {

     
      
     } else {

      invoices[$(row).data('invoice')] = [];

     }

     console.log("coverage == "+ $(row).data('status'));
     
     invoices[$(row).data('invoice')].push({'description':$(row).data('description'),
        'quantity':$(row).data('quantity'),
        'price':$(row).data('price'),
       // 'prestation':$(row).data('service'),
        'observation':$(row).data('observation'),
        'coverage':$(row).data('status'),
      }) ;

    }
    

      
      //return line;


});/**/

// console.log(invoices);


init_validate_folder(invoices , $(this));


});




function init_validate_folder(invoices , current_button ) {



current_button.addClass("d-none");
current_button.siblings('.loader').removeClass('d-none');

//console.log(current_button.siblings('.loader'));



console.log(invoices);




  var data_send = {},
  url = '/api/validate-invoices';

  data_send['invoices'] = invoices;
  data_send['is_draft'] = 0;
  data_send['folder'] = $("#current-folder").val();


    let onSuccess = function(data, textStatus, xhr) {
        if (data.status) {

        } else {
          $.each(data.errors, function(key, value) {
            $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
            $(` #${key} `).addClass('is-invalid');

          });

        }

      },

      onError = function(xhr) {
        console.log(xhr);
        
        //handleXhrError(xhr)
      },

      onComplete = function(xhr, textStatus) {


        current_button.siblings('.loader').addClass('d-none');


      };



    ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);

    sendAjaxRequest(ajaxRequest);

}









///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


$(".draft-invoice").click(function (e) {

e.preventDefault();

var lines = [],
invoices = {};

// console.log($(this).parents(".forms-invoice").find(".invoice-table"));

var table = $(this).parents(".forms-invoice").find(".invoice-table").attr('id');


$(`#${table} tbody tr`).each(function (index, row) {


     if ($(row).data('description')) {
      
     
     if (invoices[$(row).data('invoice')]) {

     
      
     } else {

      invoices[$(row).data('invoice')] = [];

     }

     invoices[$(row).data('invoice')].push({'description':$(row).data('description'),
        'quantity':$(row).data('quantity'),
        'price':$(row).data('price'),
        'prestation':$(row).data('service'),
      }) ;

    }
    

      
      //return line;


});/**/

// console.log(invoices);


init_draft_folder(invoices , $(this));


});

function init_draft_folder(invoices , current_button ) {



current_button.addClass("d-none");
current_button.siblings('.loader').removeClass('d-none');

//console.log(current_button.siblings('.loader'));



//console.log(invoices);




  var data_send = {},
  url = '/api/draft-invoices';

  data_send['invoices'] = invoices;
  data_send['is_draft'] = 1;
  data_send['folder'] = $("#current-folder").val();


    let onSuccess = function(data, textStatus, xhr) {
        if (data.status) {

        } else {
          $.each(data.errors, function(key, value) {
            $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
            $(` #${key} `).addClass('is-invalid');

          });

        }

      },

      onError = function(xhr) {
        console.log(xhr);
        
        //handleXhrError(xhr)
      },

      onComplete = function(xhr, textStatus) {


        current_button.siblings('.loader').addClass('d-none');


      };



    ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);

    sendAjaxRequest(ajaxRequest);

}
  });
</script>
   
@endsection
