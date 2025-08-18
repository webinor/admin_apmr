<!DOCTYPE html>
<html lang="fr">

<head> 
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>My Transfert</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('zen2')}}/vendors/feather/feather.min.css">
  <link rel="stylesheet" href="{{asset('zen')}}/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{asset('zen2')}}/vendors/ti-icons/css/themify-icons.min.css">
  <link rel="shortcut icon" href="{{asset('wordpress/2022/06/logo.png')}}" />

  {{--<link rel="stylesheet" href="{{asset('zen/')}}/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/css/vendor.bundle.base.css">{{----}}
  <!-- endinject -->
  <!-- Plugin css for this page -->
  {{--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/js/select.dataTables.min.css">--}}
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  {{--<link rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css">--}}
<link media="all"  rel="preload" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link media="all"  rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/style.min.css"></noscript>
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('wordpress/2022/6')}}/logo.png"  />
  @yield('custom_css')
  <link rel="stylesheet" href="{{asset('zen2')}}/css/vertical-layout-light/loader.min.css">
  <link rel="stylesheet" href="{{asset('zen2')}}/css/chat/chat.min.css">
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
    {{--<div class="row p-0 m-0 proBanner" id="proBanner">
        <div class="col-md-12 p-0 m-0">
          <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
            <div class="ps-lg-1">
              <div class="d-flex align-items-center justify-content-between">
                <p class="mb-0 font-weight-medium me-3 buy-now-text">Free 24/7 customer support, updates, and more with this template!</p>
                <a href="https://www.bootstrapdash.com/product/star-admin-pro/?utm_source=organic&utm_medium=banner&utm_campaign=buynow_demo" target="_blank" class="btn me-2 buy-now-btn border-0">Get Pro</a>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <a href="https://www.bootstrapdash.com/product/star-admin-pro/"><i class="mdi mdi-home me-3 text-white"></i></a>
              <button id="bannerClose" class="btn border-0 p-0">
                <i class="mdi mdi-close text-white me-0"></i>
              </button>
            </div>
          </div>
        </div>
      </div>--}}
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{url('/')}}">
            <img src="{{asset('wordpress/2022/06/logo.png')}}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/')}}">
            <img src="{{asset('wordpress/2022/06/logo.png')}}" alt="logo" />
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
      @include('layouts.partials._ext_sidebar')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper {{Request::segment(1)}}-page">
           {{-- @if ($errors->has('error'))
            <div class="alert alert-danger" role="alert">
              <h6 class="alert-heading">{{$errors->first('error')}}</h6>
            </div>   
            @endif--}}
            @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        {{--<footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"><strong>GECAM</strong> <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
          </div>
        </footer>--}}
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends 
    <div id="chat-button-container">
      <button id="chat-button" class="shadow-sm add btn btn-icons btn-rounded btn-primary text-white me-0 pl-12p">
        <i class="mdi mdi-wechat"></i>
      </button>
    </div>-->
        
    </div>
    <!-- Autoplay is allowed. -->
  {{--<iframe id="chat-alert-sound" style="display: none" src="{{ asset('sound/chat_notification.mp3') }}" allow="autoplay">
  --}}
  <input type="hidden" id="current_user" value="{{--session('user')->id--}}">
  <input type="hidden" id="chat-alert-sound" value="{{ asset('sound/chat_notification.mp3') }}">
 {{-- <audio id="chat-alert-sound" style="display: none">
      <source src="{{ asset('sound/chat_notification.mp3') }}" />
  </audio>	--}}
  <!-- container-scroller -->
    @yield('custom_modal')

   {{-- <div id="chat-list">

      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Accordion Item #1
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
            </div>
          </div>
        </div>
       
        
      </div>


    </div>--}}
    
{{--  <script src="{{asset('js/app.min.js')}}"></script>--}}

  <!-- plugins:js -->
  <script src="{{asset('zen/')}}/vendors/js/vendor.bundle.base.js"></script>
  {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js"></script>--}}
  <script src="{{asset('zen2')}}/js/off-canvas.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  {{--<script src="{{asset('zen/')}}/vendors/chart.js/Chart.min.js"></script>
  <script src="{{asset('zen/')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="{{asset('zen/')}}/vendors/progressbar.js/progressbar.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('zen/')}}/js/off-canvas.js"></script>
  <script src="{{asset('zen/')}}/js/hoverable-collapse.js"></script>
  --}}<script src="{{asset('zen2')}}/js/template.min.js"></script>
  {{--<script src="{{asset('zen/')}}/js/settings.js"></script>
  <script src="{{asset('zen/')}}/js/todolist.js"></script>--}}
  <!-- endinject -->
  <!-- Custom js for this page-->
  {{--<script src="{{asset('zen/')}}/js/jquery.cookie.js" type="text/javascript"></script>--}}
  {{--<script src="{{asset('zen/')}}/js/dashboard.js"></script>
  <script src="{{asset('zen/')}}/js/Chart.roundedBarCharts.js"></script>
  <script type="text/javascript" src="{{asset('zen')}}/js/local.js"></script>--}}
  <script>
$(function () {
  

var protocol = location.protocol === 'https:' ? "https://" : "http://"  ;
var host = location.host;// document.domain ;

$("#chat-button").click(function (e) { 
  //window.location.href = `${protocol}${host}/chat`;
  //e.preventDefault();
});


 

$(".instances_lines").on( 'click' , '.delete' ,function (e) { 
 
 let closest_table =  $(this).closest("table"),
 closest_tr =  $(this).closest("tr"),
 instance_code = $(this).siblings("input").val(),
 type = $( `#${closest_table.attr('id')}`).data("type"),
 link = $( `#${closest_table.attr('id')} `).data("url");
 
 //console.log(closest_table.attr('id'));

 //console.log(instance_code);
  //console.log(link);

  deleteInstance(instance_code , closest_tr , link , type);


e.preventDefault();

});


const deleteInstance = (instance , closest_tr , link , type) => {

//$(".delete").addClass("d-none");
   
$(`.${type}_${instance}`).toggleClass("d-none");

let data_send = {};

data_send['instance']=instance;

//sales/quote/delete
$.ajax({
  type: "DELETE",
  url: protocol+host+`/api/${link}/${instance}`,
  data: data_send,
headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
  dataType: "json",
  success: function(data, textStatus, xhr) {
  if (data.status) {

    closest_tr.remove();
    
  //  //console.log(data);
  
  $(`.${type}_${instance}`).toggleClass("d-none");


  //$(".delete").removeClass("d-none");


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
  @yield('custom_js')
<script>
$(function () {
  
$('.lazy').Lazy({
           // your configuration goes here
           //scrollDirection: 'vertical',
           //delay: 2000,
           //appendScroll: $('#lazy-container'),
               effect: 'fadeIn',
               effectTime: 1000,
          //threshold: 0,
               //visibleOnly: true,
               onError: function(element) {
                   //console.log('error loading ' + element.data('src'));
               },
               beforeLoad: function(element) {
                   // called before an elements gets handled
                //   //console.log(`image ${element.data('src')} is about to be loaded`);
               },
               afterLoad: function(element) {
                   // called after an element was successfully handled
                //   //console.log(`image ${element.data('src')} has been loaded`);
               },
               onFinishedAll: function() {
            if( !this.config("autoDestroy") )
                this.destroy();
        }
         }); 

});
//Pusher.logToConsole = false;
 /* $(document).ready(function () {

     // Enable pusher logging - don't include this in production<script src="https://js.pusher.com/7.2/pusher.min.js">
      Pusher.logToConsole = false;

var pusher = new Pusher('aac706caf2f98547c9cb', {
  cluster: 'eu'
});

var channel = pusher.subscribe('posts');
channel.bind('PostCreated', function(data) {
  ////console.log(JSON.parse(data.data.post));
  alert(JSON.stringify(data));
//  $("#posts-count .rate-percentage").val(parseInt($("#posts-count .rate-percentage").val())+1);
 // $("#posts-count").text($("#posts-count .rate-percentage").val());
});/**/

 /*   Echo.private(`posts`)
    .listen('PostCreated', (e) => {
        //console.log(e.post);
      //  //console.log($("#posts_count_hidden").val());
      $("#posts_count_hidden").val(parseInt($("#posts_count_hidden").val())+1);
      $("#posts_count_hidden").siblings(".rate-percentage").text($("#posts_count_hidden").val());
    })
    .error((error) => {
        console.error(error);
    });/**/

   /* Echo.private(`chat.${1}`)
    .whisper('typing', {
        name: 'this.user.name'
    });*/
 /*
    Echo.join(`users-connexion`)
    .here((users) => {
      //  //console.log(users);
        $.each(users, function (indexInArray, user) { 
           $(`.user-login-status-${user.id}`).toggleClass("logged-out");
           $(`.user-login-status-${user.id}`).toggleClass("logged-in");
        //console.log(`user ${user.id} is already logged in`);
        });
    })
    .joining((user) => {
        //console.log(`${user.email} Joined the chat`);
        $(`.user-login-status-${user.id}`).addClass("logged-in");
        $(`.user-login-status-${user.id}`).removeClass("logged-out");
    })
    .leaving((user) => {
      //console.log(`${user.email} leave the chat`);
      $(`.user-login-status-${user.id}`).addClass("logged-out");
           $(`.user-login-status-${user.id}`).removeClass("logged-in");

    })
    .error((error) => {
        console.error(error);
    });


/**/

$(document).ready(function(){
	
  var preloadbg = document.createElement("img");
  preloadbg.src = "https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/timeline1.png";
  
	$("#searchfield").focus(function(){
		if($(this).val() == "Search contacts..."){
			$(this).val("");
		}
	});
	$("#searchfield").focusout(function(){
		if($(this).val() == ""){
			$(this).val("Search contacts...");
			
		}
	});
	
	$("#sendmessage input").focus(function(){
		if($(this).val() == "Send message..."){
			$(this).val("");
		}
	});
	$("#sendmessage input").focusout(function(){
		if($(this).val() == ""){
			$(this).val("Send message...");
			
		}
	});
		
	
	$(".friend").each(function(){		
		$(this).click(function(){
			var childOffset = $(this).offset();
			var parentOffset = $(this).parent().parent().offset();
			var childTop = childOffset.top - parentOffset.top;
			var clone = $(this).find('img').eq(0).clone();
			var top = childTop+12+"px";
			
			$(clone).css({'top': top}).addClass("floatingImg").appendTo("#chatbox");									
			
			setTimeout(function(){$("#profile p").addClass("animate");$("#profile").addClass("animate");}, 100);
			setTimeout(function(){
				$("#chat-messages").addClass("animate");
				$('.cx, .cy').addClass('s1');
				setTimeout(function(){$('.cx, .cy').addClass('s2');}, 100);
				setTimeout(function(){$('.cx, .cy').addClass('s3');}, 200);			
			}, 150);														
			
			$('.floatingImg').animate({
				'width': "68px",
				'left':'108px',
				'top':'20px'
			}, 200);
			
			var name = $(this).find("p strong").html();
			var email = $(this).find("p span").html();														
			$("#profile p").html(name);
			$("#profile span").html(email);			
			
			$(".message").not(".right").find("img").attr("src", $(clone).attr("src"));									
			$('#friendslist').fadeOut();
			$('#chatview').fadeIn();
		
			
			$('#close').unbind("click").click(function(){				
				$("#chat-messages, #profile, #profile p").removeClass("animate");
				$('.cx, .cy').removeClass("s1 s2 s3");
				$('.floatingImg').animate({
					'width': "40px",
					'top':top,
					'left': '12px'
				}, 200, function(){$('.floatingImg').remove()});				
				
				setTimeout(function(){
					$('#chatview').fadeOut();
					$('#friendslist').fadeIn();				
				}, 50);
			});
			
		});
	});			
});

</script>
  <!-- End custom js for this page-->
</body>

</html>

