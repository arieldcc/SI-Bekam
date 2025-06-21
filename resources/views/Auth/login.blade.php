<!doctype html>
<html lang="en">
  <!-- [Head] start -->
  <head>
    <title>Login | Bekam</title>
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

    <div class="auth-main">
      <div class="auth-wrapper v3">
        <div class="auth-form">
          <div class="card my-5">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
              <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <a href="#" class="d-flex justify-content-center">
                        <img src="{{ url('') }}/public/assets/images/logo-bekam.png" alt="image xx" class="img-fluid" />
                    </a>
                    <h5 class="my-4 d-flex justify-content-center">Form Login</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </div>
                    @endif

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Username or Email" value="{{ old('email') }}" required />
                        <label for="floatingInput">Email address / Username</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="Password" required />
                        <label for="floatingInput1">Password</label>
                    </div>

                    <div class="d-flex mt-1 justify-content-between">
                        <div class="form-check">
                        <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" name="remember" />
                        <label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
                        </div>
                        <a href="#" class="text-secondary">Forgot Password?</a>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-secondary">Sign In</button>
                    </div>

                    <hr />
                    <h5 class="d-flex justify-content-center">Tidak punya akun?</h5>
                    </form>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ url('') }}/public/assets/js/plugins/popper.min.js"></script>
    <script src="{{ url('') }}/public/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ url('') }}/public/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ url('') }}/public/assets/js/icon/custom-font.js"></script>
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


  </body>
  <!-- [Body] end -->
</html>
