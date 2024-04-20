<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PRS') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/prism/prism.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div id="app" class="wrapper">

{{--    <div class="progress" style="height: 5px;">--}}
{{--        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>--}}
{{--    </div>--}}

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{asset('dist/img/Logo-1.png')}}" alt="PMRA LOGO" height="200" width="200">
    </div>


    <nav class="main-header navbar navbar-expand">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item responsive-mode">
                <a class="nav-link" ><i class="fas fa-bars"></i></a>
            </li>
            {{--Personnel Registration System--}}
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Personnel Registration System</a>
            </li>
        </ul>

        <!-- Right navbar links
        -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('notifications.index', auth()->user()->id) }}">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
            </li>
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <img src="{{ url('images/profile/'.(Auth::user()->profile_image !== null ? Auth::user()->profile_image : "placeholder.jpg")) }}" alt="User Image" class="user-image">
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('user.account') }}">
                            <i class="fa fa-user text-dark"></i> {{ __('Profile') }}
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="{{ route('password.change') }}">
                            <i class="fa fa-key text-dark"></i> {{ __('Change Password') }}
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt text-dark"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </nav>

    @include('ui.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                      <h5 class="m-0">
                        <a href="javascript:history.back()" class="text-decoration-none brand-color"><i class="fas fa-long-arrow-alt-left"></i></a>
                         @yield('title')
                        </h5>
                    </div>
                    <div class="col-sm-6 justify-content-end">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="text-decoration: none">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>
</div>

{{--<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>--}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script defer src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script defer src="https://unpkg.com/slim-js"></script>
    <script defer type="module" src="{{asset('plugins/popper/popper.js')}}"></script>
    <script defer src="{{asset('plugins/prism/prism.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script defer src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- jQuery Knob Chart -->
    <script defer src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script defer src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script defer src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- AdminLTE App -->
    <script defer src="{{asset('dist/js/adminlte.js')}}"></script>

    <script defer src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script defer src="{{asset('plugins/fullcalendar/main.js')}}"></script>
    <script defer src="{{asset('plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
    <script defer src="{{asset('plugins/raphael/raphael.min.js')}}"></script>
    <script defer src="{{asset('plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
    <script defer src="{{asset('plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- ChartJS -->
<script defer src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script defer src="{{asset('dist/js/pages/dashboard2.js')}}"></script>
<script defer src="{{asset('js/style.js')}}"></script>

<!-- SweetAlert2 -->
@include('sweetalert::alert')
<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@yield('scripts')
{{--<script>--}}
{{--    $(document).ready(function() {--}}
{{--        // Hide the progress bar when the page has finished loading--}}
{{--        $(".progress").hide();--}}
{{--    });--}}

{{--    $(window).on('load', function() {--}}
{{--        // Show the progress bar when the page is loading--}}
{{--        $(".progress").show();--}}
{{--    });--}}
{{--</script>--}}
<script>
$(document).ready(function() {
    $('#addRow').click(function() {
        let row = $('#academics').children().clone();
        $('#academics').append(row);
    });

    $('#addRowProf').click(function() {
        let row = $('#professional').children().clone();
        $('#professional').append(row);
    });

    $('#removeRow').click(function() {
        if ($('#academics').children().length >= 2) {
            $('#academics').children().remove();
        }
    });


});
</script>
</body>
</html>
