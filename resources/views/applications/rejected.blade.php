@extends('layouts.app')
@section('title', 'Rejected Applications')
@section('content')
    <div class="row">
        <div class="card elevation-0">
            <div class="card-body">
                @if(count($applications) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="rejectedTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Application ID</th>
                                <th>Register For</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Rejection Comment</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($applications as $application)
                                <tr data-entry-id="{{ $application->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $application->application_id }}</td>
                                    <td>{{ $application->personnelCategory->name }}</td>
                                    <td>{{ $application->created_at->format('d/m/Y') }}</td>
                                    <td><div class="badge badge-danger" style="height: 25px;">{{ $application->application_status }}</div></td>
                                    <td>{{ $application->screen_comment}} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                        <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                        <p>No rejected applications are available at the moment</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            $('#rejectedTable').DataTable({
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
