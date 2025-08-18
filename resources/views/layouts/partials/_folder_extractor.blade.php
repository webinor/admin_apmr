<div class="tab-pane fade {{ $unsaved_unvalidated_folders > 0 ? "show active" : "" }}" id="overview" role="tabpanel" aria-labelledby="overview"> 
       
  @include('layouts.partials._folder_template' , [ 'folders' => $folders ,  'status' => '1'  /*, 'status' => 2*/ ])

</div>



                    {{--           Extract succeded            --}}

            <div class="tab-pane fade {{  ( $unsaved_unvalidated_folders == 0 && $itemless_folders > 0) ? "show active" : "" }}" id="demographics" role="tabpanel" aria-labelledby="demographics">


                      @include('layouts.partials._folder_template' , [ 'folders' => $folders , 'status' => '3' ])


                     
                  </div>
                  {{--           End Extract succeded        --}}


                          {{--           Extract Failed            --}}
           <div class="tab-pane fade {{  ( $unsaved_unvalidated_folders == 0 && $itemless_folders == 0 &&  $saved_validated_folders == $slip_folders   ) ? "show active" : "" }}" id="audiences" role="tabpanel" aria-labelledby="audiences">
       

              <div class="row">

              @include('layouts.partials._folder_template' , [ 'folders' => session('user')->isExtractor() ? $folders : $filtered_folders , 'status' => '2' ] )
                

              </div>

             
          </div>

            {{--           End Extract Failed        --}}