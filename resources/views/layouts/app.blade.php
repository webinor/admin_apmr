<!DOCTYPE html>
<html lang="fr">

<head> 
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? "Cameroun Assistance Sanitaire" }}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('zen2')}}/vendors/feather/feather.min.css">
  <link rel="stylesheet" href="{{asset('zen')}}/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{asset('zen2')}}/vendors/ti-icons/css/themify-icons.min.css">
  <link rel="stylesheet" href="{{ asset('zen/vendors/simple-line-icons/css/simple-line-icons.css') }}">


  <link rel="shortcut icon" href="{{asset('wordpress/2022/06/LOGO_CAMEROUN_ASSIST.png')}}" />


 <link rel="stylesheet" href="{{asset('zen/')}}/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css">{{--  --}}


<link media="all"  rel="preload" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link media="all"  rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css"></noscript>
  <!-- endinject -->

  {{-- <link rel="shortcut icon" href="{{asset('LOGO_CAMEROUN_ASSIST.png')}}"  /> --}}
  
  @yield('custom_css')


  <link rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/loader.min.css">
  {{-- <link rel="stylesheet" href="{{asset('zen2')}}/css/chat/chat.min.css"> --}}
  @if ($_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != '127.0.0.1')
       <script src="https://kit.fontawesome.com/93901d031a.js" crossorigin="anonymous"></script>
   @else
       <link rel="stylesheet" href="{{ asset('fontawesome-free-6.4.2-web') }}/css/all.css" />

  @endif

  {{-- .home-tab .nav-tabs .nav-item  --}}
  <style>

.main-panel .nav-link.active {
    background-color: #85858547 !important;
    font-weight: bold !important;
    color: #73b31f;
}
select.form-control{
  background-color: #FFF;
  color: #000;
  outline: 1px solid #30303071;
}

.form-control{
  outline: 1px solid #30303071;
}
     .logged-in {
    color: green;
  }
  
  .logged-out {
    color: red;
  } 
    #chat-button-container{
      position: fixed;
      bottom: 50px;
      right: 50px;
    }
    .cover_image{
      background-color: #48e2433d;
    }



#fullscreen-loader-neon {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backdrop-filter: blur(3px);
  background-color: rgba(226, 226, 226, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  display: none;
}

.loader-neon {
  position: relative;
  width: 100%;
  height: 100%;
}

.loader-neon .circle {
  position: absolute;
  top: 50%;
  left: 50%;
  border: 6px solid transparent;
  border-top-color: #3498db;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  animation: spinNeon 1.6s linear infinite;
  box-shadow: 0 0 20px rgba(52, 152, 219, 0.8);
  width: 120px;
  height: 120px;
}

.loader-neon .neon2 {
  width: 90px;
  height: 90px;
  border-top-color: #e74c3c;
  animation-duration: 1.2s;
  animation-direction: reverse;
  box-shadow: 0 0 20px rgba(231, 76, 60, 0.8);
}

.loader-neon .neon3 {
  width: 60px;
  height: 60px;
  border-top-color: #f1c40f;
  animation-duration: 0.9s;
  box-shadow: 0 0 20px rgba(241, 196, 15, 0.8);
}

@keyframes spinNeon {
  0% { transform: translate(-50%, -50%) rotate(0deg); }
  100% { transform: translate(-50%, -50%) rotate(360deg); }
}

  </style>
</head>

<body class="sidebar-dark">

  <!-- Loader Fullscreen Ultra Premium Neon -->
<div id="fullscreen-loader-neon">
  <div class="loader-neon">
    <div class="circle neon1"></div>
    <div class="circle neon2"></div>
    <div class="circle neon3"></div>
  </div>
</div>


  <div class="container-scroller">
    <!-- partial -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{url('/')}}">
            <img style="width: 100px; height: 100px;"  src="{{asset('wordpress/2022/06/LOGO_CAMEROUN_ASSIST.png')}}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/')}}">
            <img style="width: 100px; height: 100px;" src="{{asset('wordpress/2022/06/LOGO_CAMEROUN_ASSIST.png')}}" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 

        {{--custom header--}}
        @yield('custom_header')
        {{--top bar--}}
        @include('layouts.partials._topbar')
        {{--End top bar--}}

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
     
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      @include('layouts.partials._sidebar')
      <!-- partial -->


      <div class="main-panel">
        
        <div class="content-wrapper {{Request::segment(1)}}-page">
            @yield('content')
        </div>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        {{--

        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"><strong>GECAM</strong> <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
          </div>
        </footer>
        
        --}}
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>

        
    </div>
    

  <input type="hidden" id="current_user" value="{{--session('user')->id--}}">
  <input type="hidden" id="chat-alert-sound" value="{{ asset('sound/chat_notification.mp3') }}">

 {{-- <audio id="chat-alert-sound" style="display: none">
      <source src="{{ asset('sound/chat_notification.mp3') }}" />
  </audio>	--}}

  @include('layouts.partials._modal_delete') 

  <!-- container-scroller -->
    @yield('custom_modal')
    
{{--  <script src="{{asset('js/app.min.js')}}"></script>--}}

  <!-- plugins:js -->
  <script src="{{asset('zen/')}}/vendors/js/vendor.bundle.base.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js"></script>--}}
  <script src="{{asset('zen2')}}/js/off-canvas.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  {{----}}
  
  <script src="{{asset('zen/')}}/vendors/chart.js/Chart.min.js"></script>
  <script src="{{asset('zen/')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="{{asset('zen/')}}/vendors/progressbar.js/progressbar.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->

 

  {{-- <script src="{{asset('zen/')}}/js/off-canvas.js"></script> --}}
  <script src="{{asset('zen/')}}/js/hoverable-collapse.js"></script>


  
  <script src="{{asset('zen/js/template.js')}}"></script>
{{--   <script src="{{asset('zen2')}}/js/template.min.js"></script> --}}
  {{-----}}
  
  <script src="{{asset('zen/')}}/js/settings.js"></script>
  <script src="{{asset('zen/')}}/js/todolist.js"></script>
  
   {{----}}
  <!-- endinject -->

  <!-- Custom js for this page-->
  {{----}}<script src="{{asset('zen/')}}/js/jquery.cookie.js" type="text/javascript"></script> {{----}}
  {{----}}<script src="{{asset('zen/')}}/js/dashboard.js"></script> {{----}}
  {{----}}<script src="{{asset('zen/')}}/js/Chart.roundedBarCharts.js"></script> {{----}}
  {{----}}<script type="text/javascript" src="{{asset('zen')}}/js/local.js"></script> {{----}}
  <script src="{{asset('js/aaautils.js')}}"></script>
  <script>
    const togglePassword = document.querySelector('#togglePassword');
 const password = document.querySelector('#password');

 if (togglePassword) {

 togglePassword.addEventListener('click', function (e) {
  //console.log(togglePassword);
   // toggle the type attribute
   const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
   password.setAttribute('type', type);
   // toggle the eye slash icon
   this.classList.toggle('fa-eye-slash');
}); 

}
 </script>
  <script>
      $("#results").change(function (e) { 
     

     //   $(".form-results").submit();
   // e.preventDefault();

   $('#form-results').submit();

   // console.log($('#form-results'));
    
    
  });

  $("#search").click(function (e) { 

e.preventDefault();

$("#form-search .loader").removeClass("d-none");
$("#form-search #search").addClass("d-none");
console.log("submit");

$("#form-search").submit();

});
  </script>

<!-- custom js for this page-->
@yield('custom_js')
<!-- End custom js for this page-->

</body>

</html>

