

{{--  --}}
<script>

    function initialize() {
  
      var input = document.getElementById('address');
  
   //   console.log(input);
  
      var autocomplete = new google.maps.places.Autocomplete(input,{
         // fields: ["name"]
      });
  
      autocomplete.addListener('place_changed', function () {
  
      var place = autocomplete.getPlace();
  
      // get_search(place.name);
  
    //  console.log(place);
  
      //This will get only the address
  input.value = place.name;
      if (place.geometry) {
              
        // place variable will have all the information you are looking for.
              
     console.log(place.address_components[1].long_name);
     console.log(place.name);
  
      $('#lat').val(place.geometry['location'].lat());
  
      $('#long').val(place.geometry['location'].lng());
  
      $("#address").val(place.name);
  
      $("#city").val(place.address_components[1].long_name);
  
  
      }
  
  
  
     // $("#address").val(place.name);
  
    //  $("#search_box").submit();
      
  
    });
  
  }
  
  //window.initialize = initialize;
    </script>
  {{-- --}}
  <script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB5DGr7QgnfsGNw73430khud9lev-bO-sg&callback=initialize&libraries=places"
  
  ></script> 