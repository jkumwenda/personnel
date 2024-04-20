@extends('layouts.app')
@section('title', $user->first_name . ' ' . $user->last_name . ' Profile')
@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ url('images/profile/'.($user->profile_image !== null ? $user->profile_image : "placeholder.jpg")) }}" alt="User Image" class="rounded-circle" width="200" height="200" style="object-fit: cover">
                                <a href="#" class="btn btn-sm brand-bg-color mt-2" data-toggle="modal" data-target="#editImageModal">Edit Image <i class="fas fa-edit"></i></a>
                                <div class="mt-3">
                                    <h4>{{ $user->first_name . ' ' . $user->last_name }}</h4>
                                    <p class="text-secondary mb-1">{{ $user->roles->first()->name }}</p>
                                    @if($user->roles->first()->name == "personnel")
                                        <div class="badge badge-info">{{ $user->personnelCategory->name }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="editImageModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editImageModalLabel">Edit Profile Image</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('profile.image.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="profile_image">Profile Image <span class="text-danger">*</span></label>
                                                    <input type="file" class="form-control-file" id="profile_image" name="profile_image" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-sm brand-bg-color">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">First Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->first_name ?? "N/A" }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Last Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->last_name ?? "N/A" }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Gender</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ ucfirst($user->gender ?? "N/A") }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->phone_number ?? "N/A" }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Postal Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->postal_address ?? "N/A" }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Physical Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->physical_address ?? "N/A" }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.edit', $user->id) }}">Edit Profile <i class="fas fa-edit"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
