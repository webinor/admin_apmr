<div class="tab-pane fade  {{ $conforms_folders > 0 ? "show active" : "" }}" id="overview" role="tabpanel" aria-labelledby="overview"> 
       
  @include('layouts.partials._folder_template' , [ 'folders' => $folders ,  'status' => '1'  /*, 'status' => 2*/ ])

</div>


      {{--           Extract Failed            --}}

      <div class="tab-pane fade {{$conforms_folders==0 && $inconforms_folders > 0 ? "show active" : "" }}" id="demographics" role="tabpanel" aria-labelledby="demographics">

        <div class="row">

        @include('layouts.partials._folder_template' , [ 'folders' => session('user')->isExtractor() ? $folders : $filtered_folders , 'status' => '3' ] )
          

        </div>

       
    </div>

      {{--           End Extract Failed        --}}


                    {{--           Extract succeded            --}}

                    <div class="tab-pane fade {{$conforms_folders==0 && $inconforms_folders == 0 && $saved_validated_folders == $slip_folders ? "show active" : "" }}" id="audiences" role="tabpanel" aria-labelledby="audiences">

                      @include('layouts.partials._folder_template' , [ 'folders' => $folders , 'status' => '2' ])


                     
                  </div>
                  {{--           End Extract succeded        --}}


                    