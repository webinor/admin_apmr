

@extends('common.layouts.front', ['title'=>'Inscription'])

@section('content')
    

<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
       
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="brand-logo text-center">
              <img style="width: 50px;"  src="{{asset('wordpress/2022/06/logo.png')}}" alt="logo">
              <i data-feather="circle"></i>
            </div>
            <h4>Hello!</h4>
            <h6 class="fw-light">Inscrivez-vous pour poster et gerer vos annonces</h6>
            <form class="pt-3 " novalidate method="post" action="register">

              <input type="hidden" value="email" name="subscribe_method" class="form-control" id="subscribe_method">
              <input type="hidden" value="237" name="country_code" class="form-control" id="country_code">
              <input type="hidden" value="{{ $from }}" name="from" class="form-control" id="from">

              @csrf
              <div class="form-group">
                <input type="text" name="first_name" class=" form-control form-control-sm" id="first_name" placeholder="Nom" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
                <div class="invalid-feedback">
                </div>
              </div>
              <div class="form-group">
                <input type="text" name="last_name" class=" form-control form-control-sm" id="last_name" placeholder="Prenom" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
                <div class="invalid-feedback">
                </div>
              </div>
              <div class="form-group">
                <input type="email" name="email" class=" form-control form-control-sm" id="email" placeholder="email" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
                <div class="invalid-feedback">
                </div>
              </div>
              <div class="form-group">
                <input type="number" name="phone_number" class=" form-control form-control-sm" id="phone_number" placeholder="contact téléhonique" required>
                <div class="valid-feedback">
                  Looks good!
                </div>
                <div class="invalid-feedback">
                </div>
              </div>
              <div class=" form-group">
             
                <input style="width: 98%;display: inline-block;" type="password" name="password" class="form-control form-control-sm" id="password" placeholder="mot de passe">
               

               
                  <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;opacity: 0.8;"></i>
                
                <div class="invalid-feedback">
                  
                </div>
              </div>
         
             
              <div id="register_button" class="mt-3">
                <button id="register" type="button"  class="w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                 Inscription
                </button>
              </div> 
              <div class="mt-3">
                <a href="{{ url($login_link) }}">J'ai deja un compte, je me connecte</a>
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
  $(document).ready(function () {
    
  
      $("#register").click(function (e) {
  
        let data_send = {},
      inputs=$('.form-control');
       $.map(inputs, function (input, indexOrKey) {
      $(`#${input.id}`).removeClass('is-invalid');


      data_send[input.id]=input.value;
  
      });/* */
  
        $("#register_button").toggleClass("d-none");
        $("#loader").toggleClass("d-none");
      //  $(".needs-validation").submit();
        e.preventDefault();
  
        $.ajax({
          type: "post",
          url: "{{ $register_end_point }}",
          data: data_send,
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
          dataType: "json",
          success: function(data, textStatus, xhr) {
          if (data.status) {
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
     
      } 
        });
  
        
    
      });
  
    });
    </script>


@endsection
