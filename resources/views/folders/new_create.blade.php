@extends('layouts.app')

@section('custom_css')
 <!-- fontawesome 6 stylesheet -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <!-- typeahead stylesheet -->
 <link rel="stylesheet" href="{{asset('plugins/typeahead/typeahead.css')}}"> 
            
<style>


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
  margin-bottom: 7rem !important;
  transform: scale(0);
  transition: 0.5s;
}

 .items-preview.animate{
  transform: scale(1);
}

.dropzone-transform{
  min-height: 50vh;
  transition: 1.5s;
}

#select-files{
  transition: .5s;
}

.dropzone-transform.animate{
  
}

/*#file-previews*/ .progress-bar {
    width: 0%;
    height: 5px;
    background-color: 
#4CAF50;
    margin-top: 5px;
    border-radius: 12px;
    text-align: center;
    color: 
#fff;
}
</style>
@endsection

@section('content')

<input id="folder-test"  type="hidden" value=''>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
    <div class="toast-header">
      {{-- <img src="..." class="rounded me-2" alt="..."> --}}
      <strong class="me-auto">Importation en cours...</strong>
      <small>11 mins ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="d-flex  justify-content-start">
      <div class="toast-body">
        Dossiers importés :  
      </div>
      <div class="spinner-import spinner-border text-primary align-self-center" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>

  <div id="extracting-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
    <div id="global-progress-bar" class="text-center progress-bar mt-0"></div>
    <div class="toast-header">
      {{-- <img src="..." class="rounded me-2" alt="..."> --}}
      <strong class="me-auto text-success">Traitement en cours</strong>
      <small class="text-muted">just now</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="d-flex  justify-content-start">
      <div class="toast-body fw-bold">
        Dossiers traités : 
      </div>
      <div class="spinner-extract spinner-border text-primary align-self-center" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</div>

    <div class="row">
      
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" tab-id="overview" role="tab"
                                aria-controls="overview" aria-selected="true">Importer des dossiers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" tab-id="audiences"
                                aria-controls="audiences" role="tab" aria-selected="false">Nouveaux Dossiers
                                <span id="badge-success" class="badge fw-bold text-bg-success">0</span>
                              </a>
                        </li>
                       @if (1)
                       <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" tab-id="demographics" role="tab"
                        aria-controls="demographics"  aria-selected="false">Dossiers rejetés 
                            <span id="badge-danger" class="badge fw-bold text-white text-bg-danger">0</span></a>
                    </li>


                       @endif
                         <li class="nav-item">
            <a class="nav-link" id="digital-tab" data-bs-toggle="tab" href="#digital" tab-id="digital"
            aria-controls="digital" role="tab" aria-selected="false">Dossiers dupliqués
              <span id="badge-warning" class="badge fw-bold text-white text-bg-warning">0</span></a>

            </a>
          </li>
     
                    </ul>
                    <div>
                        <div class="btn-wrapper">
                            <a href="{{ url('extract') }}" class="btn btn-primary text-white"><i
                                class="icon-book"></i>Liste des dossiers</a>
                        </div>
                    </div>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div id="audience_tab" class="row justify-content-center">
                          <div class="col-md-5 grid-margin stretch-card">
                            <div class="card" >


                                <div class="card-body row justify-content-center">
                                    

                                  <div class="form-group row m-2 align-items-center">
                                    <label for="slip" class="col-lg-4 col-form-label mb-0 fw-bold">Numero de bordereau</label>
                                    <div id="slip-input" class="col-lg-7">
                                      <input type="text" id="slip" name="slip" value="{{-- RDF56YHE --}}" class="form-control fw-bold" placeholder="{{ __("Indiquez le numero de bordereau ici") }}" aria-label="{{ __("Indiquez le numero de bordereau ici") }}" aria-describedby="email-addon">
                                        
                                      <div class="valid-feedback  fw-bold"  style="display: block">
                                      </div>
                                      <div  id="login-feedback" class="invalid-feedback feedback-slip fw-bold" style="display: block">
                                      </div>                  
                                                 
                                    </div>
                                  </div>
                                  <div class="form-group row m-0 align-items-center">
                                    <label for="provider" class="col-lg-4 col-form-label mb-0 fw-bold">Prestataire</label>
                                    <div id="provider-input" class="col-lg-7">
                                      <input type="text" id="provider" name="provider" {{--  value="Fondation Ad Lucem" --}} value="" {{-- LABORATOIRE BIOMEDICAM --}} class="form-control fw-bold" placeholder="{{ __("Indiquez le prestataire ici") }}" aria-label="{{ __("Indiquez le prestataire ici") }}" aria-describedby="email-addon">
                                       
                                      <div class="valid-feedback fw-bold"  style="display: block">
                                      </div>
                                      <div  id="login-feedback" class="invalid-feedback feedback-provider fw-bold" style="display: block">
                                      </div>                   
                                                   
                                    </div>
                                  </div>
                                   
                                {{--  --}  <div>
                                   Evolution : <span id="upload"></span>
                                  </div>{{--  --}}
                                
                                                
                                    
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
                                               
                                                  <div class="{{--style="min-height: 50vh;" --}} h-50 d-flex justify-content-center align-items-center dropzone d-block dropzone-transform">
                                                  <form id="form-files" class="pt-3 " novalidate method="post" action="{{url('invoice/parse')}}">
                                                      @csrf
                                                      
              <input readonly id="url-export" class="url-export"  type="hidden" value="export" >
              <input readonly id="url-extract" class="url-extract"  type="hidden" value="extract" >
              <input readonly id="import-completed" value="0"  type="hidden"  >
              <input readonly id="extract-completed" value="0"  type="hidden"  >
              <input readonly id="token" value="{{ session("user")->code }}"  type="hidden"  >
              <input type="hidden" class=" form-control" id="instance_type" value="\App\Models\Extract">
                                                          <label for="files" class="dropzone-container">
                                                              <div class="file-icon d-none"><i class="fa-solid fa-file-circle-plus text-primary"></i></div>
                                                              <div  class="text-center {{-- pt-3 px-5 --}}">
                                                                  <p class="w-80 h5 text-dark fw-bold">Déposez vos fichiers ou cliquez pour selectionner des fichiers.</p>
                                                                  
                                                              <div class="mt-3 ">
                                                                          <button id="select-files" type="button"
                                                                          class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                                                          Selectionner des fichiers
                                                                      </button> 
                                                              </div> {{-- --}}
                                                          </div> 
                                                          <div class="hr-sectpp mx-2"></div>
                                                          <div id="save_files_button" class="d-none d-flex align-items-center    {{-- mt-3 --}}">
                                                            <button id="upload-files" type="button"
                                                            class=" d-none additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                                            Importer les fichiers
                                                        </button> 
                                                </div> 


                                                <div id="extract_files_button" class="d-none d-flex align-items-center    {{-- mt-3 --}}">
                                                  <button id="extract-files" type="button"
                                                  class=" text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                                  Demarrer l'extraction
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

                    {{--           Extract succeded            --}}

                    <div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="audiences">

                        <div class="row">

                        @include('layouts.partials._invoice' , ['color'=>'success'])
                          

                        </div>

                       
                    </div>
                    {{--           End Extract succeded        --}}



                    {{--           Extract Failed            --}}

                    <div class="tab-pane fade" id="demographics" role="tabpanel" aria-labelledby="demographics">

                      <div class="row">

                      @include('layouts.partials._invoice' , ['color'=>'danger'])
                        

                      </div>

                     
                  </div>

                    {{--           End Extract Failed        --}}



                    {{--           Extract Duplicated        --}}
          
                 
                    <div class="tab-pane fade" id="digital" role="tabpanel" aria-labelledby="digital">

                        <div class="row">

                        @include('layouts.partials._invoice' , ['color'=>'warning'])
                          

                        </div>

                       
                    </div>
                 
    
        
                    {{--           End Extract Duplicated        --}}

                  
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_modal')
@endsection

@section('custom_js')

<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{ asset("plugins/typeahead/typeahead.bundle.min.js") }}"></script> 
<script src="{{ asset("plugins/typeahead/typeahead.js") }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script src="{{ asset("js/upd_table_local.js") }}"></script>
{{-- --}<script src="{{ asset("js/srdsdvsDFYK.min.js") }}"></script>  {{--  --}}

<script>

      // Loaded via <script> tag, create shortcut to access PDF.js exports.
      var

      files_global = [],

      my_data_files = [],

      pdfjsLib = window['pdfjs-dist/build/pdf'];
      // The workerSrc property shall be specified.
      pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';






      /////////////////////////////////////////  Select files \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\



      $("#select-files").click(function(e) {

      e.preventDefault();

      $('#files').click();

      });

      const dragDropArea = document.getElementById('drag-drop-area');
      dragDropArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      dragDropArea.classList.add('drag-over');
      });
      dragDropArea.addEventListener('dragleave', () => {
      dragDropArea.classList.remove('drag-over');
      });
      dragDropArea.addEventListener('drop', (e) => {
      e.preventDefault();
      dragDropArea.classList.remove('drag-over');
      const files = e.dataTransfer.files;
      handleDroppedFiles(files);
      });

      function handleDroppedFiles(files) {
      //console.log('should handle');
      // Handle dropped files here
      displayPreview(files);

      $(`.file-input`)[0].files = files;
      //fill_global_files (files);
      }




      $("#files").on("change", function(e) {

      const files = e.target.files;
      displayPreview(files);

      });



      $(".additionnal_details").click(function(e) {

      let form = $(this).closest("form").attr('id'),
        url = $(`#${form} #url-export`).val();


      uploadFolderRequest(form, url);

      });




      $("#file-previews, #file-previews-return").on("click", ".remove-file-from-list", function(e) {
      e.preventDefault();

      var form = 'form-files',
        container = 'file-previews',
        index = $(this).data('index'),
        parent = $(this).closest(".items-preview");

      removeFileFromFileList(form, container, index, parent)


      });




      /////////////////////////////////////////////// Preview begin \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


      const create_items_container = (viewport, scale, index) => {



      const items_container = $(`<div id="container-${index}" class="col-md-12 grid-margin stretch-card"  >
      <div class="card" style="background-color: #d3d3d3c2;">
        <div id="good" class="card-body p-1 d-flex flex-column align-items-center justify-content-center">
          <div  class="row justify-content-center file-previews-return"  style="max-height: 300px;">
          </div>
        </div>
      </div>
      </div>`);

      return items_container;



      }


      const create_canvas = (viewport) => {
      const canvas = document.createElement('canvas');
      canvas.width = viewport.width;
      canvas.height = viewport.height;
      canvas.style.width = "100%";
      canvas.style.height = "100%";
      return canvas;
      }


      const create_wrapper = (viewport, scale, index) => {

      const wrapper = document.createElement('div');
      wrapper.className = 'items-preview col-lg-2 mb-5';
      wrapper.style.width = Math.floor(viewport.width / (scale * 4.5)) + 'pt';
      wrapper.style.height = Math.floor(viewport.height / (scale * 4.5)) + 'pt';

      //setTimeout(()=> wrapper.classList.add("animate"), index*500);

      return wrapper;

      }

      const create_header = (index, file, can_delete = true, folder) => {


      const header = $(`<div class ="d-flex justify-content-center">
      <div class=" upload-option-header ${!can_delete ? `folder-fetcher` : ``} text-center" ${!can_delete ? `data-folder="${folder}"` : ``}>
      <a  class="${can_delete ? `d-none me-2` : ``} show blink " href="#"><i
      class="menu-icon mdi mdi-eye"></i></a>

      ${can_delete ? `<a  class="remove-file-from-list blink" data-index="${index}" href="#"><i
      class="menu-icon mdi mdi-close-circle"></i></a>` : ''}
      </div>
      <div id="upload-complete-icon-${file !=null ? sanitize_filename(file.name): index}" class="f-flex d-none upload-complete-icon   text-center ">

      <a class="show blink me-1" href="#"><i class="menu-icon mdi mdi-check-circle"></i></a>
      <span id="upload-complete-duration-${file !=null ? sanitize_filename(file.name): index}" ></span>

      </div>

      <div id="upload-option-loader-${file !=null ? sanitize_filename(file.name): index}" class="d-none upload-option-loader spinner-border spinner-border-sm text-primary align-self-center" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
      </div>
      `)[0];

      return header;

      }

      const create_progress_bar = (file) => {

      var file_name = sanitize_filename(file.name);

      const progress_bar = $(`<div id="progress-bar-${file_name}" class="text-center progress-bar"></div>`)[0];

      return progress_bar;

      }


      const create_description = (file, pdf) => {


      const description = document.createElement('div');
      description.className = 'shadow card-body p-0 ps-2 fw-bold bg-white';


      if (file && pdf) {

        $(description).append(`<p class="p-0 mb-0 card-text">${sanitize_filename(file.name)}</p>`);
        $(description).append(`<p class="p-0 mb-0 card-text">${pdf.numPages} Pages</p>`);

      }

      return description;

      }


      $("body").on('click', '.folder-fetcher', function(e) {
      e.preventDefault();

      let folder = $(this).data('folder');


      let data_send = {
          "folder": folder
        },
        url = `/api/fetch-folder/${folder}`,
        processData = true,
        contentType = true,
        method = "GET";


      let onSuccess = function(data, textStatus, xhr) {
          if (data.status) {



            addCaseFile(data.data.folder, $(".tab-pane.active").attr("id"), null);




          } else {



          }

        },

        onError = function(xhr) {

          //handleXhrError(xhr)



        },

        onComplete = function(xhr, textStatus) {




        };



      ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete, method, processData, contentType);



      sendAjaxRequest(ajaxRequest);








      });


      const displayPreview = (files) => {


      $(".dropzone-transform")[0].style.minHeight = '0vh';

      //$(".dropzone-transform").addClass("animate");
      //setTimeout(() => wrapper.classList.add("animate"), index * 500);

      $("#select-files").addClass("d-none");
      $("#save_files_button").removeClass("d-none");
      $("#form-files .loader").removeClass('d-none');

      var start_index = $(".items-preview").length;

      var index = 0 + start_index;

      for (const file of files) {

        if (file.type == "application/pdf") {

          var fileReader = new FileReader();
          fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
              data: pdfData
            });
            loadingTask.promise.then(function(pdf) {

              // Fetch the first page
              var pageNumber = 1;
              pdf.getPage(pageNumber).then(function(page) {

                const filePreviews = document.getElementById('file-previews');

                var scale = 5,
                  viewport = page.getViewport({
                    scale: scale
                  }),
                  // Prepare canvas using PDF page dimensions
                  header = create_header(index, file),
                  progress_bar = create_progress_bar(file),
                  canvas = create_canvas(viewport),
                  wrapper = create_wrapper(viewport, scale, index),
                  description = create_description(file, pdf);

                wrapper.appendChild(header);
                wrapper.appendChild(progress_bar);
                wrapper.appendChild(canvas);
                wrapper.appendChild(description);

                filePreviews.appendChild(wrapper);


                if ((index + 1) == $(".items-preview").length) {
                  //console.log('All Pages rendered');
                  $("#form-files .loader").addClass('d-none');
                  $("#save_files_button").removeClass("d-none");
                }

                setTimeout(() => wrapper.classList.add("animate"), index * 500);

                index++;
                var context = canvas.getContext('2d');

                // Render PDF page into canvas context
                var renderContext = {
                  canvasContext: context,
                  viewport: viewport
                };
                var renderTask = page.render(renderContext);
                renderTask.promise.then(function() {


                });
              });
            }, function(reason) {
              // PDF loading error
              console.error(reason);
            }, );
          };
          fileReader.readAsArrayBuffer(file);
        }

      };



      }



      const removeFileFromFileList = (form, container, index, parent) => {
      const dt = new DataTransfer();
      const input = document.getElementById('files');
      const {
        files
      } = input;

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (index !== i) {
          dt.items.add(file); // here you exclude the file. thus removing it.
        }
      }

      /* Assigning data transfer object files to the 'input' variable will not write the data transfer files to it because it doesn't have the reference to the element: Instead write, */
      document.getElementById('files').files = dt.files; // Assign the updates list

      parent.remove();

      if ($(`#${container}  .items-preview`).length == 0) {

        $(`#${form} #save_files_button`).addClass("d-none");
        $(`#${form} #extract_files_button`).addClass("d-none");
        $("#select-files").removeClass("d-none");
        $(".dropzone-transform")[0].style.minHeight = '50vh';



      }


      }

      ////////////////////////////////////////  Preview end Begin \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\



      ////////////////////////////////////////  Import Begin \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
      const check_form = (form) => {

      var is_ok = true;

      $("#slip").removeClass('is-invalid');
      $("#provider").removeClass('is-invalid');
      $('.feedback-slip').text("");
      $('.feedback-provider').text("");


      if ($('#slip').val() == '') {

        is_ok = false;

        $('#slip').addClass('is-invalid');

        $('.feedback-slip').text("Vous devez indiquer le numero de bordereau");

      }
      if ($('#provider').val() == '') {

        is_ok = false;

        $('.feedback-provider').text("Vous devez indiquer le prestaire");

        $('#provider').addClass('is-invalid');

      }

      return is_ok;

      }

      const uploadFolderRequest = (form, url) => {



      if (!check_form()) {
        return false
      }

      $(`#${form} #extract_files_button`).addClass("d-none");

      $(`#${form} #save_files_button`).addClass("d-none");
      //$(`#${form} .loader`).toggleClass("d-none");

      let data_send,
        final_url = `/api/${url}`,
        processData = false,
        contentType = false,
        method = "POST";

      $(`#${form} #file`).removeClass('is-invalid');

      uploadFiles(form, final_url, method);

      }



      function uploadFiles(form, final_url, method) {


      $(".upload-option-header").addClass('d-none');
      $("#import-completed").val(0); //we reset the file to upload
      $("#extract-completed").val(0); //we reset the file to extract
      const toastLiveExample = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(toastLiveExample)

      toast.show();
      $('#liveToast .spinner-import').removeClass('d-none');



      var files = $(`#${form} .file-input`)[0].files,
        processData = false,
        contentType = false;



      //console.log(`send ${files.length} requests`);

      for (var i = 0; i < files.length; i++) {




        var data_send = new FormData();

        data_send = getInputValues(form, data_send, 'form-control', false);
        data_send.append('provider', $(`#provider`).val());
        data_send.append('slip', $(`#slip`).val());
        data_send.append('files_to_upload', files.length);


        var allowedExtensions = ['.pdf'],
          fileExtension = files[i].name.substring(files[i].name.lastIndexOf('.')).toLowerCase();

        if (allowedExtensions.includes(fileExtension)) {

          var sanitized_name = sanitize_filename(files[i].name);
          data_send.append('files[]', files[i], sanitized_name);
          data_send.append('progress_id', `progress-bar-${sanitize_filename(files[i].name)}`);
          data_send.append('loader_id', `upload-option-loader-${sanitize_filename(files[i].name)}`);

          let onSuccess = function(data, textStatus, xhr) {
              if (data.status) {




                //  console.log(data);
                my_data_files.push(data.data.file)

                /**
                $(`#overview .alert-success`).removeClass("d-none");
                $(`#overview #save_files_button`).addClass("d-none");


                // addCaseFile(data, "audiences");

                $(`#${form}`)[0].reset();

                $(`#overview .items-preview`).remove();
                /**/

              } else {
                $.each(data.errors, function(key, value) {
                  $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
                  $(` #${key} `).addClass('is-invalid');

                });

              }

            },

            onError = function(xhr) {

              //handleXhrError(xhr)

              let status = xhr.status;



              switch (status) {
                case 422:

                  //console.log(xhr.responseJSON.errors);
                  $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#provider-input .invalid-feedback').text(value[0]);
                    $(`#${key}`).addClass('is-invalid');

                  });


                  break;

                default:
                  break;
              }


            },

            onComplete = function(xhr, textStatus) {

              // $(`#${form} #save_files_button`).toggleClass("d-none");
              // $(`#${form} .loader`).toggleClass("d-none");


            };



          ajaxRequest = constructUploadAjaxRequest(final_url, data_send, onSuccess, onError, onComplete, method, processData, contentType, 'json', toast);


          // //console.log(ajaxRequest);

          sendAjaxRequest(ajaxRequest);

        } else {
          alert('Invalid file type: ' + fileExtension);
        }
      }
      }


      const sanitize_filename = (filename) => {

      filename = filename.replace(/\.[^/.]+$/, "");
      filename = filename.replace(/\s/g, '-');
      filename = filename.replace(/\./g, '').toLowerCase();

      return filename

      }


      const display_pdf = (urls, tab = "audiences", folder) => {

      for (let i = 0; i < urls.length; i++) {

        const url = urls[i];


        var index = 0;
        // Using DocumentInitParameters object to load binary data.
        var loadingTask = pdfjsLib.getDocument(url['link']);
        loadingTask.promise.then(function(pdf) {
          console.log('PDF loaded');

          console.log(pdf);

          // Fetch the first page
          var pageNumber = url['page'];
          pdf.getPage(pageNumber).then(function(page) {
            console.log('Page loaded');

            //  const filePreviews = document.getElementById('file-previews-return');
            //  const filePreviewsContainer = document.getElementById('file-previews-return-container');
            const filePreviewsContainer = $(`#${tab} .file-previews-return-container`)[0]; // document.getElementById('file-previews-return-container');

            console.log(`filePreviewsContainer == ${filePreviewsContainer}`);


            var scale = 5;
            var viewport = page.getViewport({
              scale: scale
            });
            // Prepare canvas using PDF page dimensions
            var header = create_header(index, null, false, folder);
            var canvas = create_canvas(viewport);
            var wrapper = create_wrapper(viewport, scale, index);
            var items_container = create_items_container(viewport, scale, index);
            //var description =  create_description();

            //console.log(description);

            /**/
            wrapper.appendChild(header);
            wrapper.appendChild(canvas);
            //items_container.appendChild(wrapper);
            console.log(items_container);

            //filePreviewsContainer.appendChild(items_container[0]);
            $(items_container[0]).insertBefore(`#${tab} .save-all`);
            console.log(items_container.attr('id'));

            //let file_previews_return = $(`#${items_container.attr('id')} .file-previews-return`)
            let file_previews_return = $(`#${tab} .file-previews-return`)

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
            renderTask.promise.then(function() {
              console.log('Page rendered');


              /*if ((index+1) == $(".items-preview").length) {
            console.log('All Pages rendered');
      $("#form-files .loader").addClass('d-none');
      $("#save_files_button").removeClass("d-none");
            }*/

              setTimeout(() => wrapper.classList.add("animate"), index * 500);
              // index++;

              // $("#dropzone-init").addClass('d-none');
            });
          });
        }, function(reason) {
          // PDF loading error
          console.error(reason);
        }, );

      }
      }
      //////////////////////////////////////////////////// After import end  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

      $("#extract_files_button").click(function(e) {
      e.preventDefault();

      $("#extract_files_button").addClass("d-none");

      const toastLiveExample = document.getElementById('liveToast')
      const toast = new bootstrap.Toast(toastLiveExample)

      //  toast.show();

      toast.hide();

      //  console.log(my_data_files);



      //	$(`#${my_data_files[0].loader}`).removeClass('d-none');

      //$(".upload-option-header").removeClass('d-none');

      $(".upload-option-loader").removeClass('d-none');


      var completed = parseInt($("#import-completed").val());

      const extracting_toast = document.getElementById('extracting-toast');

      const extracting = new bootstrap.Toast(extracting_toast);

      extracting.show();

      $('#extracting-toast .spinner-extract').removeClass('d-none');
      $("#extracting-toast .toast-body").text(`Dossiers traités : 0 / ${completed}`);

      let form = $(this).closest("form").attr('id'),
        url_extract = $(`#${form} #url-extract`).val();






      if (!check_form()) {
        return false
      }

      $(`#${form} #extract_files_button`).addClass("d-none");

      $(`#${form} #save_files_button`).addClass("d-none");
      //$(`#${form} .loader`).toggleClass("d-none");

      let data_send,
        final_url = `/api/${url_extract}`,
        processData = true,
        contentType = true,
        method = "POST";

      $(`#${form} #file`).removeClass('is-invalid');

      ExtractFiles(form, final_url, method);

      });



      function ExtractFiles(form, final_url, method) {

      var processData = true,
        contentType = true,
        data_send = {
        "files": JSON.stringify(my_data_files)
      };

      data_send = getInputValues(form, data_send, 'form-control', true, [], except = ["files"])

      data_send['provider'] = $(`#provider`).val();
      data_send['slip'] = $(`#slip`).val();

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


          let status = xhr.status;



          switch (status) {
            case 422:

              $.each(xhr.responseJSON.errors, function(key, value) {
                $('#provider-input .invalid-feedback').text(value[0]);
                $(`#${key}`).addClass('is-invalid');

              });


              break;

            default:
              break;
          }


        },

        onComplete = function(xhr, textStatus) {


        };



      var ajaxRequest = constructAjaxRequest(final_url, data_send, onSuccess, onError, onComplete, method, processData, contentType, 'json');

      sendAjaxRequest(ajaxRequest);


      }



      //////////////////////////////////////////////////// After extract  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\



      const addCaseFile = (folder, tab, badge = "badge-success") => {


      if (badge != null) {


        display_pdf([{
          "link": folder.doc_path,
          "page": folder.invoice_index
        }], tab, folder.code);

        var success_count = parseInt($(`#${badge}`).text()) + (1);
        $(`#${badge}`).text(success_count);



        if (parseInt($(`#${badge}`).text()) != 1) {


          return false;

        }

      }

      let card = $("#good"),
        bad = $("#bad"),
        good_ref = ``,
        services = ``,
        prestation_lines = {},
        prestation_total = {};

      fill_provider(tab, folder.provider);
      fill_finance(tab, folder.invoices);
      fill_beneficiary(tab, folder);
      fill_invoice(tab, folder);
      

      };


      const fill_provider = (tab, provider) => {

      $(`#${tab} .name`).text(provider.name);
      //$(`#${tab} .address`).text(provider.address);
      //$(`#${tab} .category`).text(provider.category.name);

      };

      const fill_finance = (tab, invoices) => {

      $(`#${tab} .invoices_count`).text(invoices.length);
      $(`#${tab} .finance_services`).html('');

      var list = ``,
        already_exist = [];

      $.each(invoices, function(indexInArray, invoice) {

        if (already_exist.indexOf(invoice.prestationable.fullname) == -1) {
          list += `<li>${invoice.prestationable.fullname}</li>`;
        }

      });

      $(`#${tab} .finance_services`).append(list);


      };

      const fill_beneficiary = (tab, folder) => {

      $(`#${tab} .beneficiary`).text(folder.insured.name);
      $(`#${tab} .insured`).text(folder.insured.name);
      $(`#${tab} .folder_id`).text(folder.identification);


      var list = ``,
        already_exist = [];
      $(`#${tab} .reference_ids`).html('');

      $.each(folder.invoices, function(indexInArray, invoice) {

        if (already_exist.indexOf(invoice.reference) == -1) {
          list += `<li  class="${invoice.reference == "UNDEFINED REFERENCE" ? "text-danger" : "text-success"}">${invoice.reference}<a class="me-3 edit blink "
                  href="#"><i
                    class="menu-icon mdi mdi-table-edit"></i>
              </a></li>`;
        }

      });

      $(`#${tab} .reference_ids`).append(list);


      };



      const fill_invoice = (tab, folder) => {

      var index = 1,
        lines = ``,
        total = 0,
        invoices = folder.invoices;

      $('.new-line').data('table', folder.code);
      $('.invoice-table').attr('id', folder.code);

      $.each(invoices, function(indexInArray, invoice) {

        $.each(invoice.invoice_lines, function(indexInArray, invoice_line) {

          var quantity = parseInt(invoice_line.quantity),
            price = parseInt(invoice_line.price),

            total_line = price * quantity;

          total += total_line;


          lines += `<tr id="${invoice_line.code}" data-invoice-line="${invoice_line.code}"
      data-description="${invoice_line.description}"
      data-quantity="${quantity}"
      data-price="${price}"
      data-service="${invoice.prestationable ? invoice.prestationable.fullname : 'SELECTIONNEZ LE SERVICE CONCERNE'}">

                          <td>${index++}</td>
                          <td>${invoice_line.description}</td>
                          <td>${quantity}</td>
                          <td>${price}</td>
                          <td class="partial-total">${total_line}</td>
                          <td>${invoice.prestationable ? invoice.prestationable.fullname : 'SELECTIONNEZ LE SERVICE CONCERNE'}</td>
                          <td>

                            <a class="me-3 edit edit-item blink "
                                href="#"><i
                                  class="menu-icon mdi mdi-table-edit"></i>
                            </a>

                            <a id="delete" class="delete delete-item blink" href="#"><i
                                class="menu-icon mdi mdi-close-circle"></i></a> 
                            <input id="input" type="hidden"
                              value="">
                            <div class="blink loader d-none d-flex justify-content-center mt-3">

                            <div class="inner-loading dot-flashing"></div>
                            </div>


                          </td>

                        </tr>
                        
                        
                        `;


        });


      });

      lines += `<tr id="line-total">
                    <td></td>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td id="all-total">${total}</td>
                    <td></td>
                    <td></td>
                  </tr>`;


      $(`#${tab} .invoice-table tbody`).html(lines);



      };

      const fill_global_files = (files) => {

      files_global = files;

      }



</script>

<script>

      // Enable pusher logging - don't include this in production
      var token = $("#token").val();
      
    //Pusher.logToConsole = true;
    Echo.private(`folder.${token}`)
        .listen('.folder.extracted', (data) => {

          console.log(data);
          
          let folder = data.folder,
          has_items = data.has_items,
          is_new_folder = data.is_new_folder,
          
          import_completed = $("#import-completed").val(),
          
          extract_completed = parseInt($("#extract-completed").val()) + 1;
          
        //  console.log(folder.doc_name);
          

            $("#extract-completed").val(extract_completed);

            $("#extracting-toast .toast-body").text(`Dossiers traités : ${extract_completed}/${import_completed}`);

            $(`#upload-option-loader-${folder.doc_name}`).addClass('d-none');

            $(`#upload-complete-duration-${folder.doc_name}`).text(`${folder.extraction_times[0].duration} s`);


            
            $(`#upload-complete-icon-${folder.doc_name}`).removeClass('d-none');

            if (extract_completed == import_completed) {
              $('#extracting-toast .spinner-extract').addClass('d-none');
              //$('#extracting-toast .spinner-extract').addClass('d-none');
                //$("#form-files .loader").addClass('d-none');

              // console.log($("#save_files_button"));
              // $(`#extract_files_button`).removeClass("d-none");
                
              //  $("#save_files_button").removeClass("d-none");
            }

          //  my_folder = folder;

          if (has_items) {
            
          
          if (is_new_folder) {
            addCaseFile(folder, "audiences" , "badge-success");
      


            
          } else {

      
            
            addCaseFile(folder, "digital" , "badge-warning");

            
          }

        }
        else{

            addCaseFile(folder, "demographics" , "badge-danger");


        }



            ////////////////////////////we updata the global progress bar

            const percent = (extract_completed / import_completed) * 100;
            $(`#global-progress-bar`).width(percent + '%')
                    //$("#upload").text(`Uploading: ${percent.toFixed(2)}%`);;

        })
        .error((error) => {
          $(`#extract_files_button`).addClass("d-none");

          $("#save_files_button").removeClass("d-none");

            console.error(error);
        });/**/;

</script>


   
@endsection
