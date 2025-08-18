<!DOCTYPE html>
<html lang="fr">

<head> 
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? "App AI" }}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('zen2')}}/vendors/feather/feather.min.css">
  <link rel="stylesheet" href="{{asset('zen')}}/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{asset('zen2')}}/vendors/ti-icons/css/themify-icons.min.css">

  <link rel="shortcut icon" href="{{asset('wordpress/2022/06/logo.png')}}" />


  {{--<link rel="stylesheet" href="{{asset('zen/')}}/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/css/vendor.bundle.base.css">{{--
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css">--}}


<link media="all"  rel="preload" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link media="all"  rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css"></noscript>
  <!-- endinject -->

  <link rel="shortcut icon" href="{{asset('logo.png')}}"  />
  
  @yield('custom_css')


  <link rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/loader.min.css">
  {{-- <link rel="stylesheet" href="{{asset('zen2')}}/css/chat/chat.min.css"> --}}
  @if ($_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != '127.0.0.1')
       <script src="https://kit.fontawesome.com/93901d031a.js" crossorigin="anonymous"></script>
   @else
       <link rel="stylesheet" href="{{ asset('fontawesome-free-6.4.2-web') }}/css/all.css" />

  @endif
  <style>
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
  </style>
</head>

<body class="sidebar-dark">
  <div class="container-scroller">
    <!-- partial -->
    <nav class="d-none navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      
      <div class="navbar-menu-wrapper d-flex align-items-top"> 

        {{--custom header--}}
        @yield('custom_header')
        {{--top bar--}}
       
        {{--End top bar--}}

       {{--  <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button> --}}
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper {{ ($wrapper_padding != null) ? 'pt-'.$wrapper_padding : '' }}" >
     
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      
      <!-- partial -->


      <div class="main-panel w-100">
        
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


  <!-- container-scroller -->
    @yield('custom_modal')
    
{{--  <script src="{{asset('js/app.min.js')}}"></script>--}}

  <!-- plugins:js -->
  <script src="{{asset('zen/')}}/vendors/js/vendor.bundle.base.js"></script>
  {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js"></script>--}}
  <script src="{{asset('zen2')}}/js/off-canvas.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  {{--
  
  <script src="{{asset('zen/')}}/vendors/chart.js/Chart.min.js"></script>
  <script src="{{asset('zen/')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="{{asset('zen/')}}/vendors/progressbar.js/progressbar.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('zen/')}}/js/off-canvas.js"></script>
  <script src="{{asset('zen/')}}/js/hoverable-collapse.js"></script>

  --}}
  
  <script src="{{asset('zen2')}}/js/template.min.js"></script>
  {{--
  
  <script src="{{asset('zen/')}}/js/settings.js"></script>
  <script src="{{asset('zen/')}}/js/todolist.js"></script>
  
  --}}
  <!-- endinject -->

  <!-- Custom js for this page-->
  {{--<script src="{{asset('zen/')}}/js/jquery.cookie.js" type="text/javascript"></script>--}}
  {{--<script src="{{asset('zen/')}}/js/dashboard.js"></script>--}}
  {{--<script src="{{asset('zen/')}}/js/Chart.roundedBarCharts.js"></script>--}}
  {{--<script type="text/javascript" src="{{asset('zen')}}/js/local.js"></script>--}}
  <script src="{{asset('js/aaautils.js')}}"></script>


<!-- custom js for this page-->
@yield('custom_js')
<!-- End custom js for this page-->

</body>

</html>

