@extends('layouts.app')


@section('custom_css')
    <link rel="stylesheet" href="{{ asset('zen2/css/typeahead/typeahead.css') }}">

    <style>
    .twitter-typeahead {
            width: 100% !important;
        }

        .success {
                background-color: rgb(126, 245, 152);
            }

            .green-icon {
                color: rgb(20 ,161, 51);
                cursor: pointer;
            }

            .reject {
                background-color: rgb(250, 193, 176);
            }

            .is_open{

background-color: rgb(218, 218, 218);


}

.is_not_open{

background-color: rgb(255, 255, 255);

}
    </style>
@endsection


@section('content')
<div class="row">
  <div class="fw-bold">Bordereaux > Bordereau Numero : {{ $slip->identification }}</div>
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">

          @if (session('user')->isExtractor())


 
          @if ($slip -> unsaved_unvalidated_folders($folders) > 0) 
           
      
       
       <li class="nav-item">
         <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">{{ $pending_title }}
           <span id="badge-info" class="badge fw-bold text-bg-info">{{  $slip -> unsaved_unvalidated_folders($folders) }}</span>

         </a>
       </li>


       @endif

       

      

         
   @if ($slip -> itemless_folders($folders) > 0) 


       
   <li class="nav-item">
     <a class="nav-link {{ $slip -> unsaved_unvalidated_folders($folders) == 0 ? "active ps-0" : "" }}" id="contact-tab" data-bs-toggle="tab" href="#demographics" tab-id="demographics" role="tab"
     aria-controls="demographics"  aria-selected="false">{{ $rejected_title }}
         <span id="badge-danger" class="badge fw-bold text-white text-bg-danger">{{ $slip -> itemless_folders($folders) }}</span> 
       </a>
 </li>


 @endif
 

 

     @if ($slip -> saved_validated_folders($folders) > 0 )

     <li class="nav-item">
       <a class="nav-link {{ $slip -> saved_validated_folders($folders) == $slip->folders->count() ? "active ps-0" : "" }}" id="profile-tab" data-bs-toggle="tab" href="#audiences" tab-id="audiences"
           aria-controls="audiences" role="tab" aria-selected="false">{{ $executed_title }}
           <span id="badge-success" class="badge fw-bold text-bg-success">{{ $slip -> saved_validated_folders($folders) }}</span> {{-- --}}
         </a>
   </li>

   @endif
   
  
              
          @elseif(session('user')->isValidator())
              
         {{--
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">{{ "Vue d'ensemble des dossiers"}}

            </a>
          </li>
            --}}
          @if ($slip -> get_conforms_or_inconforms_folders($folders)["conforms"] > 0)
 
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">{{ $pending_title }}
              <span id="badge-info" class="badge fw-bold text-bg-info">{{  $slip -> get_conforms_or_inconforms_folders($folders)["conforms"] }}</span>

            </a>
          </li>
          
          @endif 
     
         

        @if ( $slip -> get_conforms_or_inconforms_folders($filtered_folders)["inconforms"] > 0)
            
        <li class="nav-item">
          <a class="nav-link {{ $slip -> get_conforms_or_inconforms_folders($folders)["conforms"] == 0 ? "active ps-0" :"" }}" id="contact-tab" data-bs-toggle="tab" href="#demographics" tab-id="demographics" role="tab"
          aria-controls="demographics"  aria-selected="false">{{ $rejected_title }}
          <span id="badge-danger" class="badge fw-bold text-white text-bg-danger">{{ $slip -> get_conforms_or_inconforms_folders($filtered_folders)["inconforms"] }}</span> 
        </a>
      </li>
      
      @endif  
  
    

        @if ($slip -> saved_validated_folders($folders) > 0) 
        <li class="nav-item">
          <a class="nav-link {{ ($slip -> saved_validated_folders($folders) == $slip->folders->count()) ? "active ps-0" : "" }}" id="profile-tab" data-bs-toggle="tab" href="#audiences" tab-id="audiences"
              aria-controls="audiences" role="tab" aria-selected="false">{{ $executed_title }}
              <span id="badge-success" class="badge fw-bold text-bg-success">{{ $slip -> saved_validated_folders($folders) }}</span> 
            </a>
      </li>
       @endif
      @endif
    
    

       
        </ul>
        <div>
          <div class="btn-wrapper">
            
          @if (session('user')->isValidator() )

          <a href="#" data-bs-toggle="modal" data-bs-target="#filterModal" class="btn btn-info text-white"><i class="mdi mdi-filter"></i>Filter la recherche</a>
             
          @endif

          @if (session('user')->isExtractor() )

                @if ($slip -> saved_validated_folders($folders) != $slip->folders->count())
                    
                <a href="{{ url('extract/create?slip='.$slip->identification.'&provider='. $slip->get_provider()) }}" class="btn btn-primary text-white"><i class="icon-download"></i>Ajouter des factures à ce borderau</a>
                
                @endif
        
            @endif
            
            <a href="{{ url('slip') }}" class="btn btn-success text-white"><i
              class="icon-book"></i>Liste des borderaux</a>


   {{--  <a href="{{url('extract/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouvelle extraction</a>
   
   
    <a href="#" class="btn btn-success text-white me-0" ><i class="icon-clock"></i>Planifier une extraction</a>
 --}}
        
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        @if (false)

        {{-- <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
       
          @include('layouts.partials._folder_template' , [ 'folders' => $folders ,  'status' => '5'  /*, 'status' => 2*/ ])

        </div> --}}

        @else

                @if (session('user')->isExtractor())

                @include('layouts.partials._folder_extractor' , ['unsaved_unvalidated_folders'=> $slip -> unsaved_unvalidated_folders($folders) ,
                 'itemless_folders'=>$slip -> itemless_folders($folders) , 
                 'saved_validated_folders'=>$slip -> saved_validated_folders($folders),
                 'slip_folders'=>$slip->folders->count()
                 ])
                    
                @elseif(session('user')->isValidator())

                @include('layouts.partials._folder_validator' , ['conforms_folders'=>$slip -> get_conforms_or_inconforms_folders($folders)["conforms"],
                'inconforms_folders'=>$slip -> get_conforms_or_inconforms_folders($folders)["inconforms"],
                'saved_validated_folders'=>$slip -> saved_validated_folders($folders),
                'slip_folders'=>$slip->folders->count()])
                    
                @endif

        
            
        @endif
        

      </div>
    </div>
  </div>
</div>
@endsection


@section('custom_modal')

@include('layouts.partials._modal_delete') 
{{-- @include('layouts.partials._modal_filter') --}} 
@include('layouts.partials._modal_new_filter') 

@endsection



@section('custom_js')

<script src="{{ asset('zen/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('zen/js/typeahead.js') }}"></script>
<script src="{{ asset('plugins/sorttableJs/sorttable.js') }}"></script>
<script>
 
  $("#search").click(function (e) { 

    e.preventDefault();

    $("#form-search .loader").removeClass("d-none");
    $("#form-search #search").addClass("d-none");
    console.log("submit");

    $("#form-search").submit();
    
  });




  $("#filter-button").click(function (e) { 
    e.preventDefault();
    $("#filter-button-text").toggleClass("d-none");
    $("#filter-loader").toggleClass("d-none");
    $("#form_filter").submit();
    
  });


  $(".min-price").on('input' , function (e) { 
  e.preventDefault();
//  console.log($(this).val());
//  console.log($("#min-price").attr("title"));


  $(".min-price-value").text($(this).val());
//   var exampleTriggerEl = document.getElementById('example')
//var tooltip = bootstrap.Tooltip.getInstance(this)
//   tooltip.update()
//  $(this).attr("title", $(this).val());

});

$(".max-price").on('input' , function (e) {
  e.preventDefault();
//console.log($(this).val());
//console.log($("#max-price").attr("max"));

  $(".max-price-value").text($(this).val() == $(".max-price").attr("max") ? $(this).val()+'+':$(this).val());
//   var exampleTriggerEl = document.getElementById('example')
//var tooltip = bootstrap.Tooltip.getInstance(this)
//   tooltip.update()
//  $(this).attr("title", $(this).val());

}); 


  $('.prices').on('change',function (e) { 
  e.preventDefault();

 
  filter_search(this);
//console.log( $(this).val());

  
});

 

  $('.filter').change(function() {
   // console.log($(this));

   // console.log($('.filter'));

    filter_search(this)
    
    
        if(this.checked) {
          //  var returnVal = confirm("Are you sure?");
           // $(this).prop("checked", returnVal);
        }
       // $('#textbox1').val(this.checked);        
    });


    function filter_search(element) {
  
	
$("#filter-button-text").toggleClass("d-none");
$("#filter-loader").toggleClass("d-none");

 let data_send = {},
 url = $("#form_filter  #filter_url").val(),
 processData = true,
contentType = true,
method = "POST";
 


let inputs = $(`#form_filter .form-control`);


//data_send = getInputValues('form_filter', data_send ,  'form-control' );

$.map(inputs, function(input, indexOrKey) {


if ($(input).hasClass("form-check-input")) {

 // console.log(input.checked);
  
  
  if (input.checked) {
    
   // console.log("ouiiiiiiii");
    
    data_send[input.id]= input.value

  }
 

}
else{

 data_send[input.id] = input.value ;


}



});

console.log(data_send);

  let onSuccess = function(data, textStatus, xhr) {
    if (data.status) {

      $("#filter-button-text").text(data['filter-button-text']);
   // $(alert_success_node).removeClass("d-none");
    //$('.provider').val(data.data.provider.code);

  //  location.reload();

    
    } 
    else{

    responseError(data);
   
    }
  
  }, 

  onError = function(xhr) {
  
  console.log(xhr);
  
  handleXhrError(xhr);

} ,

  onComplete = function(xhr, textStatus) {
  
    $("#filter-button-text").toggleClass("d-none");
    $("#filter-loader").toggleClass("d-none");
  

}  ;


  let ajaxRequest = constructAjaxRequest(url , data_send , onSuccess , onError , onComplete , method , processData , contentType  );


  sendAjaxRequest( ajaxRequest  );
  


 

}




  
</script>

@endsection