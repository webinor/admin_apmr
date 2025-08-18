@extends('common.layouts.front' , ['title'=>"Réinitialisation du mot de passe"])


@section('content')
    


<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
      <div class="row w-100 mx-0">
       
        <div class="col-lg-4 mx-auto">
          <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="brand-logo text-center">
              <img  src="{{asset('wordpress/2022/06/logo.png')}}" alt="logo">
              
            </div>
            {{-- <h4>Hello!</h4> --}}
            <h6 class="fw-light">Saisissez votre adresse email utilisée sur ce site</h6>
            <form class="pt-3 " novalidate method="post" action="reset_password">

            
              @csrf
              
              <div class="form-group">
                <input type="email" name="email" class=" form-control form-control-lg" id="email" placeholder="email" required>
                <div class="valid-feedback">
                  
                </div>
                <div class="invalid-feedback">
                </div>
              </div>
             
              <div id="reset_button" class="mt-3">
                <button id="reset" type="button"  class="w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                 Réinitialiser le mot de passe
                </button>
              </div> 
             
              
              <div id="loader" class="d-none d-flex justify-content-center mt-3">
                
                  <div class="inner-loading dot-flashing"></div>
                
              </div>
               <div class="mt-3">
                <a href="{{ $login_link }}">Se connecter</a>
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
    
  
  
      $("#reset").click(function (e) {
  
        let data_send = {},
      inputs=$('.form-control');
       $.map(inputs, function (input, indexOrKey) {
      $(`#${input.id}`).removeClass('is-invalid');
      $(`#${input.id}`).removeClass('is-valid');
  
      data_send[input.id]=input.value;
  
      });
  
        $("#reset_button").toggleClass("d-none");
        $("#loader").toggleClass("d-none");
        e.preventDefault();
  
        $.ajax({
          type: "post",
          url: "{{ $reset_password_endpoint }}",
          data: data_send,
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
          dataType: "json",
          success: function(data, textStatus, xhr) {
          if (data.status) {
            $(`#email`).siblings('.valid-feedback').text(data.data);
       $(`#email`).addClass('is-valid');
          } 
          else{
            
            $.each(data.errors, function(key,value) {
       $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
       $(`#${key}`).addClass('is-invalid');
  
      });
       
       
    
          }
      },
      error: function (xhr) {
        console.log(xhr.responseJSON.errors);
        
     $.each(xhr.responseJSON.errors, function(key,value) {

       $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
       $(`#${key}`).addClass('is-invalid');
  
      });
  
      
    
  },
      complete: function(xhr, textStatus) {
        $("#reset_button").toggleClass("d-none");
        $("#loader").toggleClass("d-none");
      } 
        });
  
        
    
      });
  
    });
    </script>
      

@endsection