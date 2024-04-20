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

            <p class="my-3">The <b>{{ $exam->exam_name }} Personnel Registration Exams</b> results are out and your authority is being sought to approve the results.</p>
            <p>Please review and approve the results.</p>

            <p><b>Note:</b></p>
            <p>Once you approve the results, the exam will be closed, the results will be published and licences will be generated.</p>
            <p>Click the button below to review the results and approve.</p>

            <a href="{{ route('exams.list') }}" class="btn btn-sm brand-bg-color">Review Results and Approve</a>

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
