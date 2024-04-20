@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <div class="row">
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
        <div class="col-md-12 mt-5">
            <div class="card elevation-0">
                <div class="card-header">
                    <h6 class="card-title">Users List</h6>
                    <a href="#" class="btn brand-bg-color btn-sm float-right" data-toggle="modal" data-target="#adduserModel">Add User</a>
                    <div class="modal fade" id="adduserModel" tabindex="-1" role="dialog" aria-labelledby="adduserModelLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="adduserModelLabel">Add User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('users.create') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="firstname">First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="firstname" id="firstname" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="lastname">Last Name</label>
                                                <input type="text" name="lastname" id="lastname" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" id="email" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="role">Role <span class="text-danger">*</span></label>
                                                <select name="role" id="role" class="form-control" required>
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password" id="password" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="reset" class="btn btn-sm btn-secondary">Clear</button>
                                            <button type="submit" class="btn btn-sm brand-bg-color">Add User</button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable" id="usersListTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr data-entry-id="{{ $user->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ url('images/profile/'.($user->profile_image !== null ? $user->profile_image : "placeholder.jpg")) }}" alt="Profile Image" style="width: 30px; height: 30px; border-radius: 50%;"> {{ $user->name }} </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->first()->name }}</td>
                                    <td>{{ $user->created_at->format('d F Y') }}</td>
                                    <td>
                                        <a href="{{ route('users.show', $user->id) }}"
                                               class="btn btn-sm brand-bg-color" title="View"><i class="fa fa-user"></i></a>
                                        @if(Auth::user()->can('user edit'))
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="btn btn-sm btn-info" title="Edit"><i class="fa fa-user-edit"></i></a>
                                        @endif
                                        @if(Auth::user()->hasRole('superadmin'))
                                            <a href=""
                                               class="btn btn-sm btn-warning" title="Reset Password"><i class="fa fa-key"></i></a>
                                        @endif
                                        @if(Auth::user()->can('user delete'))
                                            @if($user->roles->first()->name !== 'superadmin')
                                                <form action="{{ route('users.destroy', $user
                                                        ->id) }}" method="POST"
                                                      style="display: inline-block;" id="deleteForm{{ $user->id }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token"
                                                           value="{{ csrf_token() }}">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('Delete User, {{$user->name}}!', {{ $user->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        $(function () {
            $('#usersListTable').DataTable({
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
    <script>
        function confirmDelete(title, userId) {
            Swal.fire({
                title: title,
                text: 'Are you sure you want to delete? This cannot be reversed!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    document.getElementById('deleteForm' + userId).submit();
                }
            });
        }
    </script>
@endsection

