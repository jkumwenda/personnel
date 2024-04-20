@extends('layouts.app')
@section('title', 'Edit Role')
@section('content')
    <div class="row">
        <div class="card elevation-0">
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ $role->name }}" class="form-control" placeholder="Role Name" required>
                    </div>
                    <div class="form-group">
                        <label for="permissions">Permissions</label>
                        @foreach($permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="permission{{ $permission->id }}" value="{{ $permission->name }}"
                                    {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm brand-bg-color">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
