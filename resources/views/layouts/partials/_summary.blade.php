<div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body row p-3">
        <h5 class="fw-bold text-center mb-1">Information sur le prestataire</h5>

        <div class=" row">
          <div class="col-4 " >Nom :</div> 
          <div class="fw-bold mb-1 col-6 name" >{{ $folder ? $folder->provider->get_name() : '' }}</div>
        </div>
        <div class=" row">
          <div class="col-4 " >Adresse :</div> 
          <div class="fw-bold mb-1 col-6 address" >{{ $folder ? $folder->provider->get_address() : '' }}</div>
        </div>
        <div class=" row">
          <div class="col-4 " >Categorie :</div> 
          <div class="fw-bold col-6 category" >{{ $folder ? $folder->provider->get_category() : '' }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body row">
        <h5 class="fw-bold text-center mb-3">Information financieres</h5>

        <div class=" row">
          <div class="col-4" >Bons de PC: </div> 
          <div class="fw-bold mb-2 col-6 invoices_count" >{{ $folder ? $folder->invoices->count() : '' }}</div>
        </div>
        <div class=" row">
          <div class="col-4" >Prestations :</div> 
          <div class="fw-bold mb-2 col-6" >
            <ul class="finance_services">
              @if ($folder)
                
              @foreach ($folder->get_services()  as $service)
              <li>{{ $service }}</li>
              @endforeach

              @endif
            </ul>
          </div>
        </div>
        <div class=" row">
          <div class="col-4" >Montant total :</div> 
          <div class="fw-bold col-6" ></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body row">
        <h5 class="fw-bold text-center mb-3">Information sur l'assuré</h5>

        <div class=" row">
          <div class="col-4" >Assuré :</div> 
          <div class="fw-bold mb-3 col-6" >
            <span class="insured"></span>

            <a class="me-3 edit blink "
                                  href="#"><i
                                    class="menu-icon mdi mdi-table-edit"></i>
                              </a>

          </div>
        </div>
        <div class=" row">
          <div class="col-4" >Béneficiaire :</div> 
          <div class="fw-bold mb-3 col-6" ><span class="beneficiary"></span>
          
            <a class="me-3 edit blink "
            href="#"><i
              class="menu-icon mdi mdi-table-edit"></i>
        </a>
          
          </div>
        </div>
        <div class=" row">
          <div class="col-4" >Dossier # :</div> 
          <div class="fw-bold mb-3 col-6" ><span class="folder_id">{{ $folder ? $folder->get_identification() :'' }}</span>
          
            <a class="me-3 edit blink "
            href="#"><i
              class="menu-icon mdi mdi-table-edit"></i>
        </a>
          
          </div>
        </div>
        <div class=" row">
          <div class="col-4" >References  :</div> 
          <div class="fw-bold col-6" >
            <ul class="reference_ids">
              @if ($folder)

              @foreach ($folder->invoices  as $invoice)
              <li>{{ $invoice->reference }} <a class="me-3 edit blink "
                href="#"><i
                  class="menu-icon mdi mdi-table-edit"></i>
            </a></li>
              @endforeach

              @endif
              
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>