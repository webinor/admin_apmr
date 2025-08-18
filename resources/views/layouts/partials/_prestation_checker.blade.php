

   
   

    <div class="col-lg-12 grid-margin stretch-card" {{-- style="width: 88%;" --}}>
        <div class="card">
            <div class="card-body">
     
              <div class=" mb-4 ">
                <form id="form-results " class="row form-results" action="" method="get">
        
                  <div class="col-1">
                    <label for="results">Afficher</label>
                  </div>
                  <div class="col-2">
          
                    <select name="results" class="form-control" id="results">
                     {{--  <option value="5" {{ Request::get("results") == 5 ? 'selected' : ''}} >5</option> --}}
                      <option value="10" {{ Request::get("results") == 10 ? 'selected' : ''}} >10</option>
                      <option value="25" {{ Request::get("results") == 25 ? 'selected' : ''}} >25</option>
                      <option value="50" {{ Request::get("results") == 50 ? 'selected' : ''}} >50</option>
                      <option value="100" {{ Request::get("results") == 100 ? 'selected' : ''}} >100</option>
                    </select>
                  </div>
        
                </form>
               
               </div>
            

    

             
               

                <div class="d-none alert alert-success" role="alert">
                    <h6 class="alert-heading">Facture créee avec succes</h6>
                </div>
               



            @include('layouts.partials._check_prestation')


           


        
             
              
               
                
            </div>
        </div>
    </div>

