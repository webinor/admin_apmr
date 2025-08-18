{{-- <script src="{{ asset("common") }}/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('js/app.min.js') }}" ></script>
<script src="{{url('plugins/jquery-lazy/jquery.lazy.min.js')}}"></script>
<script src="{{ asset("common") }}/js/tiny-slider.js"></script>
<script src="{{ asset("common") }}/js/aos.js"></script>
<script src="{{ asset("common") }}/js/navbar.js"></script>
<script src="{{ asset("common") }}/js/counter.js"></script>
<script src="{{ asset("common") }}/js/custom.js"></script>

<script>
    $(function () {
      
      let previous_category= $("#default_search #category").val();
    
        console.log(previous_category);
     
      $("#default_search #category").change(function (e) { 
        let category= $(this).val();
        console.log(category);
        switch (category) {
          case "Land":
   
          $("#default_search #furnished").html(`<option value="no" >A Louer</option>
            <option value="sell" >A vendre</option>
            <option value="yes" >Terrain agricole</option>`);
            
            break;
  
            case "Office":
            case "Coworking Space":
            case "Wharehouse":
            case "Store":
            case "Shop":
  
          $("#default_search #furnished").html(`<option value="no" >A Louer</option>`);
            
            break;
  
            case "Appartment":
            case "Studio":
            case "BedRoom": 
            case"House":
          
         if (!["Appartment","Studio","BedRoom","House"].includes(previous_category)) {
          $("#default_search #furnished").html(`<option value="no">Non Meublé</option>
            <option value="yes">Meublé</option>
            <option value="sell" >A vendre</option>`);
         }
          
            break;
        
          default:
            break;
        }
  
        previous_category= $("#default_search #category").val();
        e.preventDefault();
        
      });
    });
  </script>
<script>

$(".dash").hover(function () {
        $(this).toggleClass("shadow");
        $(this).toggleClass("shadow-lg");
        
    }, function () {
        
        $(this).toggleClass("shadow");
        $(this).toggleClass("shadow-lg");
    }
);
   // console.log($(window.top).height());;
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
                   console.log('error loading ' + element.data('src'));
               },
               beforeLoad: function(element) {
                   // called before an elements gets handled
                //   console.log(`image ${element.data('src')} is about to be loaded`);
               },
               afterLoad: function(element) {
                   // called after an element was successfully handled
                //   console.log(`image ${element.data('src')} has been loaded`);
               },
               onFinishedAll: function() {
            if( !this.config("autoDestroy") )
                this.destroy();
        }
         });

       
</script>
<script>
    $(function () {
              $('.search-button').click(function (e) { 
               
                  e.preventDefault();
                  let form = $(this).data('form-id');
                 // console.log(form);
                  $(`#${form} .search-text`).addClass('d-none');
                  $(`#${form} .search-loader`).removeClass('d-none');
                  $(`#${form}`).submit();
              });
          });
  </script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
 const password = document.querySelector('#password');

 if (togglePassword) {
  

 togglePassword.addEventListener('click', function (e) {
   // toggle the type attribute
   const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
   password.setAttribute('type', type);
   // toggle the eye slash icon
   this.classList.toggle('fa-eye');
   this.classList.toggle('fa-eye-slash');
}); 

}
 </script>