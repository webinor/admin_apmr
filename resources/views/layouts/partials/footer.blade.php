<div  class="site-footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <div class="widget">
          <h3>Contact</h3>
          <address>Makepe Saint Tropez, Douala, Cameroun</address>
          <ul class="list-unstyled links">
           {{--  <li><a href="tel://11234567890">+237(691)-504-490</a></li>
            <li><a href="tel://11234567890">+1(123)-456-7890</a></li> --}}
            <li>
              <a href="mailto:hello@myindexpert.com">hello@myindexpert.com</a>
            </li>

            <li>
              <a href="{{ url('user/common/ad/create') }}" target="_blank">{{ __("Post ads now") }}</a>
            </li>
          </ul>
        </div>
        <!-- /.widget -->
      </div>
      <!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <div class="widget">
          <h3>Sources</h3>
          <ul class="list-unstyled float-start links">
            <li><a href="{{ url('about') }}">{{ __("About") }}</a></li>
            {{-- <li><a href="{{ url('services') }}">{{ __("Services") }}</a></li> --}}
            <li><a href="#">{{ __("Vision") }}</a></li>
          </ul>
          <ul class="list-unstyled float-start links">
            <li><a href="{{ url('user/common/ad/create') }}" target="_blank">{{ __("Partners") }}</a></li>
            <li><a href="{{ url('user/common/ad/create') }}" target="_blank">{{ __("Business") }}</a></li>
            <li><a href="#">{{ __("Blog") }}</a></li>
          </ul>
        </div>
        <!-- /.widget -->
      </div>
      <!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <div class="widget">
          <h3>Links</h3>
          <ul class="list-unstyled links">
            <li><a href="#">{{ __("Vision") }}</a></li>
            <li><a href="{{ url('about') }}">{{ __("About") }}</a></li>
            <li><a href="{{ url('contact') }}">{{ __("Contact Us") }}</a></li>
            <li><a href="{{ url('user/common/ad/create') }}" target="_blank">{{ __("Post ads now") }}</a></li>
          </ul>

          <ul class="list-unstyled social">
            <li>
              <a href="#"><span class="icon-instagram"></span></a>
            </li>
            <li>
              <a href="#"><span class="icon-twitter"></span></a>
            </li>
            <li>
              <a href="#"><span class="icon-facebook"></span></a>
            </li>
            <li>
              <a href="#"><span class="icon-linkedin"></span></a>
            </li>
            <li>
              <a href="#"><span class="icon-pinterest"></span></a>
            </li>
            <li>
              <a href="#"><span class="icon-dribbble"></span></a>
            </li>
          </ul>
        </div>
        <!-- /.widget -->
      </div>
      <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->

    <div class="row mt-5">
      <div class="col-12 text-center">
        

        <p>
          Copyright &copy;
          <script>
            document.write(new Date().getFullYear());
          </script>
          . {{ __("All Rights Reserved") }}. &mdash; {{ __("Designed with love by") }}
          <a href="https://doitscale.com" target="_blank">doitscale.com</a>
          <!-- License information: https://doitscale.com/license/ -->
        </p>
      </div>
    </div>
  </div>
  <!-- /.container -->
</div>