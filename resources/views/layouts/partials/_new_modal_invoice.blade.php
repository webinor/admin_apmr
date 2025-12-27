<!-- Modal Génération de Facture -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">

      <!-- Header -->
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="invoiceModalLabel">
          <i class="bi bi-receipt-cutoff me-2"></i> Générer une Facture
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <!-- Wizard navigation -->
        <ul class="nav nav-pills justify-content-center mb-4" id="invoiceWizard">
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#step1">1. Sélection</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#step2">2. Prévisualisation</a></li>
          {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#step3">3. Options</a></li> --}}
          <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#step3">3. Confirmation</a></li>
        </ul>

        <div class="tab-content">
          <!-- STEP 1 : Sélection -->
          <div class="tab-pane fade show active" id="step1">
            <form id="invoiceFormStep1">
          <input id="filter_url" type="hidden" value="{{ ('/api/preview') }}">

              {{-- <div class="mb-3">
                <label for="compagnie" class="form-label">Compagnie</label>
                <select class="form-select" id="compagnie" required>
                  <option value="">Sélectionner une compagnie</option>
                  <option value="AF">Air France</option>
                  <option value="TK">Turkish Airlines</option>
                  <option value="KQ">Kenya Airways</option>
                </select>
              </div> --}}

               <!-- Compagnie -->
              <div class="mb-3 border-bottom">
                <label for="company" class="form-label">Compagnie</label>
                <select id="company" name="company" class="form-control filter">
                  <option value="" selected>-- Sélectionner une compagnie --</option>
                  @foreach($companies as $compagnie)
                    <option value="{{ $compagnie->code }}">{{ $compagnie->name }}</option>
                  @endforeach
                </select>
              </div>

              @php
    $today = date('Y-m-d'); // date du jour
@endphp

<div class="mb-3 row">
    <div class="col">
        <label for="date_debut" class="form-label">Date début</label>
        <input type="date" 
               id="date_debut" 
               name="date_debut" 
               class="form-control filter" 
               min="2025-11-01"
               max="{{ $today }}">
    </div>
    <div class="col">
        <label for="date_fin" class="form-label">Date fin</label>
        <input type="date" 
               id="date_fin" 
               name="date_fin" 
               class="form-control filter" 
               min="2025-11-01"
               max="{{ $today }}">
    </div>
</div>

              {{-- <div class="mb-3 row">
                <div class="col">
                  <label for="date_debut" class="form-label">Date début</label>
                  <input type="date" name="date_debut" class="form-control filter" id="date_debut" required>
                </div>
                <div class="col">
                  <label for="date_fin" class="form-label">Date fin</label>
                  <input type="date" name="date_fin" class="form-control filter" id="date_fin" required>
                </div>
              </div> --}}
              {{-- <button type="button" class="btn btn-primary float-end" data-bs-target="#step2" data-bs-toggle="pill">
                Suivant <i class="bi bi-arrow-right-circle ms-1"></i>
              </button> --}}
              <button type="button" class="btn btn-primary float-end" data-go="step2">
    Suivant <i class="bi bi-arrow-right-circle ms-1"></i>
</button>
            </form>
          </div>

          <!-- STEP 2 : Prévisualisation -->
          <div class="tab-pane fade" id="step2">
            <h6 class="fw-bold mb-3">Résumé de la période sélectionnée</h6>
            <div class="row text-center">
              <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                  <h4 class="text-primary" id="count-total">0</h4>
                  <p class="mb-0">Fiches APMR</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                  <h4 class="text-success" id="count-signed">0</h4>
                  <p class="mb-0">Signées</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                  <h4 class="text-danger" id="count-unsigned">0</h4>
                  <p class="mb-0">Non Signées</p>
                </div>
              </div>
              {{-- <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                  <h4 class="text-warning">11</h4>
                  <p class="mb-0">Modifiées</p>
                </div>
              </div> --}}
            </div>

            <div class="mt-4  mb-3">
              <table class="table table-sm table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>Type de service</th>
                    <th>Quantité</th>
                    <th>Tarif unitaire ( FCFA )</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody id="items">
                  {{-- <tr>
                    <td>Chaise roulante manuelle</td>
                    <td>80</td>
                    <td>5 000 FCFA</td>
                    <td>400 000 FCFA</td>
                  </tr>
                  <tr>
                    <td>Chaise électrique</td>
                    <td>22</td>
                    <td>8 000 FCFA</td>
                    <td>176 000 FCFA</td>
                  </tr> --}}
                </tbody>
                <tfoot id="items-footer">
                  {{-- <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total estimé</td>
                    <td>576 000 FCFA</td>
                  </tr> --}}
                </tfoot>
              </table>
            </div>

            {{-- <button type="button" class="btn btn-secondary" data-bs-target="#step1" data-bs-toggle="pill">
              <i class="bi bi-arrow-left-circle me-1"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary float-end" data-bs-target="#step4" data-bs-toggle="pill">
              Suivant <i class="bi bi-arrow-right-circle ms-1"></i>
            </button> --}}

            <button type="button" class="btn btn-secondary" data-back="step1">
    <i class="bi bi-arrow-left-circle me-1"></i> Précédent
</button>

<button type="button" class="btn btn-primary float-end" data-go="step3">
    Suivant <i class="bi bi-arrow-right-circle ms-1"></i>
</button>
          </div>

          <!-- STEP 3 : Options -->
          {{-- <div class="tab-pane fade" id="step3">
            <h6 class="fw-bold mb-3">Options de facturation</h6>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="excludeRefused">
              <label class="form-check-label" for="excludeRefused">
                Exclure les fiches refusées
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="groupByVol">
              <label class="form-check-label" for="groupByVol">
                Regrouper par vol
              </label>
            </div>
            <div class="mt-3">
              <label for="internalRef" class="form-label">Référence interne (facultatif)</label>
              <input type="text" class="form-control" id="internalRef" placeholder="Ex : DLA001-2025-08">
            </div>

            <button type="button" class="btn btn-secondary" data-bs-target="#step2" data-bs-toggle="pill">
              <i class="bi bi-arrow-left-circle me-1"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary float-end" data-bs-target="#step4" data-bs-toggle="pill">
              Suivant <i class="bi bi-arrow-right-circle ms-1"></i>
            </button>
          </div> --}}

          <!-- STEP 3 : Confirmation -->
          <div class="tab-pane fade" id="step3">
            <h6 class="fw-bold">Confirmation</h6>
            <p>Vérifiez vos informations avant de générer la facture :</p>
            <ul class="list-group mb-3">
              <li class="list-group-item"><strong>Compagnie :</strong> <span id="compagny_name"></span> </li>
              <li class="list-group-item"><strong>Période :</strong>  <span id="period"></span></li>
              <li class="list-group-item"><strong>Total HT estimé :</strong> <span id="total"></span></li>
            </ul>
            {{-- <button type="button" class="btn btn-secondary" data-bs-target="#step2" data-bs-toggle="pill">
              <i class="bi bi-arrow-left-circle me-1"></i> Précédent
            </button> --}}

            <button type="button" class="btn btn-secondary" data-back="step2">
    <i class="bi bi-arrow-left-circle me-1"></i> Précédent
</button>


             <button type="button" data-action="final" class="generateBtn btn btn-success float-end ">
              <i class="bi bi-file-earmark-pdf me-1"></i> Générer Facture
            </button>

            <button type="button" data-action="preview" class="generateBtn btn btn-primary float-end  me-2">
              <i class="bi bi-file-earmark-pdf me-1"></i> Apercu de la Facture
            </button>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
