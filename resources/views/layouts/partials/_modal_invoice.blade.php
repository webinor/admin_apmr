
<!-- Modal -->
<div class="modal fade" id="newInvoiceModal" tabindex="-1" aria-labelledby="newInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 shadow">
        <div class="modal-header bg-primary text-white rounded-top-4">
          <h5 class="modal-title" id="newInvoiceModalLabel">
            <i class="bi bi-receipt-cutoff me-2"></i> Génération de facture
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
  
        <div class="modal-body">
            <form id="invoiceForm" method="POST" action="">
                @csrf
            <!-- Choisir compagnie -->
            <div class="mb-3">
              <label for="company" class="form-label fw-bold">Compagnie aérienne</label>
              <select class="form-select" id="company" name="company" required>
                @forelse ($companies as $company)
                    
                        @if ($loop->first)
                        <option value="" >Selectionnez la compagnie</option>
                        @endif
                           
                        <option value="{{$company->code}}" {{--  $ground_agent && $ground_agent->company->code == $company->code ? 'selected' : '' --}}>{{Str::upper(__($company->name))}}</option>
                        
                        @empty
                    
                        <option value="">Aucune compagnie disponible</option>
                    
                        @endforelse
              </select>
            </div>
  
            <!-- Mois de facturation -->
            <div class="mb-3">
              <label for="month" class="form-label fw-bold">Mois de facturation</label>
              <input type="month" class="form-control" id="month" name="month" required>
            </div>
          </form>
        </div>
  
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Annuler
          </button>
          <div>
            <button type="button" class="btn btn-success rounded-pill me-2" id="previewBtn">
              <i class="bi bi-eye"></i> Aperçu
            </button>
           {{--  <button type="button" form="invoiceForm" class="btn btn-success rounded-pill">
              <i class="bi bi-file-earmark-check"></i> Générer
            </button> --}}
          </div>
        </div>
      </div>
    </div>
  </div>