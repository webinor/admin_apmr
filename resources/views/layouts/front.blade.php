<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? "My Transfert" }}</title>
  <!-- plugins:css -->
  {{-- <link rel="stylesheet" href="{{asset('zen/')}}/vendors/feather/feather.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/css/vendor.bundle.base.css"> --}}
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('zen/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('wordpress/2022/06/logo.png')}}" />
  @include('layouts.partials._loader_style') 
</head>


<body>

  @yield('content')

  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{asset('zen/')}}/vendors/js/vendor.bundle.base.js"></script>
  {{--<!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{asset('zen/')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('zen/')}}/js/off-canvas.js"></script>
  <script src="{{asset('zen/')}}/js/hoverable-collapse.js"></script>
  <script src="{{asset('zen/')}}/js/template.js"></script>
  <script src="{{asset('zen/')}}/js/settings.js"></script>
  <script src="{{asset('zen/')}}/js/todolist.js"></script>
  <script src="{{asset('js/app.js')}}"></script>--}}
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
  <!-- endinject -->
  
  @yield('custom_js')
 
</body>

</html>
