@extends('layouts.app')

@section('custom_css')
    {{--<link rel="stylesheet" href="{{asset('zen/')}}/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="{{asset('zen/')}}/vendors/css/vendor.bundle.base.css">--}}
@endsection

@section('custom_header')

 <ul class="navbar-nav">
  <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
    <h1 class="welcome-text">{{date("H")< "12" ? "Bonjour" : "Bonsoir"}} <span class="text-black fw-bold">{{session('user')->first_name ?? ""}} {{-- session('user')->last_name --}},</span></h1>
    <h3 class="welcome-sub-text">Un resumé de vos performances</h3>
  </li>
</ul> {{----}}

{{--!! QrCode::size(50)->generate('https://techvblogs.com/blog/generate-qr-code-laravel-8') !!--}}


@endsection


@section('content')



    <div class="row">
      <div class="col-sm-12">
        <div class="home-tab">
          <div class="d-sm-flex align-items-center justify-content-between border-bottom">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">{{ __("Vue d'ensemble") }}</a>
              </li>
              {{-- <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
              </li>
              <li class="nav-item">
                <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">More</a>
              </li> --}}
            </ul>
            <div>
              <div class="btn-wrapper">
{{--                 <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
 --}}                <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Imprimer</a>
                <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Exporter</a>
              </div>
            </div>
          </div>
          <div class="tab-content tab-content-basic">
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
              <div class="row">
                <div class="col-sm-12">
                  <div class="statistics-details d-flex align-items-center justify-content-between">

                    <div>
                      <p class="statistics-title">Toutes les fiches APMR</p>
                      <h3 class="rate-percentage">{{ $fichesCount ?? '1882' }}</h3>
                      <p class="d-none text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+4.1%</span></p>
                    </div>

                    <div>
                      <p class="statistics-title">Nouvelles fiches</p>
                      <h3 class="rate-percentage">{{ $nouvelles_fiches }}</h3>
                      <p class="d-none text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.5%</span></p>
                    </div>
                    {{-- return $vars =  compact('fichesCount','beneficiairesCount','compagniesCount','agentsCount' , 'nouvelles_fiches'); --}}
                   
                    <div>
                      <p class="statistics-title">Tous les beneficiaires</p>
                      <h3 class="rate-percentage">{{ $beneficiairesCount ?? '37,682' }}</h3>
                      <p class="d-none text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+1.2%</span></p>
                    </div>
                    <div class="d-none d-md-block">
                      <p class="statistics-title">Toutes les compagnies</p>
                      <h3 class="rate-percentage">{{ $compagniesCount }}</h3>
                      <p class="d-none text-success d-flex"><i class="mdi mdi-menu-down"></i><span>-2.8%</span></p>
                    </div>
                    <div class="d-none d-md-block">
                      <p class="statistics-title">Chefs d'escales</p>
                      <h3 class="rate-percentage">{{ $agentsCount }}</h3>
                      <p class="d-none text-danger d-flex"><i class="mdi mdi-menu-up"></i><span>+0.8%</span></p>
                    </div>
                    <div class="d-none d-md-block">
                      <p class="statistics-title">Servants CAS</p>
                      <h3 class="rate-percentage">{{ $assistanceAgentsCount }}</h3>
                      <p class="d-none text-success d-flex"><i class="mdi mdi-menu-down"></i><span>-3.8%</span></p>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="d-none row">
                <div class="col-lg-8 d-flex flex-column">
                  <div class="row flex-grow">

                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card d-none">
                      <div class="card card-rounded">
                        <div class="card-body">
                          <div class="d-sm-flex justify-content-between align-items-start">
                            <div>
                              <h4 class="card-title card-title-dash">Traitements en cours</h4>
                             <p class="card-subtitle card-subtitle-dash">Vous avez 50 traitements en cours</p>
                            </div>
                            <div>
                              <a href="{{ url('extract/create') }}" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button"><i class="mdi mdi-account-plus"></i>Nouvel import</a>
                            </div>
                          </div>
                          <div class="table-responsive  mt-1">
                            <table class="table select-table">
                              <thead>
                                <tr>
                                  <th>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </th>
                                  <th>Utilisateur</th>
                                  <th>Prestataire</th>
                                  <th>Progression</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex ">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Olivier KANA</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>Ad Lucem</h6>
                                    <p>Hopital</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-warning">79%</p>
                                        <p>137/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-warning">In progress</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>GENYCO Yassa</h6>
                                    <p>Hopital</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-warning">65%</p>
                                        <p>95/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-warning">In progress</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>PHARMACIE CITE DE PALMIERS</h6>
                                    <p>PHARMACIE</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-warning">58%</p>
                                        <p>85/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 58%" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-warning">In progress</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>LOUIS PASTEUR LABO</h6>
                                    <p>LABORATOIRE</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-danger">15%</p>
                                        <p>15/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-danger">Pending</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>LABORATOIRE DU RAIL</h6>
                                    <p>LABORATOIRE</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-success">100%</p>
                                        <p>162/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-success">Completed</div></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    {{--  --}<div class="d-none col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                      <div class="card card-rounded">
                        <div class="card-body">
                          <div class="d-sm-flex justify-content-between align-items-start">
                            <div>
                             <h4 class="card-title card-title-dash">Evolution temporelle de traitement des dossiers</h4>
                             <h5 class="card-subtitle card-subtitle-dash">Allez dans les parametres pour modifier l'intervalle</h5>
                            </div>
                            <div id="performance-line-legend"></div>
                          </div>
                          <div class="chartjs-wrapper mt-5">
                            <canvas id="performaneLine"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>{{--  --}}
                  </div>
                </div>
               

                <div class="col-4 grid-margin stretch-card d-none">
                  <div class="card card-rounded">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                              <h4 class="card-title card-title-dash">Top Performer</h4>
                            </div>
                          </div>
                          <div class="mt-3">
                            <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                              <div class="d-flex">
                                <img class="img-sm rounded-10" src="{{ asset('images/default.png') }}" alt="profile">
                                <div class="wrapper ms-3">
                                  <p class="ms-1 mb-1 fw-bold">Laura NGO BIBOUM</p>
                                  <small class="text-muted mb-0">16254</small>
                                </div>
                              </div>
                              <div class="text-muted text-small">
                                1h ago
                              </div>
                            </div>
                            <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                              <div class="d-flex">
                                <img class="img-sm rounded-10" src="{{ asset('images/default.png') }}" alt="profile">
                                <div class="wrapper ms-3">
                                  <p class="ms-1 mb-1 fw-bold">Olivier KANA</p>
                                  <small class="text-muted mb-0">162134</small>
                                </div>
                              </div>
                              <div class="text-muted text-small">
                                1h ago
                              </div>
                            </div>
                            <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                              <div class="d-flex">
                                <img class="img-sm rounded-10" src="{{ asset('images/default.png') }}" alt="profile">
                                <div class="wrapper ms-3">
                                  <p class="ms-1 mb-1 fw-bold">Patrick WAFO</p>
                                  <small class="text-muted mb-0">15254</small>
                                </div>
                              </div>
                              <div class="text-muted text-small">
                                1h ago
                              </div>
                            </div>
                            <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                              <div class="d-flex">
                                <img class="img-sm rounded-10" src="{{ asset('images/default.png') }}" alt="profile">
                                <div class="wrapper ms-3">
                                  <p class="ms-1 mb-1 fw-bold">NATACHE SEKE</p>
                                  <small class="text-muted mb-0">12254</small>
                                </div>
                              </div>
                              <div class="text-muted text-small">
                                1h ago
                              </div>
                            </div>
                            <div class="wrapper d-flex align-items-center justify-content-between pt-2">
                              <div class="d-flex">
                                <img class="img-sm rounded-10" src="{{ asset('images/default.png') }}" alt="profile">
                                <div class="wrapper ms-3">
                                  <p class="ms-1 mb-1 fw-bold">RENE KEMOGNE</p>
                                  <small class="text-muted mb-0">11168</small>
                                </div>
                              </div>
                              <div class="text-muted text-small">
                                1h ago
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="d-none row">
                <div class="col-lg-8 d-flex flex-column">
                  <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                      <div class="card card-rounded">
                        <div class="card-body">
                          <div class="d-sm-flex justify-content-between align-items-start">
                            <div>
                              <h4 class="card-title card-title-dash">Aperçu bordereaux</h4>
                             <p class="d-none card-subtitle card-subtitle-dash">Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                            </div>
                            <div>
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle toggle-dark btn-lg mb-0 me-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Ce mois </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                  <h6 class="dropdown-header">Les 03 derniers mois</h6>
                                  <a class="dropdown-item" href="#">Les 06 derniers mois</a>
                                  <a class="dropdown-item" href="#">Les 12 derniers mois</a>
                                  <a class="dropdown-item" href="#">Intervalle personnalisé</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Ré-initialiser</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-sm-flex align-items-center mt-1 justify-content-between">
                            <div class="d-sm-flex align-items-center mt-4 justify-content-between"><h2 class="me-2 fw-bold">123.253.231,00</h2><h4 class="me-2">FCFA</h4><h4 class="text-success">(+1.37%)</h4></div>
                            <div class="me-3"><div id="marketing-overview-legend"></div></div>
                          </div>
                          <div class="chartjs-bar-wrapper mt-3">
                            <canvas id="marketingOverview"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                
                  <div class="row flex-grow">


                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                      <div class="card card-rounded">
                        <div class="card-body">
                          <div class="d-sm-flex justify-content-between align-items-start">
                            <div>
                             <h4 class="card-title card-title-dash">Evolution temporelle de traitement des dossiers</h4>
                             <h5 class="card-subtitle card-subtitle-dash">Allez dans les parametres pour modifier l'intervalle</h5>
                            </div>
                            <div id="performance-line-legend"></div>
                          </div>
                          <div class="chartjs-wrapper mt-5">
                            <canvas id="performaneLine"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>

                   {{--  --} 
                   <div class="col-12 grid-margin stretch-card">
                      <div class="card card-rounded">
                        <div class="card-body">
                          <div class="d-sm-flex justify-content-between align-items-start">
                            <div>
                              <h4 class="card-title card-title-dash">Traitements en cours</h4>
                             <p class="card-subtitle card-subtitle-dash">Vous avez 50 traitements en cours</p>
                            </div>
                            <div>
                              <a href="{{ url('extract/create') }}" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button"><i class="mdi mdi-account-plus"></i>Nouvel import</a>
                            </div>
                          </div>
                          <div class="table-responsive  mt-1">
                            <table class="table select-table">
                              <thead>
                                <tr>
                                  <th>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </th>
                                  <th>Utilisateur</th>
                                  <th>Prestataire</th>
                                  <th>Progression</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex ">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Olivier KANA</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>Ad Lucem</h6>
                                    <p>Hopital</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-warning">79%</p>
                                        <p>137/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-warning">In progress</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>GENYCO Yassa</h6>
                                    <p>Hopital</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-warning">65%</p>
                                        <p>95/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-warning">In progress</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>PHARMACIE CITE DE PALMIERS</h6>
                                    <p>PHARMACIE</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-warning">58%</p>
                                        <p>85/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 58%" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-warning">In progress</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>LOUIS PASTEUR LABO</h6>
                                    <p>LABORATOIRE</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-danger">15%</p>
                                        <p>15/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-danger">Pending</div></td>
                                </tr>
                                <tr>
                                  <td>
                                    <div class="form-check form-check-flat mt-0">
                                      <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" aria-checked="false"><i class="input-helper"></i></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="d-flex">
                                      <img src="{{ asset('images/default.png') }}" alt="">
                                      <div>
                                        <h6>Laura NGO BIBOUM</h6>
                                        <p>comptabilité</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <h6>LABORATOIRE DU RAIL</h6>
                                    <p>LABORATOIRE</p>
                                  </td>
                                  <td>
                                    <div>
                                      <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                        <p class="text-success">100%</p>
                                        <p>162/162</p>
                                      </div>
                                      <div class="progress progress-md">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                    </div>
                                  </td>
                                  <td><div class="badge badge-opacity-success">Completed</div></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>{{--  --}}




                   
                  </div>
                  <div class="row flex-grow">
                   
                  
                  </div>
                </div>

                <div class="col-lg-4 grid-margin d-none {{-- stretch-card --}}">
                  <div class="card card-rounded">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title card-title-dash">Activités</h4>
                        <p class="mb-0">20 terminées, 5 restantes</p>
                      </div>
                      <ul class="bullet-line-list">
                        <li>
                          <div class="d-flex justify-content-between">
                            <div><span class="text-light-green">Audrey</span> vous a attribué une tache</div>
                            <p>Il y'a quelques secondes</p>
                          </div>
                        </li>
                        <li>
                          <div class="d-flex justify-content-between">
                            <div><span class="text-light-green">Oliver</span> vous a attribué une tache</div>
                            <p>1h</p>
                          </div>
                        </li>
                        <li>
                          <div class="d-flex justify-content-between">
                            <div><span class="text-light-green">William</span> vous a attribué une tache</div>
                            <p>1h</p>
                          </div>
                        </li>
                        <li>
                          <div class="d-flex justify-content-between">
                            <div><span class="text-light-green">Lucas</span> vous a attribué une tache</div>
                            <p>1h</p>
                          </div>
                        </li>
                       
                      </ul>
                      <div class="list align-items-center pt-3">
                        <div class="wrapper w-100">
                          <p class="mb-0">
                            <a href="#" class="fw-bold text-primary">Tout afficher<i class="mdi mdi-arrow-right ms-2"></i></a>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="row">
             
              </div>
            </div>
          </div>
        </div>
      </div>
     
 



@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  {{--<script src="{{asset('zen')}}/vendors/chart.js/Chart.min.js"></script>--}}
  <script src="{{asset('zen')}}/vendors/progressbar.js/progressbar.min.js"></script>

  <script src="{{asset('zen2')}}/js/dashboard.min.js"></script>
  {{--<script src="{{asset('zen2')}}/js/Chart.roundedBarCharts.js"></script>--}}
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('zen2')}}/js/hoverable-collapse.min.js"></script>
  <script src="{{asset('zen2')}}/js/settings.min.js"></script>
  <script src="{{asset('zen2')}}/js/todolist.min.js"></script>
@endsection