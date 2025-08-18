<nav class="site-nav">
  <div class="container">
    <div class="menu-bg-wrap">
      <div class="site-navigation">
      @php
      $public_path = public_path('images/logo_white.png');    
      @endphp
        <a href="{{ url('/') }}" class="logo m-0 float-start">
          <img src="{{ asset('images/logo_white.png') }}" style="width: 50px;" alt="Image" class="lazy img-fluid" />
        </a>

        <ul
          class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end"
        >
          <li {{ Request::path() == '/' ? 'class=active' : '' }} ><a href="{{ url('/') }}">{{ __("Home") }}</a></li>
          {{-- <li class="has-children">
            <a href="{{ url('/') }}">Properties</a>
            <ul class="dropdown">
              <li><a href="#">Buy Property</a></li>
              <li><a href="#">Sell Property</a></li>
              <li class="has-children">
                <a href="#">Dropdown</a>
                <ul class="dropdown">
                  <li><a href="#">Sub Menu One</a></li>
                  <li><a href="#">Sub Menu Two</a></li>
                  <li><a href="#">Sub Menu Three</a></li>
                </ul>
              </li>
            </ul>
          </li> 
          <li {{ Request::path() == 'services' ? 'class=active' : '' }}><a href="{{ url('/') }}/services">{{ __("Services") }}</a></li>
         --}}  <li {{ (Request::path() == 'adverts' || Request::path() == 'property') ? 'class=active' : '' }}><a href="{{ url('/') }}/adverts">{{ __("Properties") }}</a></li>
        {{-- --}} <li {{ Request::path() == 'about' ? 'class=active' : '' }}><a href="{{ url('/') }}/about">{{ __("About") }}</a></li>
          <li {{ Request::path() == 'contact' ? 'class=active' : '' }}><a href="{{ url('/') }}/contact">{{ __("Contact Us") }}</a></li>
         
          @if (auth()->guard('admins')->check())
          
          <li><a  class="dash rounded-pill shadow" href="{{ url('admin') }}" target="_blank">{{ __("Dashboard") }}</a></li>
              
          @else
         
          <li><a  class="dash rounded-pill shadow" href="{{ url('user/common/ad/create') }}" target="_blank">{{ __("New ad") }}</a></li>
              
          @endif
   {{----}} 
          <li class="nav-item dropdown mx-3 hoverable rounded-5">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
             
           <img
           src="{{app()->getLocale() == "fr" ? asset('frenchflagframed.svg') : asset('Flag-of-the-United-Kingdom.svg') }}"
           width="30" height="30"
           alt="Flag"
           srcset="{{app()->getLocale() == "fr" ? asset('frenchflagframed.svg') : asset('Flag-of-the-United-Kingdom.svg') }}" />
          </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item {{app()->getLocale() == "en" ?  "fw-bold" : ""}}" href="{{url('changeLang' , ['en'])}}" >English</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item {{app()->getLocale() == "fr" ?  "fw-bold" : ""}}" href="{{url('changeLang' , ['fr'])}}" >Français</a></li>
              </ul>
          </li> 

        </ul>

        <a
          href="#"
          class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none"
          data-toggle="collapse"
          data-target="#main-navbar"
        >
          <span></span>
        </a>
      </div>
    </div>
  </div>
</nav>