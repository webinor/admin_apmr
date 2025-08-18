@can('update', $folder)
    
<a  data-id="{{ isset($id) ? $id : "" }}" data-url="{{ isset($url_update) ? $url_update : "" }}" 
data-folder="{{ isset($folder) ? $folder->code : ''}}"
data-reference="{{ isset($folder) ? $folder->get_identification() : ''}}"
data-undefined_reference="{{ "UNDEFINED FOLDER" }}"

data-prestation_code="{{ isset($invoice) && $invoice->prestationable ? $invoice->prestationable->get_code() : ''}}"
data-prestation_type="{{ isset($invoice) && $invoice->prestationable ? $invoice->prestationable->get_prestationable_type() : ''}}"
data-prestation="{{ isset($invoice) && $invoice->prestationable ? $invoice->prestationable->get_fullname() : ""  }}" data-target="folder" class="me-3 edit blink invoice_updater "
                                href="#" data-bs-toggle="moda" data-bs-target="#model_updat"><i
                                  class="menu-icon mdi mdi-table-edit"></i>
                            </a>

@endcan 