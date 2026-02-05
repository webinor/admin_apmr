<!-- Modal Génération de Facture Simplifié -->
<div class="modal fade" id="regenerateInvoiceModal" tabindex="-1" aria-labelledby="regenerateInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">

      <!-- Header -->
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="regenerateInvoiceModalLabel">
          <i class="bi bi-receipt-cutoff me-2"></i> Regénérer la Facture numéro <span class="inv-number"></span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">

        <!-- Informations de la facture -->
        <ul class="regenerateDetails list-group mb-3">
          <li class="list-group-item"><strong>Numéro de la facture :</strong> <span class="inv-number"></span></li>
          <li class="list-group-item"><strong>Compagnie :</strong> <span id="compagny"></span></li>
          <li class="list-group-item"><strong>Période allant :</strong> <span id="invoice_period"></span></li>
          <li class="list-group-item"><strong>Total :</strong> <span id="total_invoice"></span></li>
        </ul>

        <!-- Formulaire options -->
        <form id="invoiceOptionsForm">
          
            <div class="mb-3">
  <label for="new_invoice_number" class="form-label">Sélectionnez une des options ci-dessous ?</label>
  <select id="new_invoice_number" name="new_invoice_number" class="form-select">
    <option value="" selected>Voulez-vous générer un nouveau numéro de facture ?</option>
    <option value="0" >Non, Conserver le numéro actuel</option>
    <option value="1">Oui, Générer un nouveau numéro</option>
  </select>

   <div class="invalid-feedback">
    Veuillez sélectionner une option.
  </div>
</div>

          {{-- <div class="mb-3 row">
            <div class="col">
              <label for="date_debut" class="form-label">Date début</label>
              <input type="date" id="date_debut" name="date_debut" class="form-control">
            </div>
            <div class="col">
              <label for="date_fin" class="form-label">Date fin</label>
              <input type="date" id="date_fin" name="date_fin" class="form-control">
            </div>
          </div> --}}
        </form>

        <!-- Loader (caché par défaut) -->
        <div id="invoice-loader" class="d-none d-flex justify-content-center my-3">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
        </div>

      </div>

      <!-- Footer : boutons -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary reGenerateBtn" data-bs-dismiss="modal">
          Annuler
        </button>
        <button id="invoice-preview-button" type="button" class="btn btn-primary reGenerateBtn" data-action="preview">
          <i class="bi bi-file-earmark-pdf me-1"></i> Aperçu de la Facture
        </button>
        <button id="final-invoice-button" type="button" class="btn btn-success reGenerateBtn" data-action="final">
          <i class="bi bi-file-earmark-pdf me-1"></i> Régénérer la Facture
        </button>
      </div>

    </div>
  </div>
</div>
