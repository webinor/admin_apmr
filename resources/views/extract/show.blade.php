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

      <div class="fw-bold">Bordereaux > Bordereau Numéro : {{ $folder->slip->identification }} > Dossier : {{ $folder->doc_name }}</div>
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

                        {{--   <a target="_blank" href="#" class="btn btn-warning text-white"><i
                            class="icon-book"></i>Pré-valider à l'aide de l'assistant virtuel</a> --}}

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

 
                          @include('layouts.partials._invoice_extractor', ['color'=>'primary'])

                          @elseif(session('user')->isValidator())


                          @include('layouts.partials._invoice_validator', ['color'=>'primary'])


                              
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
@include('layouts.partials._modal_delete') 

  @include('layouts.partials._modal_update')
@endsection

@section('custom_js')

<script src="{{ asset("plugins/typeahead/typeahead.bundle.min.js") }}"></script> 
<script src="{{ asset("zen/js/typeahead.js") }}"></script>
<script src="{{ asset("zen/js/extractor-typeahead.js") }}"></script>
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
{{--<script src=" {{  asset("plugins/LC-Lightbox/js/lc_lightbox.lite.js") }}"></script>
<script src="{{  asset("plugins/web-pdf-viewer/js/pdfjs-viewer.js") }}"></script>
--}}

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
  url = '/save-invoices';

  data_send['invoices'] = invoices;
  data_send['folder'] = $("#current-folder").val();
  data_send['is_draft'] = is_draft;
  


    let onSuccess = function(data, textStatus, xhr) {
        if (data.status) {

          if (is_draft == "0" && data.success.responses.should_login) {
            
          location.reload();

          }

          if (is_draft == "0" ) {
            
            if (data?.success?.responses?.succeeded) {
            data.success.responses.succeeded.forEach(item => {
                const bodyReceivedSuccess = item?.body_received?.success;

                //console.log(bodyReceivedSuccess);

                if (bodyReceivedSuccess === true && Array.isArray(item?.body_sent?.rows)) {
                    item.body_sent.rows.forEach(row => {
                        const refPec = row?.refPec;
                        if (refPec) {
                            $(`a.api-${refPec}`).hide(); // Ou .remove() si tu veux les supprimer
                            $(`#api-final-${refPec}`).text('( PEC Sauvegardée )');   
                        }
                    });
                }
                else if(bodyReceivedSuccess === false && Array.isArray(item?.body_sent?.rows)){

                  item.body_sent.rows.forEach(row => {
                        const refPec = row?.refPec;
                        if (refPec) {
                          $(`#api-final-${refPec}`).text(`( ${item?.body_received?.message} )`);                        
                        }
                    });

                  

                }
            });
        }

          }

        //  console.log(`${data.success.all_folder_invoices} ${data.success.all_data.already_validated_in_remote} ${data.success.responses.all_request_successful}`);
          

        ///  if (is_draft == "1" || ( is_draft == "0" && (data.success.all_folder_invoices != data.success.all_data.already_created_in_remote) )) {
            if (is_draft == "1" || ( is_draft == "0" && ( !data.success.responses.all_request_successful ) )) {
        current_buttons.removeClass('d-none');
        }

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

       // if (is_draft == "1") {
       // current_buttons.removeClass('d-none');
       // }


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

  //   console.log("coverage == "+ $(row).data('status'));
     
     invoices[$(row).data('invoice')].push({'description':$(row).data('description'),
        'quantity':$(row).data('quantity'),
        'price':$(row).data('price'),

        'suggested_quantity':$(row).data('item-quantity-suggested'),
        'suggested_price':$(row).data('item-price-suggested'),
       // 'prestation':$(row).data('service'),
        'observation':$(row).data('observation'),
        'coverage':$(row).data('status'),
      ///  'comment':$(row).data('comment'),
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
  url = '/validate-invoices';

  data_send['invoices'] = invoices;
  data_send['is_draft'] = is_draft;
  data_send['folder'] = $("#current-folder").val();


    let onSuccess = function(data, textStatus, xhr) {
        if (data.status) {


          if (is_draft == "0" && data.success.responses.should_login) {
            
            location.reload();
  
            }
  
            if (is_draft == "0" ) {
              
              if (data?.success?.responses?.succeeded) {
              data.success.responses.succeeded.forEach(item => {
                  const bodyReceivedSuccess = item?.body_received?.success;
  
                  //console.log(bodyReceivedSuccess);
  
                  if (bodyReceivedSuccess === true && Array.isArray(item?.body_sent?.rows)) {
                      item.body_sent.rows.forEach(row => {
                          const refPec = row?.refPec;
                          if (refPec) {
                              $(`a.api-${refPec}`).hide(); // Ou .remove() si tu veux les supprimer
                              $(`#api-final-${refPec}`).text('( PEC Validée )');   
                          }
                      });
                  }
                  else if(bodyReceivedSuccess === false && Array.isArray(item?.body_sent?.rows)){
  
                    item.body_sent.rows.forEach(row => {
                          const refPec = row?.refPec;
                          if (refPec) {
                            $(`#api-final-${refPec}`).text(`( ${item?.body_received?.message} )`);                        
                          }
                      });
  
                    
  
                  }
              });
          }
  
            }
  
            console.log(`${data.success.all_folder_invoices} ${data.success.all_data.already_validated_in_remote} ${data.success.responses.all_request_successful}`);
            
  
            if (is_draft == "1" || ( is_draft == "0" && ( !data.success.responses.all_request_successful ) )) {
                 current_buttons.removeClass('d-none');
          }



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
