@extends('layouts.app')
@section('title', 'Approved Applications')
@section('content')
    <div class="row">
        <div class="card elevation-0">
            <div class="card-body">
                <div class="table-responsive">
                    @if(count($applications) > 0)
                        <table class="table table-bordered table-striped table-hover" id="approvaledTable">
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
                                    <td><div class="badge  badge-success" style="height: 25px;">{{ $application->application_status }}</div></td>
                                    <td>
                                        <a href="{{ route('applications.show', $application->id) }}"
                                           class="btn btn-sm brand-bg-color"> View <i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                            <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                            <p>No approved applications are available at the moment</p>
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
            $('#approvaledTable').DataTable({
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
