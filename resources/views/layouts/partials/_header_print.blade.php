<header>
  <div style="text-align: right">
    <span>{{$document ?? 'Devis GECAM'}}</span>
</div>
  <div style="float: left; margin-top: 30px;height:150px;;width: 20%;">
    <img style="" src="{{url('zen/images/logo.png')}}" alt="" >
  </div>
  <div style="float: left;font-size: 15px;height:150px;;width: 60%;">
    <h1 style="letter-spacing: 2px;" class="header_text">GECAM</h1>
    <h4 class="header_text">Groupe Echafaudeurs Cameroun</h4>
    <h4 class="header_text">Echafaudage, Construction métallique<br> Etude et réalisation des projets <br> Formation du personnel, BTP </h4>
    <h4 class="header_text">Douala-Cameroun</h4>
    {{--<h5 class="header_text">Tél. Standard: +237 620013274</h5>--}}
    <h5 class="header_text">Direction: (+237) 6 96 23 08 61 / 670 595 027</h5>
    <h5 class="header_text" style=" color: #ff7605">Web site: www.groupgecam.com ; @: contact@gecam-cm.com</h5>
  </div>
  <div style="float: left; margin-top: 30px;height:150px;;width: 20%;">
    @if ($image_path)
      <img style="width: 100px;height: 88px;" src="{{ asset("storage/customer_images/".$image_path)}}" alt="" >  
    @endif
    
  </div>
 
   
</header>