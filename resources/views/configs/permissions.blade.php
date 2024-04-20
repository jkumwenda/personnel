@extends('layouts.app')
@section('title', 'Permissions')
@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="float-start">Permissions for {{ $role->name }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($role->permissions as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
@endsection
