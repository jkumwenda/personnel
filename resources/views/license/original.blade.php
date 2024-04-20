@extends('layouts.app')
@section('title', 'Original Licenses')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-active-tab" data-toggle="tab" href="#active-licenses" role="tab" aria-controls="nav-active" aria-selected="true">Active <div class="badge brand-bg-color">{{ $active_licenses_count }}</div></a>
                    <a class="nav-item nav-link" id="nav-expired-tab" data-toggle="tab" href="#expired-licenses" role="tab" aria-controls="nav-expired" aria-selected="false">Expired <div class="badge badge-danger">{{ $expired_licenses_count }}</div></a>
                    <a class="nav-item nav-link" id="nav-revoked-tab" data-toggle="tab" href="#revoked-licenses" role="tab" aria-controls="nav-revoked" aria-selected="false">Revoked <div class="badge badge-secondary">{{ $revoked_licenses_count }}</div></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="active-licenses" role="tabpanel" aria-labelledby="nav-active-tab">
                    <div class="card elevation-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                @if($active_licenses_count > 0)
                                    <table class="table table-bordered table-striped table-hover table-sm datatable" id="activeLicences">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Personnel</th>
                                        <th>Category</th>
                                        <th>Registration #</th>
                                        <th>Effective Date</th>
                                        <th>Expiry Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($active_licenses as $user)
                                        <tr data-entry-id="{{ $user->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->first_name }}  {{ $user->last_name }}</td>
                                            <td>{{ $user->PersonnelCategory->name }}</td>
                                            <td>{{ $user->License->registration_number }}</td>
                                            <td>{{ date('d F Y', strtotime($user->License->effective_date)) }}</td>
                                            <td>{{ date('d F Y', strtotime($user->License->expiry_date)) }}</td>
                                            <td>
                                                <a href="{{ route('license.original', $user->id) }}" class="btn btn-sm brand-bg-color">View</a>
                                                @if(Auth::user()->hasAnyRole(['superadmin', 'Chief Inspector']))
                                                    <a href="#" class="btn btn-sm btn-danger" onclick="confirmRevoke('Revoke Licence for {{ $user->first_name }}  {{ $user->last_name }}', {{ $user->License->id }})">Revoke</a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @else
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                        <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                        <p>No, any active licenses at the moment</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="expired-licenses" role="tabpanel" aria-labelledby="nav-expired-tab">
                    <div class="card elevation-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                @if($expired_licenses_count > 0)
                                    <table class="table table-bordered table-striped table-hover table-sm datatable" id="expiredLicences">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Personnel</th>
                                        <th>Category</th>
                                        <th>Registration #</th>
                                        <th>Effective Date</th>
                                        <th>Expiry Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($expired_licenses as $user)
                                        <tr data-entry-id="{{ $user->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->first_name }}  {{ $user->last_name }}</td>
                                            <td>{{ $user->PersonnelCategory->name }}</td>
                                            <td>{{ $user->License->registration_number }}</td>
                                            <td>{{ date('d F Y', strtotime($user->License->effective_date)) }}</td>
                                            <td>{{ date('d F Y', strtotime($user->License->expiry_date)) }}</td>
                                            <td>
                                                <a href="{{ route('license.original', $user->id) }}" class="btn btn-sm brand-bg-color">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @else
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                        <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                        <p>No expired licenses at the moment</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="revoked-licenses" role="tabpanel" aria-labelledby="nav-revoked-tab">
                    <div class="card elevation-0">
                        <div class="card-body">
                            @if($revoked_licenses_count > 0)
                                <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm datatable" id="revokedLicences">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Personnel</th>
                                        <th>Category</th>
                                        <th>Registration #</th>
                                        <th>Reason for rovokation</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($revoked_licenses as $user)
                                        <tr data-entry-id="{{ $user->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->first_name }}  {{ $user->last_name }}</td>
                                            <td>{{ $user->PersonnelCategory->name }}</td>
                                            <td>{{ $user->License->registration_number }}</td>
                                            <td>{{ $user->License->revoke_reason }}</td>
                                            <td>
                                                <a href="{{ route('license.original', $user->id) }}" class="btn btn-sm brand-bg-color">View</a>
                                                @if(Auth::user()->hasAnyRole(['superadmin', 'Chief Inspector']))
                                                    <a href="#" class="btn btn-sm btn-success" onclick="reinstateLicense('Reinstate Licence for {{ $user->first_name }}  {{ $user->last_name }}', {{ $user->License->id }})">Reinstate</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                    <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                    <p>No revoked licenses at the moment</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section("scripts")
    <script>
        $(function () {
            $('#activeLicences').DataTable({
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
            $('#expiredLicences').DataTable({
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
            $('#revokedLicences').DataTable({
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
    <script>
        function confirmRevoke(title, licenseId) {
            Swal.fire({
                title: title,
                text: 'Please provide a reason for revoking the license',
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Enter a reason',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!'
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#052f6b',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Revoke!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get CSRF token
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send AJAX POST request
                    $.ajax({
                        url: '/license/revoke', // Update this if your route URL is different
                        type: 'POST',
                        data: {
                            _token: csrfToken,
                            licenseId: licenseId,
                            reason: result.value
                        },
                        success: function(response) {
                            if(response === 'success'){
                                Swal.fire('Success', 'The license has been revoked.', 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            }
                            else{
                                Swal.fire('Error', 'There was an error revoking the license.', 'error')
                                    .then(() => {
                                        location.reload();
                                    });
                            }
                        },
                        error: function(response) {
                            Swal.fire('Error', 'There was an error revoking the license.', 'error');
                        }
                    });
                }
            });
        }
    </script>
    <script>
        function reinstateLicense(title, licenseId) {
            Swal.fire({
                title: title,
                text: 'You are about to reinstate the license. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#052f6b',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Reinstate!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Send AJAX POST request
                    $.ajax({
                        url: '/license/reinstate/' + licenseId,
                        type: 'GET',
                        success: function(response) {
                            if(response === 'success'){
                                Swal.fire('Success', 'The license has been reinstated.', 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            }
                            else{
                                Swal.fire('Error', 'There was an error reinstating the license.', 'error')
                                    .then(() => {
                                        location.reload();
                                    });
                            }
                        },
                        error: function(response) {
                            Swal.fire('Error', 'There was an error reinstating the license.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
