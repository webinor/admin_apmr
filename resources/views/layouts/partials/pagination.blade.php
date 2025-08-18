<nav aria-label="Page navigation example">
    <ul class="pagination pagination-sm justify-content-center">
      
@if ($paginator->hasPages())
 
      

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&lsaquo; {{ __("Page Précédente") }}</a>
            </li>
               
                
            {{-- <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li> --}}
            @else
            {{-- <a  href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a> --}}

            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&lsaquo; {{ __("Page Précédente") }}</a></li>
               {{--  <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li> --}}
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                  {{--   <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                --}}
                <a>...</a>  
                    @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                            {{-- @if ($loop->index == 20)
                            
                            @break
                            @endif --}}
                        @if ($page == $paginator->currentPage())
                            {{--<a class="active">{{ $page }}</a> <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li> --}}
                            

                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">{{ $page }}</a>
                              </li>

                            @else


                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>

                            
                            {{--<a  href="{{ $url }}">{{ $page }}</a> <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li> --}}
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())

            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">{{ __("Page Suivante") }} &rsaquo;</a></li>

             
                     {{--  <a  href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a><li class="page-item">
                   <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a> 
                </li>--}}
            @else
            <li class="page-item disabled">
                <a class="page-link" href="#">{{ __("Page Suivante") }} &rsaquo;</a>
              </li>

              

                {{--<a  rel="next">&rsaquo;</a>  <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>--}}
            @endif
        
  
@endif

</ul>
</nav>
