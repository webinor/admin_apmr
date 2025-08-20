@extends('layouts.front' , ['title' => "Connexion"])

@section('content')
    
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper" style="height: 100vh;">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="shadow-lg mx-5 rounded row w-100 h-75 mx-0 d-flex align-items-center justify-content-center">

          <div class="col-lg-4 h-100 px-0 align-items-center">
            <div class="d-flex flex-column justify-content-center  h-100 auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center d-block d-sm-block d-lg-none">
                <img style="width: 200px" src="{{asset('wordpress/2022/06/LOGO_CAMEROUN_ASSIST.png')}}" alt="logo">
              </div>
              @php 
                  if ($admin) {
                    $hello = "Hello,";
                    $form_title = "Connectez-vous pour acceder au tableau de bord";
                  } else {

                    if ($user) {
                     
                      $hello = "Bienvenue  $user->last_name,";  
                      $form_title = "Connectez-vous pour poster et gerer vos annonces";
                 
                    } else {
                     
                      $hello = "Salut,";  
                      $form_title = "Connectez-vous pour acceder au tableau de bord";

                    }
                    

                  }
                  
              @endphp
             
              <h4>{{ $hello }}</h4>
              <h6 class="fw-light">{{ $form_title }}</h6>   
             
              <form id="login_form" class="pt-3">
                @csrf
                <div class="form-group">
                  <input type="text" value="{{ $_SERVER['SERVER_ADDR'] == "127.0.0.1" ? "gabi@cas.com" : "" }}" value="{{ $user ? $user->email : '' }}" name="login" class=" form-control form-control-lg" id="login" placeholder="email ou numéro de téléphone" required>
                  <div class="valid-feedback">
                  </div>
                  <div class="invalid-feedback">
                  </div>
                </div>
                <div class="form-group">
                <input type="password" value="{{ $_SERVER['SERVER_ADDR'] == "127.0.0.1" ? "password" : "" }}" name="password" class="form-control form-control-lg" id="password" placeholder="mot de passe">
                {{-- <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;opacity: 0.8;"></i> --}}
                <div class="valid-feedback">
                </div>
                <div class="invalid-feedback">
                </div>
              </div>

              <div  class="form-check w-50">
                <label class="form-check-label text-start fw-bold">
                  <input id="togglePassword" type="checkbox" class="form-check-input"/>
                  Afficher le mot de passe
                <i class="input-helper"></i></label>
              </div>

                <div id="login_button" class="mt-3">
                  <button id="init_login" type="button"  class="w-100 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                    Connexion
                  </button>
                </div> 
                {{-- <div class="mt-3">
                  <a href="{{ $reset_password_link }}">Reinitialiser mon mot de passerr</a>
                </div>

                <div class="mt-3">
                  <a href="{{ $register_link }}">Creer rapidement un compte</a>
                </div> --}}
                
                <div id="loader" class="d-none d-flex justify-content-center mt-3">
                  
                    <div class="inner-loading dot-flashing"></div>
                  
                </div>
                <input type="hidden" value="{{ $login_end_point }}" id="login_end_point">
              </form>
            </div>
          </div>



          <div class="col-lg-8 h-100 px-0 d-lg-block d-none">
            
          
            <div class="h-100 d-lg-block d-none me-n8 " style="background: #c7c7c738;" >
                
              <div class="h-100 ms-auto  z-index-10 " style="object-fit: fill; ;background-image:url({{ asset('/wrdpress/2022/06/LOGO_CAMEROUN_ASSIST.png') }})">
                <img class="w-100 h-100" style="object-fit: contain;" src="{{ asset('/wordpress/2022/06/LOGO_CAMEROUN_ASSIST.png') }}" alt="" srcset="">
                {{--  --} class="w-100 h-100" style="object-fit: cover;" src="{{ asset('/wordpress/2022/06/rm373batch5-blogbanner-08.jpg') }}" alt="" srcset="">{{--  --}}
                
              
              </div>
            
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

if (navigator.onLine) {
    // Le navigateur est en ligne
    console.log("Connecté à Internet");
    // Exécuter le code lié à la connexion
  } else {
    // Le navigateur est hors ligne
    console.log("Pas connecté à Internet");
    // Exécuter le code lié à l'absence de connexion
  }
  </script>
  
  <script>
    $(document).ready(function () {
    
        $("#init_login").click(function (e) {
    
          if (!navigator.onLine) {
            alert("Vous n'etes pas connecté à internet");
            return null;
          }
         

          $("#login_button").toggleClass("d-none");
          $("#loader").toggleClass("d-none");

          let data_send = {},
    inputs=$('#login_form .form-control');
     $.map(inputs, function (input, indexOrKey) {
    $(`#login_form #${input.id}`).removeClass('is-invalid');
 data_send[input.id]=input.value;

    });

          e.preventDefault();
    
          $.ajax({
            type: "post",
            url: "{{ $login_end_point }}",
            data: data_send,
          headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            dataType: "json",
            success: function(data, textStatus, xhr) {
             // console.log(textStatus);
            if (data.status) {
    
              window.localStorage.setItem('token',data.token);
    
              window.location = data.redirect;
            } 
            else{
               $.each(data.errors, function(key,value) {
         $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
         $(`#${key}`).addClass('is-invalid');
     
        });

        $("#login_button").toggleClass("d-none");
          $("#loader").toggleClass("d-none");
         
         
      
            }
        },
        error: function (xhr) {

          
          /*if (xhr.status == "500" && xhr.responseJSON.message.contains("cURL error 6: Could not resolve host: pass24api.insighttelematics.tn")) {
            console.log(xhr.responseJSON.message);
          }*/
          
          if (xhr.status == 429) {
            
            //console.log(xhr.responseJSON.message);


            $(`#login`).siblings('.invalid-feedback').text(xhr.responseJSON.message);
            $(`#login`).addClass('is-invalid');


          }
          else if(xhr.status == 422){

          //  console.log(xhr.responseJSON.errors);
       //$('#validation-errors').html('');
       $.each(xhr.responseJSON.errors, function(key,value) {
         $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
         $(`#${key}`).addClass('is-invalid');
    
        });
    

          }
        

        $("#login_button").toggleClass("d-none");
          $("#loader").toggleClass("d-none");
        
       
      
    },
        complete: function(xhr, textStatus) {
         // $(`#login`).siblings('.invalid-feedback').text('xhr.responseJSON.message');
        } 
          });
    
          
      
        });
    
      });
      </script>

  @endsection
  