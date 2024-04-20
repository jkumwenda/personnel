@extends('layouts.app')
@section('title', 'My Subjects')
@section('content')
    <div class="container">
        @if($registeredPersonnel !== null)
            @if($registeredPersonnel->is_registered == 1)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered dataTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject Name</th>
                                        <th>Subject Code</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->code }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                    <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                    <p>You have not yet registered for any exam. Register to view your subjects.</p>
                </div>
            @endif

        @else
            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                <p>Your application is not approved yet. Please come back soon.</p>
            </div>
        @endif
@endsection
