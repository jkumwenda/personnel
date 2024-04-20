@extends('layouts.app')
@section('title', 'Exams Configurations')
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
        <div class="row m-4">
            <div class="col-md-6">
                <div class="card elevation-0">
                    <div class="card-body">
                        <form action="{{ route('configs.exam.configure') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="northern_region">Northern Region Venue</label>
                                <input type="text" class="form-control @error('northern_region') is-invalid @enderror" id="northern_region" name="northern_region" value="{{ $examConfig->northern_region ?? "" }}">
                                @error('northern_region')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="central_region">Central Region Venue</label>
                                <input type="text" class="form-control @error('central_region') is-invalid @enderror" id="central_region" name="central_region" value="{{ $examConfig->central_region ?? "" }}">
                                @error('central_region')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="southern_region">Southern Region Venue</label>
                                <input type="text" class="form-control  @error('southern_region') is-invalid @enderror" id="southern_region" name="southern_region" value="{{ $examConfig->central_region ?? "" }}">
                                @error('southern_region')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-sm brand-bg-color">Save</button>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="card elevation-0">
                    <div class="card-body">
                        @if($examConfig !== null)
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="vTable">
                                    <thead>
                                    <tr>
                                        <th>Region</th>
                                        <th>Venue</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Northern Region</td>
                                        <td>{{ $examConfig->northern_region }}</td>
                                    </tr>
                                    <tr>
                                        <td>Central Region</td>
                                        <td>{{ $examConfig->central_region }}</td>
                                    </tr>
                                    <tr>
                                        <td>Southern Region</td>
                                        <td>{{ $examConfig->southern_region }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 45vh;">
                                <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                <p>No venues available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#vTable').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
            });
        });
    </script>
@endsection
