<ul class="navbar-nav ms-auto">

  @yield('custom_innerbar')

{{--  --}

<li class="nav-item d-none d-lg-block">
  <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
    <span class="input-group-addon input-group-prepend border-right">
      <span class="icon-calendar input-group-text calendar-icon"></span>
    </span>
    <input type="text" class="form-control">
  </div>
</li>

{{--  --}}


  
  <li class="d-none nav-item dropdown">
    <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
      <i class="icon-bell icon-lg"></i>
      <span class="count"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
      <a class="dropdown-item py-3 border-bottom">
        <p class="mb-0 font-weight-medium float-left">Vous avez 01 notification non lue </p>
        <span class="badge badge-pill badge-primary float-right">Tout afficher</span>
      </a>
      <a class="dropdown-item preview-item py-3">
        <div class="preview-thumbnail">
          <i class="mdi mdi-check m-auto text-primary"></i>
        </div>
        <div class="preview-item-content">
          <h6 class="preview-subject fw-normal text-dark mb-1">Extraction terminée</h6>
          <p class="fw-light small-text mb-0"> Maintenant </p>
        </div>
      </a>
      <a class="dropdown-item preview-item py-3">
        <div class="preview-thumbnail">
          <i class="mdi mdi-check m-auto text-primary"></i>
        </div>
        <div class="preview-item-content">
          <h6 class="preview-subject fw-normal text-dark mb-1">Extraction terminée</h6>
          <p class="fw-light small-text mb-0"> Il y'a 2 jours</p>
        </div>
      </a>
      <a class="dropdown-item preview-item py-3">
        <div class="preview-thumbnail">
          <i class="mdi mdi-clock m-auto text-primary"></i>
        </div>
        <div class="preview-item-content">
          <h6 class="preview-subject fw-normal text-dark mb-1">Extraction programmée</h6>
          <p class="fw-light small-text mb-0"> Il y'a 3 jours</p>
        </div>
      </a>
    </div>
  </li>

  <li class="d-none nav-item dropdown">
    <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
      <i class="icon-bell icon-lg"></i>
      <span class="count"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
      {{-- <a class="dropdown-item py-3 border-bottom">
        <p class="mb-0 font-weight-medium float-left">You have 4 new notifications </p>
        <span class="badge badge-pill badge-primary float-right">View all</span>
      </a> --}}
      @isset($notifications)
          
      @foreach ($notifications as $notification)
          
      <a class="dropdown-item preview-item py-3">
        <div class="preview-thumbnail">
          <i class="mdi mdi-airballoon m-auto text-primary"></i>
        </div>
        <div class="preview-item-content">
          <h6 class="preview-subject fw-normal text-dark mb-1">{{ $notification->data['body'] }}</h6>
          <p class="fw-light small-text mb-0"> 2 days ago </p>
        </div>
      </a>

      @endforeach

      @endisset

    </div>
  </li>

 {{--  <li class="nav-item dropdown"> 
    <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="icon-mail"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown">
      <a class="dropdown-item py-3">
        <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
        <span class="badge badge-pill badge-primary float-right">View all</span>
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item preview-item">
        <div class="preview-thumbnail">
          <img src="images/faces/face10.jpg" alt="image" class="img-sm profile-pic">
        </div>
        <div class="preview-item-content flex-grow py-2">
          <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
          <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
        </div>
      </a>
      <a class="dropdown-item preview-item">
        <div class="preview-thumbnail">
          <img src="images/faces/face12.jpg" alt="image" class="img-sm profile-pic">
        </div>
        <div class="preview-item-content flex-grow py-2">
          <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
          <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
        </div>
      </a>
      <a class="dropdown-item preview-item">
        <div class="preview-thumbnail">
          <img src="images/faces/face1.jpg" alt="image" class="img-sm profile-pic">
        </div>
        <div class="preview-item-content flex-grow py-2">
          <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
          <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
        </div>
      </a>
    </div>
  </li> --}}
  
  <li class="nav-item dropdown  user-dropdown">
    @php
   /* if (Auth::user()->employee->profile_url) {
                            $profile_path = asset('zen/images/profile_pictures/'.Auth::user()->employee->profile_url);
                          } else {
                            $profile_path = asset('zen/images/profile_pictures/undefined.png');
                          }*/
                          $profile_path = asset('zen/images/profile_pictures/undefined.png');

@endphp
    <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
      <img class="img-xs rounded-circle lazy" data-src="{{$profile_path}}" alt="Profile image">
      <span  class="logged-in">●</span>
      <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
      <div class="dropdown-header text-center">
      
        <img class="img-md rounded-circle lazy" style="width: 50px;height: 50px;" data-src="{{$profile_path}}" alt="Profile image">
        <p class="mb-1 mt-3 font-weight-semibold">{{Auth::user()->last_name}} {{Auth::user()->first_name}}</p>
        <p class="fw-light text-muted mb-0">{{Auth::user()->email}}</p>
      </div>
{{--       <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Mon profile <span class="badge badge-pill badge-danger">1</span></a>
 --}}
      {{--<a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages</a>
      <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
      <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>--}}
      <a href="{{route('user_logout')}}" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Deconnexion</a>
    </div>
  </li>
</ul>