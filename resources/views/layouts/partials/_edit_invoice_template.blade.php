@can('update', $invoice)
    
@if (!$already_store_on_remote)
    

<a data-id="{{ isset($id) ? $id : "" }}" data-url="{{ isset($url_update) ? $url_update : "" }}" 
data-invoice="{{ isset($invoice) ? $invoice->get_code() : ''}}"
data-reference="{{ isset($invoice) ? $invoice->get_reference() : ''}}"
data-undefined_reference="{{ __("REFERENCE INTROUVABLE") }}"

{{-- undefined_reference --}}
data-prestation_code="{{ isset($invoice) && $invoice->prestationable ? $invoice->prestationable->get_code() : ''}}"
data-prestation_type="{{ isset($invoice) && $invoice->prestationable ? $invoice->prestationable->get_prestationable_type() : ''}}"
data-prestation="{{ isset($invoice) && $invoice->prestationable ? $invoice->prestationable->get_fullname() : ""  }}" data-target="invoice" class="api-{{ $invoice->get_reference() }}me-3 edit blink invoice_updater "
                                href="#" data-bs-toggle="moda" data-bs-target="#model_updat"><i
                                  class="menu-icon mdi mdi-table-edit"></i>
                            </a>
                            
      @endif
@endcan  