
{{-- @dd(session('distincts_actions_map')) --}}

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

      <li class="nav-item">
          <a class="nav-link" href="{{url('/')}}">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">Accueil</span>
          </a>
      </li>

      @foreach (session('menus') as $menu)

      @php
          $should_display=true
      @endphp

      @foreach ($menu->submenus as $submenu)
      
      
      
  @if (in_array($submenu->id ,session('distincts_actions_map') ) && $should_display ==true) 
      
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="collapse" href="#{{$menu->slug}}" aria-expanded="false" aria-controls="{{$menu->slug}}">
      <i class="{{$menu->icon}}"></i>

      <span class="menu-title">{{$menu->name}}</span>
      <i class="menu-arrow"></i> 
    </a>
    <div class="collapse" id="{{$menu->slug}}">
      <ul class="nav flex-column sub-menu">
        
        @foreach ($menu->submenus as $submenu)
           
        @if (in_array($submenu->id ,session('distincts_actions_map') ) )
       
        <li class="nav-item"> <a class="nav-link" href="{{url("/$submenu->link")}}">{{$submenu->name}}</a></li>
       
        @endif
       
        @endforeach

      </ul>
    </div>
  </li>

  @php
  $should_display=true
@endphp
      @break
  @endif

  @endforeach
  

  @endforeach


</ul>
</nav>
