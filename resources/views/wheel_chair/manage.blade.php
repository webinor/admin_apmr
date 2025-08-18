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
            {{--@if (Auth::user()->can('create', App\Models\settings\city::class))--}
    <!-- The current user can update the post... -->
    <a href="{{url('settings/city/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau client / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
            <a href="{{url('wheel-chair')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des chaises</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">Modifier la ville</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">ville modifiée avec succes</h6>
                  </div>
                  <form id="form" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" type="hidden" class="form-control" value="{{session('user')->code}}" >
                    <input id="wheel_chair" type="hidden" class="form-control" value="{{$wheel_chair ? $wheel_chair->code : ''}}" >
                  
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Nom du type</label>
                        <input type="text" name="name" value="{{$wheel_chair ? $wheel_chair->name : ''}}" class=" form-control" id="name" placeholder="Ex : chaise c" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="slug">reference</label>
                        <input type="text" name="slug" value="{{$wheel_chair ? $wheel_chair->slug : ''}}" class=" form-control" id="slug" placeholder="Ex : C" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>
                  
                    <div id="update_button" class="mt-3">
                      <button id="update" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Modifier ce type
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

    

    let data_send = {};
    inputs=$('#form .form-control');
      $.map(inputs, function (input, indexOrKey) {
    $(`#form #${input.id}`).removeClass('is-invalid');

    data_send[input.id]=$(`#form #${input.id}`).val();

    });
  

      $.ajax({ 
        type: "post",
        url: protocol+host+`/api/wheel-chair/${data_send['wheel_chair'] ? data_send['wheel_chair']+'?_method=PUT' : ''}`,
        data: data_send,
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
        dataType: "json",
        success: function(data, textStatus, xhr) {
        if (data.status) {

          $("#overview .alert-success").toggleClass("d-none");
        //  $('.customer').val(data.data.customer);
         // $("#form")[0].reset();
    //  $('.line').remove();
        //  $('.additionnal_details').removeAttr('disabled');
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