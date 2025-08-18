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
 
 <link rel="stylesheet" href="{{ asset('zen2/css/typeahead/typeahead.css') }}">


 <style>

.twitter-typeahead {
            width: 100% !important;
        }
.covered{
  background-color: rgba(80, 202, 80, 0.308);
}

.uncovered{
  background-color: rgba(202, 80, 80, 0.308);
}

.should_validate{
  background-color: rgba(255, 229, 27, 0.31);
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
.success {
                
  background-color: #97bb9e !important ;
          
}

.reject {
          
  background-color:  #efbdb1 !important;

}
</style>
@endsection

@section('content')

    <div class="row">

      {{--  <div class="col-12">

                          <div class="col-md-5 grid-margin stretch-card">
   
                            @include('layouts.partials._search_template')
                        
                        </div>

                         </div> --}}
      
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
                                aria-controls="audiences" role="tab" aria-selected="true">Vue d'ensemble du dossier <span class=fw-bold>{{ $folder->doc_name }}</span>
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


                          @if ($_SERVER['SERVER_ADDR']=='127.0.0.1')
                          <a target="_blank" href="{{ url('folder/'.$folder->code) }}" class="btn btn-warning text-white"><i
                            class="icon-book"></i>Chaine extraite</a>
                          @else

                          <a target="_blank" href="#" class="btn btn-warning text-white"><i
                            class="icon-book"></i>Pré-valider à l'aide de l'assistant virtuel</a>

                          @endif

                          @if ($_SERVER['SERVER_ADDR']=='127.0.0.1')
                              
                          <a href="{{ url($folder->doc_path ?? $folder->doc_path) }}" target="_blank" class="btn btn-info text-white"><i class="menu-icon mdi mdi-eye"></i></i>Aperçu du fichier pdf</a>
                         
                          @else

                          <a href="{{ url($folder->s3_path ?? $folder->doc_path) }}" target="_blank" class="btn btn-info text-white"><i class="menu-icon mdi mdi-eye"></i></i>Aperçu du fichier pdf</a>


                          @endif

                            <a href="{{ url('slip') }}" class="btn btn-success text-white"><i
                              class="icon-book"></i>Liste des borderaux</a>

                          <a href="{{url('slip/'.$folder->slip->code)}}" class="btn btn-primary text-white"><i
                            class="icon-book"></i>Factures du borderau</a>

                           
                        </div>
                    </div>
                </div>

                <div>
                  @if ($_SERVER['SERVER_ADDR']=='127.0.0.1')

                  <input type="hidden" id="folder_path" value="{{ url($folder->doc_path ?? $folder->doc_path) }}">
                  
                  @else

                  <input type="hidden" id="folder_path" value="{{ url($folder->s3_path ?? $folder->doc_path) }}">
                  
                  @endif
                  
                  <input type="hidden" id="folder_page" value="{{ $folder->invoice_index }}">
                  <input type="hidden" id="folder_name" value="{{ $folder->doc_name }}">
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

                         <div class="col-12 ">

                          <div class="col-md-5 grid-margin stretch-card">
   
                            @include('layouts.partials._search_template')
                        
                        </div>

                         </div>

                          @if (session('user')->isAdministrator())
                              
                          

                          @elseif(session('user')->isExtractor())

 
                          @include('layouts.partials._prestation_checker', ['color'=>'primary'])

                          @elseif(session('user')->isValidator())


                          @include('layouts.partials._prestation_checker', ['color'=>'primary'])


                              
                          @endif
                        
                          

                        </div>


                     
             
                        <div class="row align-items-center py-0">
                         
                          
                          <div class="col-12 text-center">
                            
                      
       @if ($previous)
                                <a href="{{url('extract/'.$previous->code)}}" class="btn btn-primary fw-bold text-white">{{-- <i
                                  class="icon-book"></i> --}}Facture précédente</a>
      
                                  @else

                               {{--    <a href="#" disabled class="btn btn-primary fw-bold text-white"><i
                                    class="icon-book"></i> Facture précédente</a>--}}

                                  @endif

                                  @if ($next)
                                      
                                  <a href="{{url('extract/'.$next->code)}}" class="btn btn-primary fw-bold text-white">Facture suivante
                                    &nbsp;{{-- <i class="mdi mdi-skip-next-circle"></i> --}}</a>
                                    
                                    @endif
                                 
                        

                           </div>
                         </div>
                        
                      

                       
                    </div>
                    {{--           End Techniciens        --}}



                    {{--  Documents  --}
          
                 
                 
    
        
                    {{--  End documents --}}

                    {{--           Identite digitale            --}
                   
                    {{--          End Identite digitale        --}}

                    {{--  Notes  --}

      

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
<script src="{{ asset("zen/js/typeahead.js") }}"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>

@if (session('user') -> isExtractor())
    
{{-- --}}<script src="{{ asset("js/upd_table_local.js") }}"></script>{{--  --}}

@else
    
{{-- --}}<script src="{{ asset("js/upd_validate_table_local.js") }}"></script>{{--  --}}

<script>
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  
  
</script>
@endif


<script src="{{asset('js/GTR345FdscdYHnI.js')}}"></script>


{{-- <script src="{{ asset("js/srdsdvsDFYK.min.js.min.js") }}"></script> --}}
{{--<script src=" {{  asset("plugins/LC-Lightbox/js/lc_lightbox.lite.js") }}"></script>--}}
<script src="{{  asset("plugins/web-pdf-viewer/js/pdfjs-viewer.js") }}"></script>


<script>
  $(function () {


    $("#search").click(function (e) { 

e.preventDefault();

$("#form-search .loader").removeClass("d-none");
$("#form-search #search").addClass("d-none");
console.log("submit");

$("#form-search").submit();

});
    
   


$('.reference_ids').on('click','.invoice_updater',function(e) {
    //$(".invoice_updater").click(function (e) {
      e.preventDefault();

// or
const myModal = new bootstrap.Modal('#model_update', {});

myModal.show();

var prestation_layout=$(`#${$(this).data('prestation_type')}-layout`).html();

//console.log(prestation_layout);<label for="Prestation">Prestation</label>     ${prestation_layout}

if ($(this).data('target') == "invoice") {

  
var form_updater_layout = `<input id="prestation" class="form-control" type="hidden" value="${$(this).data('prestation_code')}" >
<input id="url" class="form-control" type="hidden" value="${$(this).data('url')}" >
<input id="invoice" class="form-control" type="hidden" value="${$(this).data('invoice')}" >


   <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="reference">Reference de : <span class="fw-bold">${$(this).data('prestation')}</span> </label>
              <input  type="text" value="${$(this).data('reference') != $(this).data('undefined_reference') ? $(this).data('reference') : "" }" name="reference" class=" form-control" id="reference" placeholder="Saisissez la reference" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>`;

          $("#form_updater").html(form_updater_layout);

$(`#form_updater #${$(this).data('prestation_type')}`).val(`${$(this).data('prestation_code')}`);

} else {


var form_updater_layout = `<input id="prestation" class="form-control" type="hidden" value="${$(this).data('prestation_code')}" >
<input id="url" class="form-control" type="hidden" value="${$(this).data('url')}" >
<input id="folder" class="form-control" type="hidden" value="${$(this).data('folder')}" >


   <div class="form-group row">
            <div class="col-sm-12 mb-3 mb-sm-0">
              <label for="reference">Reference du dossier</label>
              <input  type="text" value="${$(this).data('reference') != $(this).data('undefined_reference') ? $(this).data('reference') : "" }" name="reference" class=" form-control" id="reference" placeholder="Saisissez la reference du dossier" required>
              <div class="valid-feedback">
              </div>
              <div class="invalid-feedback">
              </div>
            </div>
          </div>`;


          $("#form_updater").html(form_updater_layout);

//$(`#form_updater #${$(this).data('prestation_type')}`).val('VV3w8');
//<input  type="text" value="${$(this).data('reference') != "UNDEFINED REFERENCE" ? $(this).data('reference') : ""}" name="reference" class=" form-control" id="reference" placeholder="SRHE45RF" required>

//$(`#form_updater #${$(this).data('prestation_type')}`).val(`${$(this).data('prestation_code')}`);
  
}





});






  });
</script>

  {{--  --}

<script>



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

            //    console.log(`filePreviewsContainer == ${filePreviewsContainer}`);

                var scale = 5;
        var viewport = page.getViewport({scale: scale});
        // Prepare canvas using PDF page dimensions
        var header = create_header(index , url['link']);
        var canvas = create_canvas(viewport);
        var wrapper =  create_wrapper(viewport , scale ,index);
        var items_container =  create_items_container(viewport , scale ,index);
        var description =  create_description($("#folder_name").val());

      //  console.log(description);
        
        /**/
        wrapper.appendChild(header);
        wrapper.appendChild(canvas);
        wrapper.appendChild(description);
        //items_container.appendChild(wrapper);
      //  console.log(items_container);

        //filePreviewsContainer.appendChild(items_container[0]);

        $(items_container[0]).insertBefore(".save-all");

     //   console.log(items_container.attr('id'));

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
          //  display_pdf([{'link' : link , 'page':page}]);



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



          const description = document.createElement('div');
      description.className = 'shadow card-body p-0 ps-2 fw-bold bg-white';


    //  if (file && pdf) {

        $(description).append(`<p class="p-0 mb-0 card-text">${sanitize_filename(file)}</p>`);
      //  $(description).append(`<p class="p-0 mb-0 card-text">${pdf.numPages} Pages</p>`);

    //  }

      return description;


      /*
          if (file) {
            
            const description = document.createElement('div');
              description.className = 'shadow card-body p-0 ps-2 fw-bold bg-white';  
              description.style.width = "85%";

              $(description).append(`<p class="p-0 mb-0 card-text">${file.name}</p>`);
              $(description).append(`<p class="p-0 mb-0 card-text">${pdf.numPages} Pages</p>`);

              return description;
          }

          */


        }

        const sanitize_filename = (filename) => {

filename = filename.replace(/\.[^/.]+$/, "");
filename = filename.replace(/\s/g, '-');
filename = filename.replace(/\./g, '').toLowerCase();

return filename

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

      //  display_pdf(data.pdf_links);




        };



       
</script>


{{--  --}}


<script>
  $(function () {

  
    $(".save-invoice").click(function (e) {

      let reference_ids = $("#reference_ids li"),
      folder_id = $("#folder_id"),
      is_draft = $(this).data('is-draft');
      has_undefined_folder = false;
      has_undefined_reference = false;


      $(reference_ids).each(function (index, element) {
        // element == this

        if ($(this).hasClass("text-danger")) {
          
            has_undefined_reference = true;

        }
        
      });

      if (folder_id.hasClass("text-danger") && is_draft == 0) {
          
        alert("Vous devez attribuer un numero de dossier avant de l'enregistrer.")

        return null ;

      }

      if (has_undefined_reference && is_draft == 0) {
        
        alert("Vous devez attribuer la/les référence(s) du document avant de l'enregistrer.")
        
        return null ;
        
      }
      
    //  return null

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

if (is_draft == 0) {
  
  current_buttons = $('.save-invoice')

}
else{

  current_buttons = $(this)

}


init_save_folder(invoices , current_buttons , is_draft);


});




    function init_save_folder(invoices , current_buttons, is_draft) {



current_buttons.addClass("d-none");
current_buttons.siblings('.loader').removeClass('d-none');

//console.log(current_buttons.siblings('.loader'));



//console.log(invoices);




  var data_send = {},
  url = '/api/save-invoices';

  data_send['invoices'] = invoices;
  data_send['folder'] = $("#current-folder").val();
  data_send['is_draft'] = is_draft;
  


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



        current_buttons.siblings('.loader').addClass('d-none');

        if (is_draft == "1") {
        current_buttons.removeClass('d-none');
        }


      };



    ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);

    sendAjaxRequest(ajaxRequest);

}


$(".validate-invoice").click(function (e) {

e.preventDefault();

var lines = [],
invoices = {},
is_draft = $(this).data('is-draft');
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

        'suggested_quantity':$(row).data('item-quantity-suggested'),
        'suggested_price':$(row).data('item-price-suggested'),
       // 'prestation':$(row).data('service'),
        'observation':$(row).data('observation'),
        'coverage':$(row).data('status'),
      }) ;
 
    }
    
  /*  $validation_line->suggested_price = $line["price"];
    $validation_line->validated_price = $line["price"];*/

      
      //return line;


});/**/

// console.log(invoices);


if (is_draft == "0") {
  
  current_buttons = $('.validate-invoice')

}
else{

  current_buttons = $('.validate-invoice')

}


init_validate_folder(invoices , current_buttons , is_draft);


});




function init_validate_folder(invoices , current_buttons , is_draft) {



current_buttons.addClass("d-none");
current_buttons.siblings('.loader').removeClass('d-none');

console.log(current_buttons);







  var data_send = {},
  url = '/api/validate-invoices';

  data_send['invoices'] = invoices;
  data_send['is_draft'] = is_draft;
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

        current_buttons.siblings('.loader').addClass('d-none');

        if (is_draft == "1") {
        current_buttons.removeClass('d-none');
        }


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
