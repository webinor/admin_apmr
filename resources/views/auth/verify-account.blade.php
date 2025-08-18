@extends('layouts.front' , ['title'=>"Vérification du code"])


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
            {{-- <h4>Hello!</h4> --}}
            <h6 class="fw-light">{{ __("Nous avons detecte un nouvel appareil, saisissez le code envoyé à l'adresse ") }} <span class="fw-bold">{{ $user->email }}</span> , {{ __(" cliquez sur \"Verifier le code\" afin de confirmer qu'il s'agit de vous.") }} </h6>
            <form class="pt-3 " novalidate method="post" action="reset_password">

              <input type="hidden" class="form-control" name="code" id="code" value="{{ $user->employee->code }}">
            
              @csrf
              
              <div class="form-group">
                <input type="number" name="verification_code" class=" form-control form-control-lg" id="verification_code" placeholder="{{ __("Saisissez le code ici") }}" required>
                <div class="valid-feedback fw-bold">
                  
                </div>
                <div class="invalid-feedback fw-bold">
                </div>
              </div>
             
              <div id="verify_button" class="mt-3">
                <button id="verify" type="button"  class="w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                 {{ __("Verifier le code") }}
                </button>
              </div> 
             
              
              <div id="loader" class="d-none d-flex justify-content-center mt-3">
                
                  <div class="inner-loading dot-flashing"></div>
                
              </div>
               {{-- <div class="mt-3">
                <a href="{{ url('login') }}">{{ __("Se connecter") }}</a>
              </div> --}}
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
    
  
  
      $("#verify").click(function (e) {
  
        let data_send = {},
      inputs=$('.form-control');
       $.map(inputs, function (input, indexOrKey) {
      $(`#${input.id}`).removeClass('is-invalid');
      $(`#${input.id}`).removeClass('is-valid');
  
      data_send[input.id]=input.value;
  
      });
  
        $("#verify_button").toggleClass("d-none");
        $("#loader").toggleClass("d-none");
        e.preventDefault();
  
        $.ajax({
          type: "post",
          url: "{{ url('user/verify-account') }}",
          data: data_send,
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
          dataType: "json",
          success: function(data, textStatus, xhr) {
          if (data.status) { 
            $(`#verification_code`).siblings('.valid-feedback').text(data.data);
            $(`#verification_code`).addClass('is-valid');

            window.location = data.redirect_to;  
          } 
          else{
            
            $.each(data.errors, function(key,value) {
       $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
       $(`#${key}`).addClass('is-invalid');
  
      });
       

      $("#verify_button").toggleClass("d-none");
        $("#loader").toggleClass("d-none");
       
    
          }
      },
      error: function (xhr) {
        
        
     $.each(xhr.responseJSON.errors, function(key,value) {

       $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
       $(`#${key}`).addClass('is-invalid');
  
      });

      $("#verify_button").toggleClass("d-none");
        $("#loader").toggleClass("d-none");
  
      
    
  },
      complete: function(xhr, textStatus) {
        
      } 
        });
  
        
    
      });
  
    });
    </script>
      

@endsection