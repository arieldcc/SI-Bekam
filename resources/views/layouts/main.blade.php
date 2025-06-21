<!doctype html>
<html lang="en">
  <!-- [Head] start -->
  <head>
    <title>@yield('title')</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="description"
      content="Berry is trending dashboard template made using Bootstrap 5 design framework. Berry is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies."
    />
    <meta
      name="keywords"
      content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard"
    />
    <meta name="author" content="codedthemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ url('') }}/public/assets/images/favicon.svg" type="image/x-icon" />
 <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link" />
<!-- [phosphor Icons] https://phosphoricons.com/ -->
<link rel="stylesheet" href="{{ url('') }}/public/assets/fonts/phosphor/duotone/style.css" />
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="{{ url('') }}/public/assets/fonts/tabler-icons.min.css" />
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="{{ url('') }}/public/assets/fonts/feather.css" />
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="{{ url('') }}/public/assets/fonts/fontawesome.css" />
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="{{ url('') }}/public/assets/fonts/material.css" />
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{ url('') }}/public/assets/css/style.css" id="main-style-link" />
<link rel="stylesheet" href="{{ url('') }}/public/assets/css/style-preset.css" />

@yield('css')

  </head>
  <!-- [Head] end -->
  <!-- [Body] Start -->
  <body>
    <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ url('') }}/public/dashboard/index.html" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="{{ url('') }}/public/assets/images/logo-dark.svg" alt="" class="logo logo-lg" />
      </a>
    </div>

    @if (auth()->user()->role=="admin")
        @include('layouts.menuAdmin')
    @elseif (auth()->user()->role=="terapis")
        @include('layouts.menuTerapis')
    @else
        @include('layouts.menuPasien')
    @endif

  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
 <!-- [ Header Topbar ] start -->
    @include('layouts.header')
<!-- [ Header ] end -->


    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">

        @yield('content')

        <!-- [ Main Content ] end -->
      </div>
    </div>
    <!-- [ Main Content ] end -->

    @include('layouts.footer')
 <!-- Required Js -->
<script src="{{ url('') }}/public/assets/js/plugins/popper.min.js"></script>
<script src="{{ url('') }}/public/assets/js/plugins/simplebar.min.js"></script>
<script src="{{ url('') }}/public/assets/js/plugins/bootstrap.min.js"></script>
{{-- <script src="{{ url('') }}/public/assets/js/icon/custom-font.js"></script> --}}
<script src="{{ url('') }}/public/assets/js/script.js"></script>
<script src="{{ url('') }}/public/assets/js/theme.js"></script>
<script src="{{ url('') }}/public/assets/js/plugins/feather.min.js"></script>


<script>
  layout_change('light');
</script>

<script>
  font_change('Roboto');
</script>

<script>
  change_box_container('false');
</script>

<script>
  layout_caption_change('true');
</script>

<script>
  layout_rtl_change('false');
</script>

<script>
  preset_change('preset-1');
</script>



    <!-- [Page Specific JS] start -->
    <!-- Apex Chart -->
    <script src="{{ url('') }}/public/assets/js/plugins/apexcharts.min.js"></script>
    <script src="{{ url('') }}/public/assets/js/pages/dashboard-default.js"></script>
    <!-- [Page Specific JS] end -->

    @yield('js')

  </body>
  <!-- [Body] end -->
</html>
