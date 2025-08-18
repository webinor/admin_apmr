
         <div class="">

        <form
        style="background-color: rgb(255, 141, 35); "
          action="{{ url('/searchresults') }}"
          method="GET"
          class="search_box narrow-w form-search d-flex align-items-stretch flex-wrap flex-lg-nowrap justify-content-center mb-3 p-1 rounded-0"
          data-aos="fade-up"
          data-aos-delay="200",
          id="default_search"
        >
  
        <select class="form-control rounded-0 px-4 mb-1 me-0 mb-lg-0  me-lg-1" style="padding-bottom: 13px;"  name="category" id="category">
          {{-- <option value="">{{ __("What are you looking for ?") }}</option> --}}
          @foreach ($categories as $category)
          <option value="{{ $category->name }}" {{ Request::get('category') == $category->name ? 'selected' : '' }}>{{ __($category->name) }}</option>
      @endforeach
        </select>
        
        <select class="form-control rounded-0 px-4 mb-1 me-0 mb-lg-0  me-lg-1" style="padding-bottom: 13px;" name="furnished" id="furnished">
          {{-- <option value="">{{ __("What are you looking for ?") }}</option> --}}
          <option value="no" {{ Request::get('furnished') == 'no' ? 'selected' : '' }}>Non Meublé</option>
          <option value="yes" {{ Request::get('furnished') == 'yes' ? 'selected' : '' }}>Meublé</option>
          <option value="sell" {{ Request::get('furnished') == 'sell' ? 'selected' : '' }}>A vendre</option>
        </select>

        {{--  <select class="form-control rounded-0 px-4 mb-1 me-0 mb-lg-0  me-lg-1" style="padding-bottom: 13px;" name="city" id="city">
         
            @foreach ($cities as $city)
                <option value="{{ $city->name }}" {{ Request::get('city') == $city->name ? 'selected' : ($city->name == 'Douala' ? 'selected' : '') }}>{{ $city->name }}</option>
                @endforeach
        </select> --}}

        <input type="hidden" value="{{ Request::get('city') }}" class="form-control rounded-0 px-4 mb-1 me-0 mb-lg-0  me-lg-1" style="padding-bottom: 13px;" name="ci" id="city">
    
        
        @if (Request::path() == '/')
        
        <input type="hidden" value="date_desc" id="order" name="order">
            
        @endif
        <input type="text" placeholder="Bonamoussadi ou Douala" value="{{ Request::get('address') }}" class="form-control rounded-0 px-4 mb-1 me-0 mb-lg-0  me-lg-1" style="padding-bottom: 13px;" name="address" id="address">
     



        {{--<input
        id="address"
          type="text"
          name="query"
          class="form-control px-4 me-1 rounded-0"
          placeholder="{{ __("Address or City. e.g. Logpom") }}"
          value="{{session('query') != null ? session('query') : ''}}"
        />
          <input type="hidden" class="lat">
          <input type="hidden" class="long"> --}}
          <button id="search-button" type="button" data-form-id="default_search" class="search-button btn btn-primary rounded-0 w-100">

            <div  class="d-flex h-100 justify-content-center align-items-center">
              <div id="search-text" class="search-text item"><strong>{{ __("Search") }}</strong></div>
              <div  id="search-loader"  class="search-loader d-none item inner-loading white-dot-flashing"></div>
            
          </div>
            

          </button>
        </form> 
      </div>

       