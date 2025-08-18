<div class="card" >




  <div class="card-body p-2 row justify-content-center">
      

   <form id="form-search" action="" method="get">

    
    <input id="typeahead_url" type="hidden"  value="{{ $typeahead_url }}" >
    <input id="extractor_typeahead_url" type="hidden"  value="{{ isset($extractor_typeahead_url) ? $extractor_typeahead_url : '' }}" >
    <input id="provider" type="hidden"  value="{{ isset($folder )? $folder->slip->provider->code : '' }}" >
    <div class="form-group row m-0">
     {{--  <label for="qry" class="fw-bold col-sm-3">{{ $query_label }}</label> --}}
      <div class="wrapper col-sm-7 mb-0 mb-sm-0">
        <input type="text" name="qry" class="fw-bold autocomplete form-control" value="{{-- $lawyer_params ?? '' --}}" id="qry" placeholder="{{ $query_label }}" required />
        <div class="valid-feedback d-block">
        </div>
        <div class="invalid-feedback  d-block">
        </div>
      </div>
  

    <div id="create_button" class="mt-0 col-sm-5">
      <button id="search" type="button"  class="text-white w-100 m-0 btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
        {{ 'Lancer la recherche' }}
      </button>
    </div> 
  </div> 
    
    <div  class="loader d-none d-flex justify-content-center mt-3">
      
        <div class="inner-loading dot-flashing"></div>
      
    </div>

   </form>
  </div>
  
</div>