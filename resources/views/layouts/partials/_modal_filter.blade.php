<!-- Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="modalFilterLabel">{{ 'Exporter les données' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0">
        <form id="form_filter" class="pt-0" novalidate method="GET" action="{{ url('slip/') }}">
          @csrf
          <input id="filter_url" type="hidden" value="{{ ('/api/count-filtered-results') }}">
          <input id="slip" name="slip" class="form-control" type="hidden" value="">

          <div class="row">
            <div class="col-md-12">

              <!-- Compagnie -->
              <div class="mb-3 border-bottom">
                <label for="compagnie" class="form-label">Compagnie</label>
                <select id="compagnie" name="compagnie" class="form-control filter">
                  <option value="">-- Toutes --</option>
                  @foreach($companies as $compagnie)
                    <option value="{{ $compagnie->code }}">{{ $compagnie->name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Agent -->
              <div class="mb-3 border-bottom">
                <label for="agent" class="form-label">Agent</label>
                <select id="agent" name="agent" class="form-control filter">
                  <option value="">-- Tous --</option>
                  @foreach($agents as $agent)
                    <option value="{{ $agent->code }}">{{ $agent->fullName() }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Période -->
              <div class="mb-3 border-bottom">
                <label for="date_debut" class="form-label">Période</label>
                <div class="d-flex gap-2">
                  <input type="date" id="date_debut" name="date_debut" class="form-control filter">
                  <input type="date" id="date_fin" name="date_fin" class="form-control filter">
                </div>
              </div>

              <!-- Type de chaise -->
               <div class="mb-3 border-bottom">
                <label for="wheel_chair" class="form-label">Type de chaise</label>
                <select id="wheel_chair" name="wheel_chair" class="form-control filter">
                  <option value="">-- Toutes --</option>
                  @foreach($wheel_chairs as $wheel_chair)
                  <option value="{{ $wheel_chair->code }}">{{ $wheel_chair->name }}</option>
                @endforeach
                </select>
              </div>

              <!-- Enregistré par -->
              <div class="mb-3 border-bottom">
                <label for="user" class="form-label">Enregistré par</label>
                <select id="user" name="user" class="form-control filter">
                  <option value="">-- Tous --</option>
                  @foreach($users as $user)
                    <option value="{{ $user->code }}">{{ $user->fullName() }}</option>
                  @endforeach
                </select>
              </div>

              

             

              <!-- Ville -->
              <div class="mb-3 border-bottom">
                <label for="city" class="form-label">Ville</label>
                <select id="city" name="city" class="form-control filter">
                  <option value="">-- Toutes --</option>
                  @foreach($cities as $city)
                    <option value="{{ $city->code }}">{{ $city->name }}</option>
                  @endforeach
                </select>
              </div>

              {{--  --}
              <!-- Min / Max prix -->
              <div class="col-12 border-bottom">
                <label style="font-size: .9rem;" for="min-price" class="form-label">
                  Total Min : <strong class="min-price-value">{{ Request::get('min-price') ?? 0 }}</strong> Fcfa
                </label>
                <input type="range" value="{{ Request::get('min-price') ?? 0 }}" id="min-price" class="form-control form-range prices min-price" min="0" max="200000" step="10000" name="min-price">

                <label style="font-size: .9rem;" for="max-price" class="form-label">
                  Total Max : <strong class="max-price-value">{{ Request::get('max-price') ?? '200000' }}</strong> Fcfa
                </label>
                <input type="range" id="max-price" class="form-control form-range prices max-price" min="0" max="200000" step="10000" value="{{ Request::get('max-price') ?? '200000' }}" name="max-price">
              </div>

              <!-- Urgence
              <div class="form-check border-bottom">
                <label class="form-check-label my-4" style="font-size: .9rem;">
                  <input id="urgence" name="urgence" type="checkbox" class="filter form-check-input">
                  PEC urgente uniquement
                </label>
              </div> -->

              <!-- Justificatifs -->
              <div class="form-check border-bottom">
                <label class="form-check-label my-4" style="font-size: .9rem;">
                  <input id="justificatifs" name="justificatifs" type="checkbox" class="filter form-check-input">
                  Deja facturees
                </label>
              </div>

              {{--  --}}

            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="menu_button">
          <button id="filter-button" type="button" class="text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
            <span id="filter-button-text">Exporter</span>
            <div id="filter-loader" class="d-none d-flex justify-content-start">
              <div class="inner-loading dot-flashing"></div>
            </div>
          </button>
        </div>
        <div class="menu_button">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div> 
      </div>
    </div>
  </div>
</div>
