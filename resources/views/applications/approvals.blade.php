@extends('layouts.app')
@section('title', 'Applications For Approval')
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
        <div class="card elevation-0">
            <div class="card-body">
                <div class="table-responsive">
                    @if(count($applications) > 0)
                        <table class="table table-bordered table-striped table-hover" id="approvalTable">
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
                                <td>{{ $application->personnelCategory->name }}</td>
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
                                    <a href="{{ route('applications.screen.approve', $application->id) }}"
                                       class="btn btn-sm brand-bg-color">Approve</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                            <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                            <p>No applications have been sent for approval</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            $('#approvalTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
