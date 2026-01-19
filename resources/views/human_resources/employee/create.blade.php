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
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Informations générales</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" aria-controls="audiences" role="tab" aria-selected="false">Droits d'accès</a>
          </li>
          
          {{--<li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Informations Manageriales</a>
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
            {{--@if (Auth::user()->can('create', App\Models\Sales\Customer::class))--}
    <!-- The current user can update the post... -->
    <a href="{{url('sales/customer/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau client / Prospect</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           --}}
            <a href="{{url('human_resource/employee')}}" class="btn btn-primary text-white"><i class="icon-printer"></i>Liste du personnel</a>
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">        
          <div id="audience_tab" class="row ">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  @php
                      if ($employee) {
                        $title ="Modifier le collaborateur";
                        $user_title ="Modifier le compte de ce collaborateur";
                        $text = "collaborateur modifié avec succes" ;
                        $user_text = "compte modifié avec succes" ;
                      } else {
                        $title ="Creer le collaborateur";
                        $user_title ="Creer le compte utilisateur de ce collaborateur";
                        $text = "collaborateur crée avec succes" ;
                        $user_text = "compte crée avec succes" ;
                      }
                      
                  @endphp
                  <h4 class="card-title">{{ $title }}</h4>
        <div class="d-none alert alert-success" role="alert">
          <h6 class="alert-heading">{{ $text }}</h6>
        </div>
        <form id="form" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
          @csrf
          <input class=" form-control " id="token" type="hidden" value="{{session('user')->id}}" >
          <input class=" form-control " id="action" type="hidden" value="{{$action}}" >
          <input readonly class="employee form-control" id="employee" type="hidden" value="{{$employee ? $employee->id : ''}}" >
          

<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="first_name">Nom</label>
    <input type="text" name="first_name" class=" form-control form-control-lg" id="first_name" placeholder="Nom" value="{{$employee ? $employee->first_name : ''}}" required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>

<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="last_name">Prenom</label>

    <input type="text" name="last_name" class=" form-control form-control-lg" id="last_name" placeholder="Prenom" value="{{$employee ? $employee->last_name : ''}}" required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>

<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="main_phone_number">Contact telephonique</label>

    <input type="number" name="main_phone_number" class=" form-control form-control-lg" id="main_phone_number" placeholder="Contact telephonique" value="{{$employee ? $employee->main_phone_number : ''}}" required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>

<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="address">Adresse</label>
    <input type="email" name="address" class=" form-control form-control-lg" id="address" placeholder="Adresse" value="{{$employee ? $employee->address : ''}}" required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>

<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="personal_email">Email personnelle</label>
    <input type="personal_email" name="personal_email" class=" form-control form-control-lg" id="personal_email" placeholder="Adresse email personnelle" value="{{$employee ? $employee->personal_email : ''}}"  required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>

<div class="form-group">
  <label for="role">Role de ce(cette) collaborateur(trice)</label>
  <select name="role" class="form-control form-control-lg" id="role" placeholder="Role de ce(cette) collaborateur(trice))">

    @forelse ($roles as $role)

    @if ($loop->first)
    <option value="" >Selectionnez le Role de ce(cette) collaborateur(trice) ?</option>
    @endif
       
    <option value="{{$role->id}}" {{$employee && $employee->role->id == $role->id ? "selected" : ""}}>{{$role->role_name}}</option>
    
    @empty

    <option value="">Aucun role disponible</option>

    @endforelse

  </select>
  <div class="valid-feedback">
  </div>
  <div class="invalid-feedback">
  </div>
</div>

{{-- 
<div id="wrapper_habilitations" class={{$employee && $employee->role->id != 5 ? "d-none" : "" }}>
    
    
    <div class="col-md-12">
      <div class="form-group row">
        <label class="col-sm-2 fw-bold">Habilitations</label>
        <div class="col-sm-4">
          <div class="form-check">
            <label class="form-check-label">

              @if ($employee && $employee->habilitations->count() == $habilitations->count())
              <input type="checkbox" class="parent form-check-input parent_input habilitation"  value="" checked>
              Tout decocher
              @else
              <input type="checkbox" class="parent form-check-input parent_input habilitation"  value="" >
              Tout cocher
              @endif

              
            </label>
          </div>
        </div> 
      </div>
    </div>

    
    <div class="col-md-12 ms-5">
      <div class="form-group row">

        @foreach ($habilitations as $habilitation)
            <div class="col-sm-3">
              <div class="form-check">
                <label class="form-check-label">
                  @if ($employee)
                     <input type="checkbox" class="form-check-input habilitations_input habilitations_input_{{$habilitation->slug}}"  id="{{$habilitation->slug}}" value="{{$habilitation->id}}" @foreach ( $employee->habilitations as $employee_habilitation)
                @if ($employee_habilitation->id == $habilitation->id ) 
                    checked 
                    @break
                @endif                      
                  @endforeach> 
                  @else
                     <input type="checkbox" class="form-check-input habilitations_input habilitations_input_{{$habilitation->slug}}"  id="{{$habilitation->slug}}" value="{{$habilitation->id}}"> 
                  @endif
                  {{$habilitation->name}}
                </label>
               
              </div>
            </div>
        @endforeach
          </div>
    </div>

    </div> --}}

                    <div id="create_button" class="mt-3">
                      <button id="create" type="button"  class="text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                        {{ $employee ? "Modifier les information" : "Creer" }}
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

        {{--           Techniciens            --}}
        <div class="tab-pane fade" id="audiences" role="tabpanel" aria-labelledby="audiences"> 
          <div class="row">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card"> 
                <div class="card-body">
                  <h4 class="card-title">{{ $user_title }}</h4>
                  <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">{{ $user_text }}</h6>
                  </div>
                  <form id="form_user_access" class="pt-3 " novalidate method="post" action="{{url('company/create')}}">
                    @csrf
                    <input id="token" class="form-control" type="hidden" value="{{session('user')->id}}" >
                    <input readonly class="employee form-control" id="employee" type="hidden" value="{{$employee ? $employee->id : ''}}" >
                    
                    <input readonly class="tab"  type="hidden" value="audiences" >
                    <input readonly class="list"  type="hidden" value="user_access_table" >
                    <input readonly class="url"  type="hidden" value="access/credential?_method=PUT" >
                    <input readonly class="instance"  type="hidden" value="user_access" >
        {{-- url: protocol+host+`/api/human_resource/employee{{ $employee ? '/update?_method=PUT' : ''  }}`, --}}
                  


<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="email">Email de l'utilisateur</label>

    <input {{ $action != "create" ? "readonly" : "" }} type="email" name="email" class=" form-control form-control-lg" id="email" placeholder="Adresse email de bureau" value="{{ $employee && $employee->user ? $employee->user->email : ""}}" required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>


<div class="form-group row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <label for="password">Mot de passe de l'utilisateur</label>

    <input {{ $action != "create" ? "" : "" }} type="password" name="password" class=" form-control form-control-lg" id="password" placeholder="Mot de passe" value="" required>
    <div class="valid-feedback">
    </div>
    <div class="invalid-feedback">
    </div>
  </div>
</div>

<div  class="form-check w-50">
                <label class="form-check-label text-start fw-bold">
                  <input id="togglePassword" type="checkbox" class="form-check-input"/>
                  Afficher le mot de passe
                <i class="input-helper"></i></label>
              </div>

<div class="row">
               
@foreach ($menus as $menu)
    
    
    <div class="col-md-12">
      <div class="form-group row">
        <label class="col-sm-4 fw-bold">{{$menu->name}}</label>
        <div class="col-sm-4">
          <div class="form-check">
            <label class="form-check-label">
              @if ($distincts_actions && $distincts_actions->count() == $actions->count())
              <input type="checkbox" class="parent action form-check-input parent_input" id="{{$menu->slug}}" value="" {{--checked--}}>
              Tout decocher
              @else
              <input type="checkbox" class="parent action form-check-input parent_input" id="{{$menu->slug}}" value="" >
              Tout cocher
              @endif
            </label>
          </div>
        </div> 
      </div>
    </div>


 
    @foreach ($menu->submenus as $submenu)

 
    @php


    
    $already_unchecked =false;
    $already_checked=false;
    $already_action = [];
@endphp 
    <div id="wrapper_{{$submenu->slug}}" class="col-md-12 ms-5">
      <div class="form-group row">
        <label class="col-sm-2">{{$submenu->name}}</label>
           @foreach ($actions as $action)
    @php
        // Vérifie si cet action doit être cochée
        $is_checked = $distincts_actions->contains(function($authorize_action) use ($submenu, $action) {
            return $authorize_action->menu_id == $submenu->id && $authorize_action->action_id == $action->id;
        });
    @endphp

    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox"
                       class="{{ $action->name }} form-check-input actions_input actions_input_{{ $menu->slug }}"
                       name="{{ $submenu->name }}"
                       id="{{ $submenu->slug }}_{{ $action->name }}"
                       value="{{ $submenu->id }}:{{ $action->id }}"
                       @if($is_checked) checked @endif
                >
                {{ __($action->name) }}
            </label>
        </div>
    </div>
@endforeach

          </div>
        </div>
    @endforeach

@endforeach


</div>

                    <div id="update_button" class="mt-3">
                      <button  id="add_user_access" {{ $employee ? "" : "disabled" }} type="button"  class="additionnal_details text-white w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                       {{ $employee ? "Modifier les information" : "Creer" }}
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
        {{--           End Techniciens        --}}

          

       
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
var host = location.host;// employee_position.domain ;

$("#role").change(function (e) { 
    e.preventDefault();

    if ($(this).val()=="5" ) {
      $("#wrapper_habilitations").removeClass("d-none");
    }
    else{
      $("#wrapper_habilitations").addClass("d-none");

    }
    
  });
  /*
$(".parent").change(function (e) {


  if ($(this).hasClass('action')) {
     let menu_id = $(this).attr('id');
    $(`.actions_input_${menu_id}`).not(this).prop('checked', this.checked);
    
  }
  else if($(this).hasClass('habilitation')){
    let menu_id = $(this).attr('id');
    $(`.habilitations_input`).not(this).prop('checked', this.checked);
    
  }
   
    e.preventDefault();
    
  });



  $(".create").change(function (e) {
    let sub_menu_id = $(this).attr('id');
    let wrapper = `wrapper_${sub_menu_id}`;

    $(`#${wrapper} .read`).not(this).prop('checked', this.checked);
    $(`#${wrapper} .update`).not(this).prop('checked', this.checked);

    
    e.preventDefault();
    
  });
  
  
  
  */

      $(".parent").each(function () {

        let menu_slug = $(this).attr('id'); // ex : "absence"
        let parent_checkbox = $(this);

        let all_actions = $(`.actions_input_${menu_slug}`);
        let checked_actions = $(`.actions_input_${menu_slug}:checked`);

      //  console.log(all_actions);
        
        // si toutes les actions sont cochées
        if (all_actions.length > 0 && all_actions.length === checked_actions.length) {
            parent_checkbox.prop('checked', true);
        } else {
            parent_checkbox.prop('checked', false);
        }
    });


  $(".parent").change(function () {//new
    let menu_slug = $(this).attr('id'); // ex: absence, workflow
    let is_checked = this.checked;

    // coche/décoche toutes les actions du menu
    $(`.actions_input_${menu_slug}`).prop('checked', is_checked);
});

$(".create").change(function () {//new
    let wrapper = $(this).attr('id'); // ex: action_absence_create
    //console.log(wrapper);
    
    let submenu_slug = wrapper.split("_").slice(0, -1).join("_"); // récupère juste "absence"
    let is_checked = this.checked;

    // coche automatiquement read + update
    $(`#wrapper_${submenu_slug} .read`).prop('checked', is_checked);
    $(`#wrapper_${submenu_slug} .update`).prop('checked', is_checked);
});




    $("#create").click(function (e) {
      let data = {};
      var searchHabilitationsIDs =[];
      if ( $("#role").val()==5 ) {
         searchHabilitationsIDs = $("#form .habilitations_input:checked").map(function(){
      return parseInt($(this).val());
    }).get(); // <----
    console.log(searchHabilitationsIDs);
 // data['habilitations']= JSON.stringify(searchHabilitationsIDs) ;

      }
    
    /*  var searchIDs = $("#form .actions_input:checked").map(function(){
      return $(this).val();
    }).get(); // <----
  //  console.log(searchIDs);
  data['authorize_actions']= JSON.stringify(searchIDs);*/

  
      
    $("#create_button").toggleClass("d-none");
    $("#loader").toggleClass("d-none");
    $(".alert-success").addClass("d-none");

  
    inputs=$('#form .form-control');
     // console.log(inputs);
      $.map(inputs, function (input, indexOrKey) {

    $(`#form  #${input.id}`).removeClass('is-invalid');

    data[input.id]=$(`#form  #${input.id}`).val();

    });
 
      $.ajax({ 
        type: "post",
        url: protocol+host+`/api/human_resource/employee{{ $employee ? '/'.$employee->code.'?_method=PUT' : ''  }}`,
       
        data: data,
      headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
        dataType: "json",
        success: function(data, textStatus, xhr) {
        if (data.status) {

          
          let action = $("#action").val();;

        //  console.log(action);

          if (action == 'create') {
        
          //  console.log(action);
          $(".employee").val(data.data.employee.id);
          $(".additionnal_details").removeAttr("disabled");
          $("#form")[0].reset();

          }
         // console.log(data.data.employee.id);

          $("#overview .alert-success").toggleClass("d-none");
        
          $("html").animate({ scrollTop: 0}, 1000);
          
        } 
        else{
          $.each(data.errors, function(key,value) {
     $(`#${key} `).siblings('.invalid-feedback').text(value[0]);
     $(`#${key} `).addClass('is-invalid');
 
    });
     
        }
        $("#create_button").toggleClass("d-none");
           $("#loader").toggleClass("d-none");
    },
    error: function (xhr) {
    //  console.log(xhr.responseJSON.errors);
   //$('#validation-errors').html('');
   $.each(xhr.responseJSON.errors, function(key,value) {
     $(`#${key} `).siblings('.invalid-feedback').text(value[0]);
     $(`#${key} `).addClass('is-invalid');

    });

     $("#create_button").toggleClass("d-none");
           $("#loader").toggleClass("d-none");
  
},
    complete: function(xhr, textStatus) {
     // $("#create_button").toggleClass("d-none");
     // $("#loader").toggleClass("d-none");
    } 
      });

      
  
    });


    $(".additionnal_details").click(function (e) {

 let form =  $(this).closest("form").attr('id'),
     tab =  $(`#${form} .tab`).val(),
     table =   $(`#${form} .list`).val(),
     instance =   $(`#${form} .instance`).val(),
     url =   $(`#${form} .url`).val();

      console.log(`${form} ${tab} ${table} ${url} ${instance}`);
add_details_request(form , tab , table , url , instance)

});


const add_details_request = (form , tab , table , url , instance) => {

  //  console.log('ok');
$(`#${form} #update_button`).toggleClass("d-none");
$(`#${form} #loader`).toggleClass("d-none");
$(`#${tab} .alert-success`).addClass("d-none");

//url = sales/interlocutor/store

let data_send = {},
fd;


if (form !="form_document") {

  inputs=$(`#${form} .form-control`);

$.map(inputs, function (input, indexOrKey) {

$(`#${form} #${input.id}`).removeClass('is-invalid');

  data_send[input.id]=$(`#${form} #${input.id}`).val();

});

if (form == "form_user_access") {

let searchIDs = [];

$(`#${form} .actions_input:checked`).each(function () {
    let val = $(this).val();

    if (!searchIDs.includes(val)) {
        searchIDs.push(val);
    }
});

  data_send['authorize_actions']= JSON.stringify(searchIDs);
}
  
} else {

$(`#${form} #slug`).removeClass('is-invalid');
$(`#${form} #file`).removeClass('is-invalid');

   fd = new FormData();

  
    // Get the selected file
    var files = $(`#${form} #file`)[0].files;
// console.log(files);
if(files.length > 0){

   // Append data 
   fd.append('file',files[0]);


}

fd.append('_token',$('meta[name="csrf-token"]').attr('content'));
   fd.append('customer',$(`#${form} #customer`).val());
   fd.append('slug',$(`#${form} #slug`).val());
   fd.append('token',$(`#${form} #token`).val());

}

let ajax_object = {
    type: "post",
    url: protocol+host+`/api/${url}`,
    data: form !="form_document" ? data_send : fd,
  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
    dataType: "json",

    success: function(data, textStatus, xhr) {
    if (data.status) {

      $(`#${tab} .alert-success`).toggleClass("d-none");
     
      if (instance!="user_access") {
      addRow(instance, table, data.data)
        

      }
      $("html").animate({ scrollTop: 0}, 1000);




     // $(`#${form}`)[0].reset();
    } 
    else{
      $.each(data.errors, function(key,value) {
 $(`#${form} #${key} `).siblings('.invalid-feedback').text(value[0]);
 $(`#${form} #${key} `).addClass('is-invalid');

});
 
    }
    $(`#${form} #update_button`).toggleClass("d-none");
       $(`#${form} #loader`).toggleClass("d-none");
},
error: function (xhr) {
  console.log(xhr.responseJSON.errors);
//$('#validation-errors').html('');
$.each(xhr.responseJSON.errors, function(key,value) {
 $(`#${form} #${key} `).siblings('.invalid-feedback').text(value[0]);
 $(`#${form} #${key} `).addClass('is-invalid');

});

 $(`#${form} #update_button`).toggleClass("d-none");
       $(`#${form} #loader`).toggleClass("d-none");

},
complete: function(xhr, textStatus) {
 // $("#update_button").toggleClass("d-none");
 // $("#loader").toggleClass("d-none");
} 
  }

  if (form =="form_document") {
    ajax_object['contentType']= false;
    ajax_object['processData']=false;
  }

  $.ajax(ajax_object);

  

}


const addRow = (instance, table, data , id) =>{
  //  console.log(quote_line);

  let columns =``,cols = [] ;


  switch (instance) {
    case 'user_access':
      
    cols = [data.instance.first_name ,
       data.instance.last_name,
       data.instance.email,
       data.instance.main_phone_number,
       data.instance.auxiliary_phone_number,
       data.instance.whatsapp_phone_number
       ];

      break;

      case 'employee_position':
    
      console.log(data.employee_position);
    cols = data.employee_position ? [data.employee_position.position.title ,
  
    data.employee_position.entry_service ? new Date(data.employee_position.entry_service) : '',
       ] : [];

       break;

       case 'platform':
    
    cols = [data.platform.slug ,
       data.platform.link,
      // new Date(data.platform.created_at),
       ];

       break;

       case 'note':
    
    cols = [data.note.note ,
      // data.note.link,
      // new Date(data.note.created_at),
       ];

       break;
  
    default:
      break;
  }

  
  $.each(cols, function (indexInArray, col) { 
    if (col!=null) {
    columns+=`<td>${col}</td>`
    }
    else{
      columns+=`<td></td>`
    }
  });

    if (columns!=``) {
      
      $(`#${table} tr:last`).after(`<tr class = "line">
      ${columns}
       <td> <form>
        <a id="delete_${data[instance].id}"  class=" delete" ><i class="menu-icon mdi mdi-close-circle"></i></a>
        <input id="input_${data[instance].id}" type="hidden" value="${data[instance].id}"> 
      </form> 
      </td>
      </tr>`);

    }
    

   

    }

    $(".table").on( 'click' , '.delete' ,function (e) { 
 
 let closest_tr =  $(this).closest("tr"),
  closest_table_id =  $(this).closest("table").attr('id'),
 id = $(this).siblings("input").val() ;
 

deleteDetail(id , closest_tr , closest_table_id );


e.preventDefault();

});


const deleteDetail = (id , closest_tr , closest_table_id ) => {

$(".delete").addClass("d-none");
 
let urls = {
  user_access : `user_access/delete/${id}`,
  documents_table : `employee_position/delete/${id}`,
  platforms_table : `platform/delete/${id}`,
  notes_table : `note/delete/${id}`,
}

let data_send = {};

data_send['id']=id;


$.ajax({
  type: "post",
  url: protocol+host+`/api/human_resources/${urls[closest_table_id]}`,
  data: data_send,
headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
  dataType: "json",
  success: function(data, textStatus, xhr) {
  if (data.status) {

    closest_tr.remove();
    

$(".delete").removeClass("d-none");


  }
  else{
 


  }

},
error: function (xhr) {

},
complete: function(xhr, textStatus) {

} 
});



    }

  });
    </script>
@endsection