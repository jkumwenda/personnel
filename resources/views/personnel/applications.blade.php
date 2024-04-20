@extends('layouts.app')
@section('title', 'Applications')
@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <a href="{{ route('applications.apply') }}" class="link-dark link-offset-2 link-underline-opacity-0">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-folder-open"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Apply for licence</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <a href="{{ route('applications.list') }}" class="link-dark link-offset-2 link-underline-opacity-0">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-folder"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">My Applications</span>
                    </div>
                </div>
            </a>

        </div>
    </div>
@endsection
