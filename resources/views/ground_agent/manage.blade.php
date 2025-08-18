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
            <a href="{{url('ground-agent')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste des responsables d'escale</a>
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
                    <input id="ground_agent" type="hidden" class="form-control" value="{{$ground_agent ? $ground_agent->code : ''}}" >
                  
          
                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Nom</label>
                        <input type="text" name="name" value="{{$ground_agent ? $ground_agent->name : ''}}" class=" form-control" id="name" placeholder="Ex : Douala" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-sm-12 mb-3 mb-sm-0">
                        <label for="name">Prenom</label>
                        <input type="text" name="name" value="{{$ground_agent ? $ground_agent->name : ''}}" class=" form-control" id="name" placeholder="Ex : Douala" required>
                        <div class="valid-feedback">
                        </div>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="city_id">Selectionnez la ville<span class="text-danger">*</span></label>
                      <select name="city_id" class="form-control" id="city_id" placeholder="">
                    
                        @forelse ($cities as $city)
                    
                        @if ($loop->first)
                        <option value="" >Selectionnez un type de piece</option>
                        @endif
                           
                        <option value="{{$city->id}}">{{Str::upper(__($city->name))}}</option>
                        
                        @empty
                    
                        <option value="">Aucun type de piece disponible</option>
                    
                        @endforelse
                    
                      </select>
                      <div class="valid-feedback">
                      </div>
                      <div class="invalid-feedback">
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="city_id">Selectionnez la compagnie<span class="text-danger">*</span></label>
                      <select name="city_id" class="form-control" id="city_id" placeholder="">
                    
                        @forelse ($cities as $city)
                    
                        @if ($loop->first)
                        <option value="" >Selectionnez un type de piece</option>
                        @endif
                           
                        <option value="{{$city->code}}">{{Str::upper(__($city->name))}}</option>
                        
                        @empty
                    
                        <option value="">Aucun type de piece disponible</option>
                    
                        @endforelse
                    
                      </select>
                      <div class="valid-feedback">
                      </div>
                      <div class="invalid-feedback">
                      </div>
                    </div>
                  
                    <div id="update_button" class="mt-3">
                      <button id="update" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       Modifier ce responsable d'escale
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
        url: protocol+host+`/api/admin/settings/city/${data_send['city']}?_method=PUT`,
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