@extends('layouts.app')
@section('title', 'Notices')
@section('content')
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="card elevation-0">
                <div class="card-body">
                    <div class="table-responsive">
                        @if(count($users) > 0)
                            <table class="table table-bordered table-striped table-hover table-sm datatable" id="provisionalLicenses">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Exam Number</th>
                                <th>Exam Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr data-entry-id="{{ $user->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->first_name }}  {{ $user->last_name }}</td>
                                    <td>{{ $user->examNumber->exam_number }}</td>
                                    <td>{{ $user->exam_name }}</td>
                                    <td>
                                        <a href="{{ route('license.provisional.personnel', $user->id) }}" class="btn btn-sm brand-bg-color">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                <p>There are no any notices at the moment</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        $(function () {
            $('#provisionalLicenses').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "order": [[ 0, "asc" ]],
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": 4 }
                ]
            });
        });
    </script>
@endsection
