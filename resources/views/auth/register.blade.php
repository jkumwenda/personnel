<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PRS') }}</title>

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
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="container mt-5">
    <div class="container">
        <div class="">
            <div class="card">
                <div class="card-body py-5 px-md-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="firstName">First name <span class="text-danger">*</span></label>
                                    <input id="firstName" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname">
                                    @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="lastName">Last name <span class="text-danger">*</span></label>
                                    <input id="lastName" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname">
                                    @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <di class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="male">{{__("Male")}}</option>
                                        <option value="female">{{__("Female")}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="personnelCategory">Personnel Category <span class="text-danger">*</span></label>
                                    <select name="personnelCategory" id="personnelCategory" class="form-control">
                                        @foreach($personnelCategories as $personnelCategory)
                                            <option value="{{$personnelCategory->id}}">{{$personnelCategory->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </di>
                        <!-- Phone number and email -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="phoneNumber">Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" id="phoneNumber" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required autocomplete="phone">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="country">Country<span class="text-danger">*</span></label>
                                    <select name="country" id="country" class="form-control">
                                        <option>-- Select --</option>
                                        <option value="malawi" selected>{{__("Malawi")}}</option>
                                        <option value="zambia">{{__("Zambia")}}</option>
                                        <option value="zimbabwe">{{__("Zimbabwe")}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="national_id">National ID No</label>
                                    <input id="national_id" type="text" class="form-control @error('national_id') is-invalid @enderror" name="national_id" value="{{ old('national_id') }}" autocomplete="national_id">
                                    @error('national_id')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="postal_address">Postal Address</label>
                                    <textarea name="postal_address" id="postal_address" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="physical_address">Physical Address</label>
                                    <textarea name="physical_address" id="physical_address" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn brand-bg-color btn-block mb-4">
                            {{__("Register")}}
                        </button>

                        <p class="mb-5 text-muted text-center">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-brand">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script defer src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script defer src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script defer src="https://unpkg.com/slim-js"></script>
<script defer type="module" src="{{asset('plugins/popper/popper.js')}}"></script>
<script defer src="{{asset('plugins/prism/prism.js')}}"></script>
<!-- Bootstrap 4 -->
<script defer src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- jQuery Knob Chart -->
<script defer src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script defer src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script defer src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script defer src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script defer src="{{asset('dist/js/adminlte.js')}}"></script>
<script defer src="{{asset('plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
<script defer src="{{asset('plugins/raphael/raphael.min.js')}}"></script>
<script defer src="{{asset('plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
<script defer src="{{asset('plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
<!-- ChartJS -->
<script defer src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script defer src="{{asset('dist/js/pages/dashboard2.js')}}"></script>
<script defer src="{{asset('js/style.js')}}"></script>

</body>
</html>

