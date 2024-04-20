@extends('layouts.app')
@section('title', 'System Configurations')
@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <a href="{{ route('configs.roles') }}" class="link-dark link-offset-2 link-underline-opacity-0"><div class="info-box">
                    <span class="info-box-icon"><img src="{{asset('dist/img/settings.png')}}" class="brand-color" alt="" style="height: 90px; width: 90px"></span>

                    <div class="info-box-content">
                        <h6>User Roles</h6>
                        <span class="info-box-text">Set User Roles, Assign Permissions</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                    <!-- /.info-box-content -->
                </div></a>
        </div>

        <div class="col-md-4 col-sm-6 col-12">
            <a href="{{ route('configs.exam') }}" class="link-dark link-offset-2 link-underline-opacity-0"><div class="info-box">
                    <span class="info-box-icon"><img src="{{asset('dist/img/exam.png')}}" alt="" style="height: 90px; width: 90px"></span>

                    <div class="info-box-content">
                        <h6>Exams</h6>
                        <span class="info-box-text">Exam configurations</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                    <!-- /.info-box-content -->
                </div></a>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <a href="{{ route('configs.licence') }}" class="link-dark link-offset-2 link-underline-opacity-0"><div class="info-box">
                    <span class="info-box-icon"><img src="{{asset('dist/img/licence.png')}}" alt="" style="height: 90px; width: 90px"></span>

                    <div class="info-box-content">
                        <h6>License</h6>
                        <span class="info-box-text">License configurations</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                    <!-- /.info-box-content -->
                </div></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <a href="{{ route('configs.backup') }}" class="link-dark link-offset-2 link-underline-opacity-0"><div class="info-box">
                    <span class="info-box-icon"><img src="{{asset('dist/img/backup.png')}}" class="brand-color" alt="" style="height: 90px; width: 90px"></span>

                    <div class="info-box-content">
                        <h6>System Backup</h6>
                        <span class="info-box-text">Database Backup</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                    <!-- /.info-box-content -->
                </div></a>
        </div>
    </div>
@endsection
