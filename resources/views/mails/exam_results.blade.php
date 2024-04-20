<!DOCTYPE html>
<html>
<head>
    <title>Mail</title>
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <style>
        .custom-color {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="custom-color">
<div class="container bg-secondary-subtle text-dark">
    <div class="text-center">
        <img src="{{asset('dist/img/Logo-3.png')}}" alt="Logo" class="my-3" style="height: 100px; width: 100px">
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="mt-3">Dear {{ $user->name }},</h4>

            <p class="my-3">We are pleased to inform you that <b>{{ $exam->exam_name }} Personnel Registration Exams</b> are out!</p>
            <p class="my-3">You can now view your results by logging into your account and navigating to the <b>Exams</b> section.</p>
            <p class="my-3">We wish you the best of luck!</p>

            <a href="{{ route('results.myresults') }}" class="btn btn-sm brand-bg-color">View your results</a>

            <p class="mt-5">Regards,</p>
            <p>{{ config('app.name') }}</p>
        </div>
    </div>
    <footer class="text-center mt-5">
        <p class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>
</div>
</body>
</html>
