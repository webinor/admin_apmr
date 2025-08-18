@extends('layouts.ext',['wrapper_padding'=>'0' , 'nav'=>'d-none'])

@section('custom_css')
 <!-- fontawesome 6 stylesheet -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <!-- typeahead stylesheet -->
 <link rel="stylesheet" href="{{asset('plugins/typeahead/typeahead.css')}}"> 
            
<style>

select.form-control{
  background-color: #FFF;
  color: #000;
  outline: 1px solid #30303071;
}

.form-control{
  outline: 1px solid #30303071;
}

.covered{
  background-color: #50ca5038
}

.uncovered{
  background-color: #ca505040;
}

.waiting{
  background-color: #cabb5057;

}

#file-previews-return .items-preview{

  display: flex;
  flex-direction: column;
  align-items: center;

}
.twitter-typeahead{
                width: 100% !important;
              }
            

    .col-form-label{
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .dropzone {
        border: dashed 4px #ddd !important ;
        background-color: #f2f6fc;
        border-radius: 15px;
    }

    .dropzone .dropzone-container {
        /*padding: 2rem 0;*/
        width: 100%;
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        color: #8c96a8;
        z-index: 20;
    }

    .dropzone .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        visibility: hidden;
        cursor: pointer;
    }

    .file-icon{
        font-size: 60px;
    }
    .hr-sect {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        margin: 8px 0px;
    }
    .hr-sect:before,
    .hr-sect:after {
        content: "";
        flex-grow: 1;
        background: #ddd;
        height: 1px;
        font-size: 0px;
        line-height: 0px;
        margin: 0px 8px;
    }

    .items-preview{
  max-width: 100%;
  margin-bottom: 7rem !important;
  transform: scale(0);
  transition: 0.5s;
}

 .items-preview.animate{
  transform: scale(1);
}

.dropzone-transform{
  min-height: 50vh;
  transition: 1.5s;
}

#select-files{
  transition: .5s;
}

.dropzone-transform.animate{
  
}

/*#file-previews*/ .progress-bar {
    width: 0%;
    height: 5px;
    background-color: 
#4CAF50;
    margin-top: 5px;
    border-radius: 12px;
    text-align: center;
    color: 
#fff;
}
</style>
@endsection

@section('content')

<input id="folder-test"  type="hidden" value=''>


<div class="row">
  <div class="d-flex flex-column justify-content-start  auth-form-light text-left  px-4 ">
    <div class="brand-logo text-cent">
      <img style="width: 200px" src="{{asset('wordpress/2022/06/logo.png')}}" alt="logo">
    </div>
  </div>
</div>
    <div class="row">
      
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="mb-3 d-sm-flex align-items-center justify-content-center border-bottom">
                    
                  <h2 class="">Recherche d'une prestation</h2>
                    
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div id="audience_tab" class="row justify-content-center">
                          <div class="col-md-4 grid-margin stretch-card">
                            <div class="card" >


                                <div class="card-body row justify-content-center">
                                    

                                  <div class="form-group row m-2 align-items-center justify-column-center">



                                    <div for="query" class="col-lg-2 col-form-label mb-0 fw-bold d-none">Saisissez votre recherche ici</div>
                                    <div id="query-input" class="col-lg-12">
                                      <input type="text" id="query" name="query" value="{{-- RDF56YHE --}}" class="fw-bold form-control fw-bold" placeholder="{{ __("Saisissez votre cherche ici") }}" aria-label="{{ __("Indiquez le numero de bordereau ici") }}" aria-describedby="email-addon">
                                        
                                      <div class="valid-feedback  fw-bold"  style="display: block">
                                      </div>
                                      <div  id="login-feedback" class="invalid-feedback feedback-query fw-bold" style="display: block">
                                      </div>                  
                                                 
                                    </div>


                                    <div id="query-input" class="col-lg-12 mt-2">
                                    <select name="category" class="form-control form-control" id="category" placeholder="Indiquez une category">

                                      @forelse ($categories as $category)
                                   
                                      <option value="{{$category["value"]}}" {{Request::get("category") == $category["value"] ? "selected" : ""}}>{{Str::upper($category["name"])}}</option>
                                      
                                      @empty
                                  
                                      <option value="">Aucune categorie disponible</option>
                                  
                                      @endforelse
                                  
                                    </select>

                                  </div>



                                  </div>



<div class="form-group">
 
  <div class="valid-feedback">
  </div>
  <div class="invalid-feedback">
  </div>
</div>
                                
                                   
                                </div>
                            </div>
                        </div>


                       

                        <div>

                        <div id="results-init" class=" col-md-12 grid-margin stretch-card">
                          <div class="card" >


                              <div class="card-body row justify-content-center">
                                  

                                
                                <div  class="form-group row m-0 align-items-center">
                                  <div for="provider" class="col-lg-4 col-form-label mb-0 fw-bold">Saisissez votre recherche dans le champ de saisie situé au dessus</div>
                                  <div id="provider-input" class="col-lg-7">
                                                  
                                                 
                                  </div>
                                </div>

                              
                                 
                              </div>
                          </div>
                      </div>

                      </div>




                      <div class="row text-center justify-content-center ">

                        <div id="loader" class="d-none text-center col-md-1 grid-margin stretch-card">
                          

                            <div class="loader d-flex justify-content-center mt-3">

                              <div class="inner-loading dot-flashing"></div>

                          

                          
                          </div>
                      </div>
                      

                    </div>

                   

                      <div class="row">



                        <div id="results-none" class="d-none col-md-12 grid-margin stretch-card">
                          <div class="card" >

                           

                              <div class="card-body row justify-content-center">
                                  

                                
                                <div  class="form-group row m-0 align-items-center">
                                  <div for="provider" class="col-lg-4 col-form-label mb-0 fw-bold">resultat : Aucune prestation trouvée</div>
                                  <div id="provider-input" class="col-lg-7">
                                                  
                                                 
                                  </div>
                                </div>

                              
                                 
                              </div>
                          </div>
                      </div>
                      

                    </div>



                      <div class="row   justify-content-center">

                      <div class="results-container row col-md-6  justify-content-center">


                        {{--  --}
                     

                        <div  class="d-non col-md-12 grid-margin stretch-card">
                          <div class="card" >


                              <div class="card-body row justify-content-center">
                               

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Nom de la prestation</div>
                                  <div  class="col-lg-8">
                                    
                                    EFRED                 
                                               
                                  </div>
                                </div>

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Type de prestation</div>
                                  <div  class="col-lg-8">
                                    
                                    EFRED                 
                                               
                                  </div>
                                </div>

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Status</div>
                                  <div  class="col-lg-8">
                                    
                                    EFRED                 
                                               
                                  </div>
                                </div>

                                
                                

                              
                                 
                              </div>
                          </div>
                      </div>

                      <div  class="d-none col-md-12 grid-margin stretch-card">
                        <div class="card" >


                            <div class="card-body row justify-content-center">
                             

                              <div class="form-group row m-2 align-items-center">
                                <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Nom de la prestation</div>
                                <div  class="col-lg-8">
                                  
                                  EFRED                 
                                             
                                </div>
                              </div>

                              <div class="form-group row m-2 align-items-center">
                                <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Type de prestation</div>
                                <div  class="col-lg-8">
                                  
                                  EFRED                 
                                             
                                </div>
                              </div>

                              <div class="form-group row m-2 align-items-center">
                                <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Status</div>
                                <div  class="col-lg-8">
                                  
                                  EFRED                 
                                             
                                </div>
                              </div>

                              
                              

                            
                               
                            </div>
                        </div>
                    </div>
                      

                    {{--  --}}
                     

                    </div>
                  </div>


                    

                        </div>

                    </div>


                  
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_modal')
@endsection

@section('custom_js')

<script src="{{asset('js/app.min.js')}}"></script>

<script>

  $(function () {
    

    function search() {




      $("#results-init").addClass("d-none");
     


      
      var data_send = {},
      url = "/api/get-search",
      query = $("#query").val();
      category = $("#category").val();
      

      data_send['query']=query;
      data_send['category']=category;
      
    //  console.log(query);



if (query != "") {
  

  $("#loader").removeClass("d-none");
  let onSuccess = function(data, textStatus, xhr) {
      if (data.status) {


        let results = data.data.results;

     //   console.log(results.length);

     console.log($(".results-container"));
     


        if (results.length == 0) {
          
       //   console.log("ok ok");
          
        
          //$("#results"). removeClass("d-none");
          $("#results-none").removeClass("d-none");
          $("#results-none").removeClass("d-none");
          $(".results-container").html("");

        }

        else{


          $("#results"). removeClass("d-none");
          $("#results-none").addClass("d-none");



          let res = ``;



          results.forEach(element => {


            console.log(element);
            

            res +=`   <div  class="d-non col-md-12 grid-margin stretch-card">
                          <div class="card ${element.coverage.status}" >


                              <div class="card-body row justify-content-center">
                               

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Nom</div>
                                  <div  class="col-lg-8">
                                    
                                    ${element.name}                   
                                               
                                  </div>
                                </div>

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Categorie</div>
                                  <div  class="col-lg-8">
                                    
                                     ${element.prestation_type}                   
                                               
                                  </div>
                                </div>

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Status</div>
                                  <div  class="col-lg-8">
                                    
                                    ${element.coverage.coverage}                 
                                               
                                  </div>
                                </div>

                                
                                

                              
                                 
                              </div>
                          </div>
                      </div>`;/**/

        /*  res += ` <div class="col-md-6 grid-margin stretch-card">
                          <div class="card ${element.coverage.status} " >


                              <div class="card-body row justify-content-center">
                               

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Nom de la prestation</div>
                                  <div id="query-input" class="col-lg-8">
                                    
                                  ${element.name}                 
                                               
                                  </div>
                                </div>

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Type de prestation</div>
                                  <div id="query-input" class="col-lg-8">
                                    
                                     ${element.prestation_type}                   
                                               
                                  </div>
                                </div>

                                <div class="form-group row m-2 align-items-center">
                                  <div for="query" class="col-lg-4 col-form-label mb-0 fw-bold">Status</div>
                                  <div id="query-input" class="col-lg-8">
                                    
                                     ${element.coverage.coverage}                   
                                               
                                  </div>
                                </div>

                                
                                

                              
                                 
                              </div>
                          </div>
                      </div>`;*/

                      
                      
                    });
                    
                    
                    $(".results-container").html(res);
                    
                    $("#loader").addClass("d-none");



        }

        

      } else {
      $("#loader").removeClass("d-none");

        $.each(data.errors, function(key, value) {
          $(` #${key} `).siblings('.invalid-feedback').text(value[0]);
          $(` #${key} `).addClass('is-invalid');

        });

      }

    },

    onError = function(xhr) {

    

      let status = xhr.status;



      switch (status) {
        case 422:

          //console.log(xhr.responseJSON.errors);
          $.each(xhr.responseJSON.errors, function(key, value) {
            $(`#${key}`).addClass('is-invalid');

          });


          break;

        default:
          break;
      }


    },

    onComplete = function(xhr, textStatus) {

      // $(`#${form} #save_files_button`).toggleClass("d-none");
      // $(`#${form} .loader`).toggleClass("d-none");

  $("#loader").addClass("d-none");


    };



  ajaxRequest = constructAjaxRequest(url, data_send, onSuccess, onError, onComplete);


  // //console.log(ajaxRequest);

  sendAjaxRequest(ajaxRequest);

} else {
 
  $("#results").addClass("d-none");
  $("#resultas").html("");
  $("#results-none").removeClass("d-none");
  $(".results-container").html("");

  
}
      
    }

    $("#category").change(function (e) {

      search();

    });



    $("#query").keyup(function (e) { 
      

      search();


    });

  });

</script>




   
@endsection
