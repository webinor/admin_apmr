@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations generales</a>
          </li>
          {{--<li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" aria-controls="audiences" role="tab" aria-selected="false">Interlocuteurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Documents</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="digital-tab" data-bs-toggle="tab" href="#digital" role="tab" aria-selected="false">Presence digitale</a>
          </li>
          <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">Notes</a>
          </li>--}}
        </ul>
        <div>
          <div class="btn-wrapper">
            {{--@if (Auth::user()->can('create', App\Models\settings\registrator::class))--}
    <!-- The current user can update the post... -->
    <a href="{{url('settings/registrator/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau client / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
            <a href="{{url('registrator')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des operateurs APMR</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                 
                  @if ($action == "create")

                  <h4 class="card-title">Ajouter un opérateur APMR</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">opérateur APMR ajouté avec succes</h6>
                  </div>
                      
                  @else
                      
                  <h4 class="card-title">Modifier l'opérateur APMR</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">opérateur APMR modifié avec succes</h6>
                  </div>

                  @endif

                  <form id="form" class="pt-3 " novalidate method="post" action="{{url('registrator/create')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{session('user')->code}}" >
                    <input id="ground-agent" type="hidden" class="form-control" value="{{$registrator ? $registrator->code : ''}}" >
                    <input id="action" type="hidden" class="" value="{{ $action }}" >
                    <input id="url" type="hidden"  value="{{"/api/registrator".($registrator ? "/".$registrator->code."?_method=PUT" : "")}}" >
                  

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Nom<span class="text-danger">*</span></label>
                        <input type="text" name="nameame" value="{{$registrator ? $registrator->name : ''}}" class=" form-control" id="name" placeholder="Ex : NONO" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="last_name">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{$registrator ? $registrator->last_name : ''}}" class=" form-control" id="last_name" placeholder="Ex : Albert" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                   

                   {{--  <div class="form-group">
                      <label for="registrator">Selectionnez la ville<span class="text-danger">*</span></label>
                      <select name="registrator" class="form-control" id="registrator" placeholder="">
                    
                        @forelse ($cities as $registrator)
                    
                        @if ($loop->first)
                        <option value="" >Selectionnez un type de piece</option>
                        @endif
                           
                        <option value="{{$registrator->id}}">{{Str::upper(__($registrator->name))}}</option>
                        
                        @empty
                    
                        <option value="">Aucun type de piece disponible</option>
                    
                        @endforelse
                    
                      </select>
                      <div class="valid-feedback">
                      </div>
                      <div class="invalid-feedback">
                      </div>
                    </div> --}}


                    <div class="form-group">
                      <label for="city">Ville</label>
                      <select name="city" class="form-control" id="city" placeholder="">
                    
                        @forelse ($cities as $city)
                    
                        @if ($loop->first)
                        <option value="" >Seactionnez la ville </option>
                        @endif
                           
                        <option value="{{$city->code}}" {{ $registrator && $registrator->city->code == $city->code ? 'selected' : '' }}>{{$city->name}}</option>
                        
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
                        <input type="email" name="email" value="{{$registrator ? $registrator->email : ''}}" class=" form-control" id="email" placeholder="Ex : aaa@gmal.com" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="file">Signature</label>
                    
                        <input {{ $readonly }} type="file" name="file" class=" form-control" id="file" placeholder="file" required>


    {{-- Affichage d'une miniature si image déjà existante --}}
    @if(!empty($registrator->image_path))
    <div class="mt-2">
      <img id="registrator_signature" src="{{ asset('storage/registrators_images/' . $registrator->image_path) }}" 
           alt="Signature actuelle" 
           class="img-thumbnail" 
           style="max-height: 80px;">
    </div>
  @endif

                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div> 
                  
                    <div id="update_button" class="mt-3">
                      <button id="update" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       {{ $action == "create" ? "Ajouter" : "Modifier" }}
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
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_js')
{{--<script src="{{asset('libs/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('libs/js/file-upload.js')}}"></script>
<script src="{{asset('libs/js/select2.js')}}"></script>--}}

    <script>
      
$(document).ready(function () {

  var protocol = location.protocol === 'https:' ? "https://" : "http://"  ;
var host = location.host;// document.domain ;

    $("#form #update").click(function (e) {

       
    $("#form #update_button").toggleClass("d-none");
    $("#form #loader").toggleClass("d-none");
    $("#overview .alert-success").addClass("d-none");

    

   /* let data_send = new Form;
    inputs=$('#form .form-control');
      $.map(inputs, function (input, indexOrKey) {
    $(`#form #${input.id}`).removeClass('is-invalid');

    data_send[input.id]=$(`#form #${input.id}`).val();

    });*/

    let data_send = new FormData(),
	 url = $("#url").val(),
	 processData = false,
	contentType = false,
	method = "POST";
   
	
  
  
  data_send = getInputValues('form', data_send ,  'form-control' , false );
  
  
    // Get the selected file
    var files = $(`#form #file`)[0].files;
 console.log(files);
if(files.length > 0){

   // Append data  
   data_send.append('file',files[0]);


}

//console.log(url);

  

      $.ajax({
        type: "post",
        url: url,// protocol+host+`/api/ground-agent/${data_send['ground-agent'] ? data_send['ground-agent']+'?_method=PUT' : ''}`,
        data: data_send,
        contentType: false,
        processData: false,
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
        dataType: "json",
        success: function(data, textStatus, xhr) {
        if (data.status) {

          if ($("#action").val()=="create") {

            $("#form")[0].reset();
          }
          $("#overview .alert-success").toggleClass("d-none");
        
              // Mise à jour de l'image si on a une nouvelle image_path
    if (data.data.image_path) {

        $("#registrator_signature").attr("src", "/storage/registrators_images/" + data.data.image_path);
        
    }
          
  }
        else{
          $.each(data.errors, function(key,value) {
     $(`#${key} `).siblings('.invalid-feedback').text(value[0]);
     $(`#${key} `).addClass('is-invalid');
 
    });
     
        }
        $("#form #update_button").toggleClass("d-none");
           $("#form #loader").toggleClass("d-none");
    },
    error: function (xhr) {
      console.log(xhr.responseJSON.errors);
   //$('#validation-errors').html('');
   $.each(xhr.responseJSON.errors, function(key,value) {
     $(`#${key} `).siblings('.invalid-feedback').text(value[0]);
     $(`#${key} `).addClass('is-invalid');

    });

     $("#form #update_button").toggleClass("d-none");
           $("#form #loader").toggleClass("d-none");
  
},
    complete: function(xhr, textStatus) {
    
    } 
      });

      
  
    });


  });
    </script>
@endsection