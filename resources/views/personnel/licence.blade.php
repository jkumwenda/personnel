@extends('layouts.app')
@section('title', 'My License Details')
@section('content')
<div class="container">
    @if(!$license)
        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
            <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
            <p>You don't have a license yet</p>
        </div>
    @else
    <div class="card elevation-0">
        <div class="card-header">
            <h5>{{ $user->name }} | {{ $license->registration_number }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Registration Number:</strong>  {{ $license->registration_number }}</p>
            <p><strong>License Type:</strong>  {{ auth()->user()->PersonnelCategory->name }}</p>
            <p><strong>Effective Date:</strong>  {{ date('d F Y', strtotime($license->effective_date)) }}</p>
            <p><strong>Expiry Date:</strong>   {{ date('d F Y', strtotime($license->expiry_date)) }}</p>
            <h6 class="text-center alert alert-danger"> Your licence will expire in the next {{ \Carbon\Carbon::now()->diffInDays($license->expiry_date) }} days. You are required to pay the retention fee before the license expires</h6>
        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-sm brand-bg-color">Pay Renew Fees</a>
            <a href="{{ route('license.original', $user->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
    </div>
    @endif
</div>
@endsection
