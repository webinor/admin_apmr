@extends('layouts.app')

@section('custom_css')

@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des clients et leads</a>
          </li>
          {{--<li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
          </li>
          <li class="nav-item">
            <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">More</a>
          </li>--}}
        </ul>
        <div>
          <div class="btn-wrapper">
            {{--@if (Auth::user()->can('create', App\Models\Sales\Customer::class))--}}
    <!-- The current user can update the post... -->
    <a href="{{url('human_resource/employee/create')}}" class="btn btn-primary text-white me-0" ><i class="icon-download"></i>Nouveau collaborateur</a>

            {{--@endif--}}
            {{--<a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
           
            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Imprimer la liste</a>--}}
          </div>
        </div>
      </div>
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
          {{--<div class="row">
            <div class="col-sm-12">
              <div class="statistics-details d-flex align-items-center justify-content-between">
                <div>
                  <input type="hidden" id="posts_count_hidden" name="" value="{{$posts_count ?? "0"}}">
                  <p class="statistics-title">Chiffre d'affaire</p>
                  <h3 id="posts_count" class="rate-percentage">{{$posts_count ?? '500000'}}</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                </div>
                <div>
                  <p class="statistics-title">Nouveaux clients</p>
                  <h3 class="rate-percentage">7,682</h3>
                  <p id="user" class="text-danger d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                </div>
                <div>
                  <p class="statistics-title">Nouvelles Sessions</p>
                  <h3 class="rate-percentage">68</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68</span></p>
                </div>
                {{--<div class="d-none d-md-block">
                  <p class="statistics-title">Avg. Time on Site</p>
                  <h3 class="rate-percentage">2m:35s</h3>
                  <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                </div>
                <div class="d-none d-md-block">
                  <p class="statistics-title">New Sessions</p>
                  <h3 class="rate-percentage">68.8</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                </div>
                <div class="d-none d-md-block">
                  <p class="statistics-title">Avg. Time on Site</p>
                  <h3 class="rate-percentage">2m:35s</h3>
                  <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                </div>
              </div>
            </div>
          </div> --}}
          
          <div class="row">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Liste du personnel</h4>
                  {{--<p class="card-description">
                    Add class <code>.table-striped</code>
                  </p>--}} 
                  <div class="table-responsive">
                    <table class="instances_lines table table-striped" data-url="human_resource/employee/delete">
                      <thead>
                        <tr>
                          <th>
                            Profile
                          </th>
                          <th>
                            Nom & Prenom
                          </th>

                          <th>
                            email personnelle
                          </th>

                          <th>
                            email de bureau
                          </th>
                          
                          <th>
                            Adresse
                          </th>

                         
                          
                          <th>
                            Ajoutee le
                          </th>
                       
                       
                         <th>Action</th>
                         
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($employees as $employee)
                        @if ( $employee->personal_email!="kgabinmarcel@gmail.com")
                            
                        
                        <tr>
                          
                          <td>
                            <div class="preview-thumbnail">
                              @php
                                  if ($employee->profile_url) {
                                    $profile_path = asset("zen/images/profile_pictures/".$employee->profile_url);
                                  } else {
                                    $profile_path = asset('zen')."/images/profile_pictures/undefined.png";
                                  }
                                  
                              @endphp
                              @if ($employee->user)
                              <img data-src="{{$profile_path}}" alt="image" class="lazy img-sm profile-pic">
                              <span  class=" logged-out user-login-status user-login-status-{{$employee->user->id}}">●</span>
                              @endif
                            </div>
                            
                          </td>
                          <td>
                            {{$employee->last_name}} {{$employee->first_name}}
                          </td>
                         
                          <td>
                            {{$employee->personal_email}}
                          </td>

                          <td>
                            {{$employee->user ? $employee->user->email : ""}}
                          </td>

                          <td>
                            {{$employee->address}}
                          </td>

                          <td>
                            {{$employee->created_at}}
                            
                          </td>
                          <td>
                          <form> 
                             
                            @if ($employee->user)
                            <a id="reset_{{$employee->code}}" class="me-3 reset_password" href="#" ><i class=" mdi mdi-backup-restore "></i></a>
                            <input id="input_{{$employee->code}}" class="reset_password" type="hidden" value="{{$employee->code}}">
                            @endif
{{----}}

                              <a id="edit_{{$employee->code}}" target="_blank" class="me-3 edit" href="{{url('human_resource/employee/'.$employee->code.'/edit')}}"><i class="menu-icon mdi mdi-table-edit"></i></a>

                              @if ($employee->role_id == 8)
                              <a id="delete_{{$employee->code}}" class="delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                              <input id="input_{{$employee->code}}" type="hidden" value="{{$employee->code}}">
                              
                              @endif
                            
                              <div id="loader" class="d-none d-flex justify-content-center mt-3">
            
                                <div class="inner-loading dot-flashing"></div>
                              
                            </div>

                            </form> 
                          </td>

                        </tr>  
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection



@section('custom_js')

<script>
      
  $(document).ready(function () {
  
    var protocol = location.protocol === 'https:' ? "https://" : "http://"  ;
  var host = location.host;// document.domain ;

  $(".table").on( 'click' , '.reset_password' ,function (e) { 
 
    $(".delete").addClass("d-none");
   $(".edit").addClass("d-none");
$(".reset_password").addClass("d-none");
$(".loader").removeClass("d-none");


 let closest_tr =  $(this).closest("tr"),
  closest_table_id =  $(this).closest("table").attr('id'),
 id = $(this).siblings(".reset_password").val() ;
 
 console.log(id);

 e.preventDefault();
 


 
let urls = {
  user_access : `user_access/delete/${id}`,
  documents_table : `document/delete/${id}`,
  platforms_table : `platform/delete/${id}`,
  notes_table : `note/delete/${id}`,
}

let data_send = {};

data_send['employee_code']=id;


$.ajax({
  type: "post",
  url: protocol+host+`/api/access/reset`,
  data: data_send,
headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
beforeSend: function (xhr) {
    xhr.setRequestHeader('Authorization', `Bearer ${window.localStorage.getItem('token')}`);
},
  dataType: "json",
  success: function(data, textStatus, xhr) {
  if (data.status) {

    alert(`Un mail de réinitialisation a été envoyé à l'adresse ${data.user.email}`)
   // closest_tr.remove();
    

   $(".delete").removeClass("d-none");
   $(".edit").removeClass("d-none");
$(".reset_password").removeClass("d-none");
$(".loader").addClass("d-none");


  }
  else{
 


  }

},
error: function (xhr) {

},
complete: function(xhr, textStatus) {

} 
});



    

      
  
    });


  });

      </script>

@endsection