@extends('layouts.front', ["title"=>"Reinitialiser le mot de passe"])

@section('content')

<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="brand-logo text-center">
              <img src="{{asset('wordpress/2022/06/logo.png')}}" alt="logo">
           
            </div>
            <h4>Hello {{ $user->last_name }}!</h4>
            <h6 class="fw-light">Ce formulaire vous permet de définir un mot de passe.</h6>
            <form id="define_form" class="pt-3 " novalidate method="post" action="register">
              @csrf
              <input class="form-control" type="hidden" name="token" id="defined_token" value="{{$token}}">
              <div class="form-group">
                <input type="email" name="login" class=" form-control form-control-lg" id="login" placeholder="login" value="{{$user->email}}" readonly>
                <div class="valid-feedback">
                  Looks good!
                </div>
                <div class="invalid-feedback">
                </div>
              </div>
              <div class="form-group">
                <input style="width: 98%;display: inline-block;" type="password" name="password" class="form-control form-control-lg" id="password" placeholder="mot de passe">
                <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;opacity: 0.8;"></i>
                <div class="invalid-feedback">
                  
                </div>
              </div>
            
              <div id="register_button" class="mt-3">
                <button id="register" type="button"  class="w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                 Valider mon mot de passe
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
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>

@endsection

@section('custom_js')


<script>
    $("#register").click(function (e) {


$("#register_button").toggleClass("d-none");
$("#loader").toggleClass("d-none");
$(".alert-success").addClass("d-none");



let data = {};
let  inputs=$('#define_form .form-control');
console.log(inputs);
$.map(inputs, function (input, indexOrKey) {

$(`#${input.id}`).removeClass('is-invalid');

data[input.id]=$(`#${input.id}`).val();

});



 
//  $(".needs-validation").submit();
e.preventDefault();

$.ajax({
  type: "post",
  url: "/define_password",
  data: data,
headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  dataType: "json",
  success: function(data, textStatus, xhr) {
  if (data.status) {

    window.localStorage.setItem('token',data.token);

    window.location = data.redirect;
  } 
  else{
    $.each(data.errors, function(key,value) {
$(`#${key}`).siblings('.invalid-feedback').text(value[0]);
$(`#${key}`).addClass('is-invalid');

});
$("#register_button").toggleClass("d-none");
     $("#loader").toggleClass("d-none");


  }
},
error: function (xhr) {
console.log(xhr.responseJSON.errors);
//$('#validation-errors').html('');
$.each(xhr.responseJSON.errors, function(key,value) {
$(`#${key}`).siblings('.invalid-feedback').text(value[0]);
$(`#${key}`).addClass('is-invalid');

});

$("#register_button").toggleClass("d-none");
     $("#loader").toggleClass("d-none");

},
complete: function(xhr, textStatus) {
// $("#register_button").toggleClass("d-none");
// $("#loader").toggleClass("d-none");
} 
});



});
</script>


@endsection