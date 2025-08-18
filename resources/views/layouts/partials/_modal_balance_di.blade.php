 <!-- Modal -->
 <div class="modal fade" id="balance-modal" tabindex="-1" aria-labelledby="modalBalanceLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable modal-lg">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="modalBalanceLabel">{{ $title }}</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
         <form id="form_modal" class="pt-3 d-none" novalidate method="post"
           action="{{ url('company/create') }}">
           @csrf
           <input id="modal_url" type="hidden" value="{{ $modal_url }}">
           {{-- <input id="resource_code" class="form-control" type="hidden" value="{{ $resource_code }}"
           >
           <input id="resource_type" class="form-control" type="hidden" value="{{ $resource_type }}"> --}}
           <input id="validator_id" class="form-control" type="hidden"
             value="{{ session('user')->id }}">
         </form>


         <div class="row ">
           <div class="col-lg-12 grid-margin stretch-card  justify-content-center">
             <div class="table-responsive">
               <table id="supplier"
                 class="text-center fw-bold instances_lines table table-hover table-bordered table-striped"
                 data-url="supplier" data-type="supplier">
                 <thead>
                   <tr class="">
                     <th>
                       Logo
                     </th>

                     <th>
                       Fournisseur
                     </th>

                     <th>
                       Numero de la DI
                     </th>
                     <th>
                       Factures <br> ( Numero --- Montant Total de la facture --- Solde )
                       {{-- <strong>( {{ currency()->getUserCurrency() }} )</strong> --}}
                     </th>

                     <th>
                       Montant de la DI
                     </th>

                     <th>
                       Solde de la DI
                     </th>

                     {{-- <th>Action</th> --}}

                   </tr>
                 </thead>
                 <tbody>
                   @foreach($import_declarations as $import_declaration)
                     @php
                       $total_di = 0;
                       // $total+=$supplier->getBalance();
                     @endphp
                     <tr>
                       <td>
                         {{-- {{ currency($supplier->getBalance()) }} --}} <div class="preview-thumbnail">
                           @php
                             if ($import_declaration->partnerable->logo) {
                             $logo = asset("storage/supplier_images/".$import_declaration->partnerable->logo->path);
                             } else {
                             $logo = asset("storage/supplier_images/default.png");
                             }
                           @endphp

                           <img data-src="{{ $logo }}" alt="image" class="lazy img-sm profile-pic">

                         </div>

                       </td>

                       <td>{{ $import_declaration->partnerable->social_reason }}</td>


                       <td>
                         {{ $import_declaration->identifier }}
                       </td>

                       <td>


                         <table>



                           @php
                             $amount_invoice = 0;//$related_resource->resourceable->getTotalAmount();
                             $balance_invoice = 0;//$related_resource->resourceable->getTotalAmount();
                             $amount_di = 0;//$related_resource->resourceable->getTotalAmount();
                             $balance_di = 0;//$related_resource->resourceable->getBalance();
                           @endphp
                           {{-- <thead>
                            <th>Num</th>
                            <th>Montant</th>
                            <th>Solde</th>
                          </thead>
                          <tbody> --}}
                           @foreach($import_declaration->related_resources as $related_resource)

                             @php

                               $amount_invoice = $related_resource->resourceable->getTotalAmount();
                               $balance_invoice = $related_resource->resourceable->getBalance();

                               $amount_di += $amount_invoice;
                               $balance_di += $balance_invoice;
                             @endphp

                             <tr
                               {{ $loop->last ? '' : "class=border-bottom" }}>
                               <td>{{ $related_resource->identifier }}</td>
                               <td>{{ currency($amount_invoice ,currency()->getUserCurrency() ) }}</td>
                               <td>
                                 {{ $balance_invoice > 0 ? currency($balance_invoice,currency()->getUserCurrency()) : 0 }}
                               </td>
                             </tr>
                           @endforeach
                           {{-- </tbody> --}}

                         </table>


                         {{--                              c
 --}}

                       </td>

                       <td>
                         {{ currency($import_declaration->amount,$import_declaration->currency->code,currency()->getUserCurrency()) }}
                       </td>


                       <td>
                         {{ $balance_di > 0 ? currency($balance_di,currency()->getUserCurrency(),null) : 0 }}
                       </td>

                       {{--  <td>
                      <form>

@can('view', $import_declaration)
                            
                        
                        <a    id="print_{{ $import_declaration->code }}"
                       class="import_declaration_{{ $import_declaration->code }} me-3 print"
                       href="{{ url('import_declaration/'.$import_declaration->code) }}" ><i
                         class="menu-icon mdi mdi-eye"></i></a>

                   @endcan

                   @can('update', $import_declaration)
                     <a id="edit_{{ $import_declaration->code }}"
                       class="import_declaration_{{ $import_declaration->code }} me-3 edit"
                       href="{{ url('import_declaration/'.$import_declaration->code.'/edit') }}"><i
                         class="menu-icon mdi mdi-table-edit"></i></a>
                   @endcan

                   </form>
                   </td> --}}

                   </tr>
                   @endforeach
                   {{-- <tr class="fw-bold">
                    <td>Total</td>
                    <td>*</td>
                    <td>{{ currency($total_di) }}</td>

                   </tr> --}}
                 </tbody>
               </table>
             </div>
           </div>
         </div>
       </div>
       <div class="modal-footer">
         <div class="menu_button">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
         </div> {{-- 

        <div class="menu_button">
          <button id="validate" data-resource="{{ $resource }}" data-partner="{{ $partner }}" type="button" class="
         text-white w-100 btn btn-block btn-primary font-weight-medium auth-form-btn">
         Oui
         </button>
       </div> --}}

       <div id="loader" class="d-none d-flex justify-content-start">

         <div class="inner-loading dot-flashing"></div>

       </div>
     </div>
   </div>
 </div>
 </div>