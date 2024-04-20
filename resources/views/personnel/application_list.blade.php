@extends('layouts.app')
@section('title', 'My Applications')
@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

  <div class="row">
      <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm table-hover datatable">
                  <thead>
                  <tr>
                      <th>#</th>
                      <th>Application ID</th>
                      <th>Register For</th>
                      <th>Training Domain</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($applications as $application)
                      <tr data-entry-id="{{ $application->id }}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $application->application_id }}</td>
                          <td>{{ $application->personnelCategory->name}}</td>
                          <td>{{ $application->training }}</td>
                          <td>{{ $application->created_at->format('d/m/Y') }}</td>
                          <td>
                              <div class="badge {{ $application->application_status == "pending" ? "badge-primary" : ($application->application_status == "in-review" ? "badge-secondary" : ($application->application_status == "rejected" ? "badge-danger" : "badge-success")) }}">
                                  {{ $application->application_status }}
                              </div>
                          </td>
                          <td>
                              <a href="{{ route('applications.show', $application->id) }}"
                                 class="btn btn-sm btn-primary">View</a>
                              @if($application->application_status == "pending")
                                  <a href="{{ route('applications.cancel', $application->id) }}"
                                      class="btn btn-sm brand-bg-color">Withdraw</a>
                              @endif
                              @if($application->application_status == "rejected")
                                  <a href="{{ route('applications.edit', $application->id) }}"
                                      class="btn btn-sm brand-bg-color">Edit</a>
                              @endif
                              @if($application->application_status == "approved")
                                  <a href="{{ route('payments.make_payment', [$application->id, 1]) }}" class="btn btn-sm brand-bg-color">Make Payment</a>
                              @endif
                          </td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>
@endsection

