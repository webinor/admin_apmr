@extends('layouts.app')


@section('custom_css')
    <style>
      /* Supprimer visuellement la partie gauche des lignes de total
tfoot .total-row td:first-child {
    border: none !important;
}

/* Supprimer aussi les séparations internes *
tfoot .total-row td:not(:last-child) {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
} */
    </style>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Vue d'ensemble des factures</a>
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
            {{--@if (Auth::admin()->can('create', App\Models\Sales\Customer::class))--}}
    <!-- The current admin can update the post... -->
  

  <a href="#" class="btn btn-success text-white me-0" data-bs-toggle="modal" data-bs-target="#invoiceModal">
    <i class="bi bi-file-earmark-plus"></i> Nouvelle facture
  </a>

   {{-- <a href="#" class="btn btn-primary text-white me-0" data-bs-toggle="modal" data-bs-target="#newInvoiceModal">
    <i class="bi bi-file-earmark-plus"></i> Nouvelle facture
  </a> --}}

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
                  <p id="admin" class="text-danger d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
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
                  <h4 class="card-title">Liste des factures</h4>
                  {{--<p class="card-description">
                    Add class <code>.table-striped</code>
                  </p>--}}
                  <div class="table-responsive">
                    <table class="instances_lines table table-striped">
                      <thead>
                        <tr>
                         {{-- <th>
                            Logo
                          </th>--}}
                          <th>
                            Compagnie
                          </th>
                          
                          <th>
                            generée par
                          </th>
                          <th>
                            generée le
                          </th>

                          <th>
                            Periode
                          </th>
                         <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($invoices as $invoice)

                      
                        <tr>
                          
                         {{-- <td>
                            {{$invoice->image_url}}
                          </td>--}}
                          <td>
                            {{Str::upper($invoice->company->name)}}
                          </td>

                          <td>

                            @if ($invoice->invoicer && $invoice->invoicer->employee)
                           
                            {{$invoice->invoicer->employee->full_name()}}
                                
                            @endif

                            
                          </td>
                          <td>
                            {{$invoice->created_at}}
                          </td>

                           <td>
                            {{ \Carbon\Carbon::parse($invoice->start_date)->format('d/m/Y') }} 
-> 
{{ \Carbon\Carbon::parse($invoice->end_date)->format('d/m/Y') }}
                          </td>
                          
                          <td>
                            <form>

                              @can('update', $invoice)
                                  
                              <a id="print_{{$invoice->code}}" class="me-3 print" href="{{url('invoice/'.$invoice->code.'/edit')}}" ><i class="menu-icon mdi mdi-eye"></i></a>
                              
                                {{-- Régénérer la facture --}}
    {{-- <button 
        type="button"
        class="btn btn-sm btn-outline-warning regenerate-invoice"
        data-url="{{ url('api/invoice/'.$invoice->code.'/regenerate') }}"
        title="Régénérer la facture"
    >
        <i class="mdi mdi-refresh"></i>
    </button> --}}

    <button 
    type="button"
    class="btn btn-sm btn-outline-warning regenerate-invoice"
    data-url="{{ url('api/invoice/'.$invoice->code.'/regenerate') }}"
    data-code="{{ $invoice->code }}"
    data-company="{{ $invoice->company->name }}"
    data-start="{{ $invoice->start_date }}"
    data-end="{{ $invoice->end_date }}"
    data-invoice-number="{{ $invoice->invoice_number }}"
    data-total="{{ number_format($invoice->get_amount(), 0, ',', ' ') }}"
    title="Vérifier ou Régénérer la facture"
>
    <i class="mdi mdi-refresh"></i>
</button>


                              @endcan

                              {{-- <a id="edit_{{$invoice->code}}" class="me-3 edit" href="{{url('invoice/'.$invoice->code.'/edit')}}"><i class="menu-icon mdi mdi-eye"></i></a> --}}

                              @can('delete', $invoice)

                           
                              
                              
                               <a   data-bs-toggle="modal" data-bs-target="#delete-modal" data-model-to-delete="{{ $invoice->company->name}} Du  {{ \Carbon\Carbon::parse($invoice->start_date)->format('d/m/Y') }} 
-Au
{{ \Carbon\Carbon::parse($invoice->end_date)->format('d/m/Y') }}" data-delete-link="{{ ('/api/invoice/'.($invoice->code)) }}" class="delete" href="#"><i class="menu-icon mdi mdi-close-circle"></i></a>
                               
                               <div id="loader" class="invoice_{{$invoice->code }}  d-none d-flex justify-content-center mt-3">
                                 
                                 <div class="inner-loading dot-flashing"></div>
                                 
                                </div>
                              @endcan
                             
                            </form> 
                          </td>

                        </tr>  
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


{{-- @section('custom_modal')

@include('layouts.partials._modal_invoice')

        
@endsection --}}

@section('custom_modal')

{{-- @include('layouts.partials._modal_invoice') --}}

@include('layouts.partials._new_modal_invoice')

@include('layouts.partials._modal_regenerate_invoice')


{{-- @include('layouts.partials._modal_filter')  --}}

        
@endsection



@section('custom_js')


<script>
$(document).ready(function () {

    /**
     * Aller à une étape donnée
     * @param stepId ex: "#step2"
     */
    function goToStep(stepId) {
        // Désactiver toutes les tabs
        $('.tab-pane').removeClass('show active');

        // Activer la bonne tab
        $(stepId).addClass('show active');

        // Mettre à jour la navigation (nav-pills)
        $('#invoiceWizard .nav-link').removeClass('active');
        $('#invoiceWizard .nav-link[href="' + stepId + '"]').addClass('active');
    }

    /* ==========================
       BOUTONS SUIVANT
    ========================== */

    // Step 1 → Step 2
    $(document).on('click', '[data-go="step2"]', function () {

        // Exemple de validation simple
        if (!$('#company').val() || !$('#date_debut').val() || !$('#date_fin').val()) {
            alert('Veuillez remplir tous les champs ( compagnie, debut et fin )');
            return;
        }

        // Tu peux appeler ici ton AJAX preview si besoin
        // loadPreview();

        goToStep('#step2');
    });

    // Step 2 → Step 3
    $(document).on('click', '[data-go="step3"]', function () {
        goToStep('#step3');
    });

    /* ==========================
       BOUTONS PRÉCÉDENT
    ========================== */

    // Step 2 → Step 1
    $(document).on('click', '[data-back="step1"]', function () {
        goToStep('#step1');
    });

    // Step 3 → Step 2
    $(document).on('click', '[data-back="step2"]', function () {
        goToStep('#step2');
    });

});
</script>


<script>
  $(function () {
     

$(document).on('click', '.regenerate-invoice', function() {
    const button = $(this);

    // Récupérer les infos de la facture depuis les data-attributes
    const company   = button.data('company');
    const start     = button.data('start');
    const end       = button.data('end');
    const total     = button.data('total');
    const invoiceCode = button.data('code');
    const apiUrl    = button.data('url');
    const invoiceNumber    = button.data('invoice-number');

    

    // Remplir les champs du modal
    $('.inv-number').text(invoiceNumber);
    $('.regenerateDetails #compagny').text(company);
    $('.regenerateDetails #invoice_period').text(start + ' → ' + end);
    $('.regenerateDetails #total_invoice').text(total + ' FCFA');

    // Reset checkbox
    $('#invoiceOptionsForm #new_invoice_number').val("");

    // Stocker l'URL et code dans les boutons
    $('#final-invoice-button').data('url', apiUrl).data('code', invoiceCode);
    $('#invoice-preview-button').data('url', apiUrl).data('code', invoiceCode);

    // console.log($('#invoice-preview-button'));
    
    // Ouvrir le modal
    const modal = new bootstrap.Modal(document.getElementById('regenerateInvoiceModal'));
    modal.show();
});

$('#invoice-preview-button, #final-invoice-button').on('click', function(e){

     e.preventDefault();
    
    const form = $('#invoiceOptionsForm')[0];

    if ($("#new_invoice_number").val() == "") {
        // $($("new_invoice_number")).addClass('was-validated');
        alert('Indiquez si vous souaitez générer un nouveau numéro pour cette facture')
        return; // stop
    }

    handleInvoiceAction($(this));
});


function handleInvoiceAction(button) {

    // const url = $(this).data('url'),
    // action = 'preview';
      // const code = button.data('code');
    const url  = button.data('url');
    const action = button.data('action') ;
    const newNumber = $('#new_invoice_number').val();


    console.log(url);


    if (action =="final" && !confirm('Voulez-vous vraiment régénérer cette facture ?')) {
        return;
    }

     const payload = {
        _token: '{{ csrf_token() }}',
        new_number: newNumber,
        action: action
    };

    // button.prop('disabled', true);
    $(".reGenerateBtn").addClass("d-none");
    $("#fullscreen-loader-neon").fadeIn();

    $.ajax({
        url: url,
        type: 'POST',
        headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${window.localStorage.getItem('token')}`,
      },
        data: payload,
        beforeSend: function () {
            // optionnel : loader
        },
        success: function (response) {
            
          if (!response.success) {
      // button.prop('disabled', false);
      $(".reGenerateBtn").removeClass("d-none");
      $("#fullscreen-loader-neon").fadeOut();
      alert("Erreur lors de la génération");
      return;
    }
          // ✅ Ouvrir le PDF
    window.open(response.url, "_blank");

    // ✅ Mise à jour UI
    if (action === "final") {
      // addInvoiceRow(response.invoice); // ou reload()
      location.reload();
    }
    else{
      // button.prop('disabled', false);
      $(".reGenerateBtn").removeClass("d-none");
      $("#fullscreen-loader-neon").fadeOut();
    }
        },
        error: function (xhr) {
            alert('Erreur lors de la régénération de la facture');
        }
    });
}


  
//     document.querySelectorAll('.generateBtn').forEach(btn => {
//   btn.addEventListener('click', function () {

//     const action = this.dataset.action; // preview | final

//     const company     = document.getElementById('company').value;
//     const date_debut  = document.getElementById('date_debut').value;
//     const date_fin    = document.getElementById('date_fin').value;

//     if (!company || !date_debut || !date_fin) {
//       alert("Veuillez sélectionner la compagnie ainsi que les dates de début et de fin !");
//       return;
//     }

//     // Construction de l’URL avec action
//     const url = `{{ route('invoices.generate') }}`
//       + `?company=${encodeURIComponent(company)}`
//       + `&date_debut=${encodeURIComponent(date_debut)}`
//       + `&date_fin=${encodeURIComponent(date_fin)}`
//       + `&action=${encodeURIComponent(action)}`;

//     // Ouvre le PDF (aperçu ou définitif)
//     window.open(url, '_blank');
//   });
// });

document.querySelectorAll('.generateBtn').forEach(btn => {
  btn.addEventListener('click', async function () {

    const action     = this.dataset.action;
    const company    = document.getElementById('company').value;
    const date_debut = document.getElementById('date_debut').value;
    const date_fin   = document.getElementById('date_fin').value;

    if (!company || !date_debut || !date_fin) {
      alert("Veuillez remplir tous les champs !");
      return;
    } 

    $(".invoice-button").addClass("d-none");
    $("#fullscreen-loader-neon").fadeIn();

    const response = await fetch("{{ route('invoices.generate') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        company,
        date_debut,
        date_fin,
        action
      })
    });

    const data = await response.json();

    if (!data.success) {
      $(".invoice-button").removeClass("d-none");
      $("#fullscreen-loader-neon").fadeOut();
      alert("Erreur lors de la génération");
      return;
    }

    // ✅ Ouvrir le PDF
    window.open(data.url, "_blank");

    // ✅ Mise à jour UI
    if (action === "final") {
      // addInvoiceRow(data.invoice); // ou reload()
      location.reload();
    }
    else{
      $(".invoice-button").removeClass("d-none");
      $("#fullscreen-loader-neon").fadeOut();
    }
  });
});


function addInvoiceRow(invoice) {

  const tbody = document.querySelector('.instances_lines tbody');
  if (!tbody) return;

  const createdAt = new Date(invoice.created_at).toLocaleString();
  const startDate = new Date(invoice.start_date).toLocaleDateString();
  const endDate   = new Date(invoice.end_date).toLocaleDateString();

  const row = `
    <tr>
      <td>${invoice.company_name}</td>

      <td>
        ${invoice.generated_by ?? ''}
      </td>

      <td>
        ${createdAt}
      </td>

      <td>
        ${startDate} → ${endDate}
      </td>

      <td>
        <form>
          <a class="me-3 print" 
             href="/invoice/${invoice.code}/edit">
             <i class="menu-icon mdi mdi-eye"></i>
          </a>

          <a class="delete"
             href="#"
             data-bs-toggle="modal"
             data-bs-target="#delete-modal"
             data-model-to-delete="${invoice.company_name} du ${startDate} au ${endDate}"
             data-delete-link="/api/invoice/${invoice.code}">
             <i class="menu-icon mdi mdi-close-circle"></i>
          </a>

          <div class="invoice_${invoice.code} d-none d-flex justify-content-center mt-3">
            <div class="inner-loading dot-flashing"></div>
          </div>
        </form>
      </td>
    </tr>
  `;

  // 🔥 Ajout en haut du tableau
  tbody.insertAdjacentHTML('afterbegin', row);
}



  const form = document.getElementById('invoiceFormStep1');
  const filterUrl = document.getElementById('filter_url').value;



  // Fonction pour récupérer les filtres du formulaire
function getFilters() {
  const filters = {};
  form.querySelectorAll('.filter').forEach(input => {
    if (input.type === 'checkbox') {
      if (input.checked) filters[input.name] = 1;
    } else if (input.value) {
      filters[input.name] = input.value;
    }
  });
  return filters;
}

async function updateCount() {

  $(".invoice-button").addClass("d-none");
  $("#invoice-loader").removeClass("d-none");


  const filters = getFilters();

  try {
    const params = new URLSearchParams(filters);

    const response = await fetch(`${filterUrl}?${params.toString()}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${window.localStorage.getItem('token')}`,
      }
    });

    if (!response.ok) {
      throw new Error('Erreur lors du chargement des statistiques');
    }


      $(".invoice-button").removeClass("d-none");
  $("#invoice-loader").addClass("d-none");

  resetInvoicePreview();


    const data = await response.json();

  //  console.log(!data.count_signed);
    
        if (data.count_signed_uninvoiced >= 0) {
   

              $("#invoice-button").removeClass("d-none");
        $("#preview-button").removeClass("d-none");
    

    // ✅ Mise à jour des compteurs
    document.getElementById('count-total-signed').textContent =
      data.count_total_signed ?? 0;

    document.getElementById('count-signed-invoiced').textContent =
      data.count_signed_invoiced ?? 0;

    document.getElementById('count-signed-uninvoiced').textContent =
      data.count_signed_uninvoiced ?? 0;




    document.getElementById('compagny_name').textContent = data.company.name;

    document.getElementById('period').textContent = data.period;


    document.getElementById('total').textContent = data.totals.ht;



          // Table
    renderItems(data.items);
    renderFooter(data.totals);

        }

        else{



         $("#invoice-button").addClass("d-none");
        $("#preview-button").addClass("d-none");

      
        }

  } catch (error) {
    console.error(error);
  }
}

function renderItems(items) {
    const tbody = document.getElementById('items');
    tbody.innerHTML = '';

    items.forEach(item => {

      if (!item.is_mensual_fee) {

        tbody.innerHTML += `
            <tr>
                <td>${item.label}</td>
                <td class="text-center">${item.qty}</td>
                <td class="text-end">${formatMoney(item.pu)}</td>
                <td class="text-end fw-bold">${formatMoney(item.amount)}</td>
            </tr>
        `;
        
      } else {

        tbody.innerHTML += `
            <tr>
                <td colspan="3">${item.label}</td>
                <td class="text-end fw-bold">${formatMoney(item.amount)}</td>
            </tr>
        `;
        
      }
        
    });
}

function renderFooter(totals) {
    const tfoot = document.getElementById('items-footer');

    tfoot.innerHTML = `
        <tr class="total-row">
            <td colspan="3" class="text-end">Montant HT</td>
            <td class="text-end">${formatMoney(totals.ht)}</td>
        </tr>
        <tr class="total-row">
            <td colspan="3" class="text-end">TVA</td>
            <td class="text-end">${formatMoney(totals.tva)}</td>
        </tr>
        <tr class="fw-bold table-light total-row">
            <td colspan="3" class="text-end">Montant TTC</td>
            <td class="text-end">${formatMoney(totals.ttc)}</td>
        </tr>
    `;
}

function resetInvoicePreview() {
    // Réinitialiser les compteurs
    document.getElementById('count-total-signed').textContent = 0;
    document.getElementById('count-signed-invoiced').textContent = 0;
    document.getElementById('count-signed-uninvoiced').textContent = 0;

    // Réinitialiser le nom de la compagnie et la période
    document.getElementById('compagny_name').textContent = '-';
    document.getElementById('period').textContent = '-';

    // Réinitialiser le total
    document.getElementById('total').textContent = '0';

    // Réinitialiser le tableau des items
    const tbody = document.getElementById('items');
    if (tbody) tbody.innerHTML = '';

    // Réinitialiser le footer du tableau
    const tfoot = document.querySelector('#items').closest('table')?.querySelector('tfoot');
    if (tfoot) tfoot.innerHTML = '';
}


function formatMoney(amount) {
    return amount > 0 ? new Intl.NumberFormat('fr-FR').format(amount) + '' : '';
}


// On écoute tous les changements dans le formulaire
form.querySelectorAll('.filter').forEach(input => {
  input.addEventListener('change', updateCount);
});

    

    
  });
</script>



@endsection