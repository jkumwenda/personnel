@extends('layouts.app')
@section('title', 'Audit Trails')
@section('content')
    <div class="container m-4">
        <div class="card elevation-0">
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm table-hover datatable" id="trailTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>User ID</th>
                        <th>Action</th>
                        <th>IP Address</th>
                        <th>Agent</th>
                        <th>URL</th>
                        <th>Action On</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($trails as $trail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trail->username }}</td>
                            <td>{{ $trail->user_id }}</td>
                            <td>{{ $trail->action }}</td>
                            <td>{{ $trail->ip_address }}</td>
                            <td>{{ $trail->user_agent }}</td>
                            <td>{{ $trail->url }}</td>
                            <td>{{ date('d M Y H:i:s', strtotime($trail->created_at)) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
$(document).ready(function() {
    $('#trailTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "order": [[0, "asc"]],
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});
    </script>
@endsection
