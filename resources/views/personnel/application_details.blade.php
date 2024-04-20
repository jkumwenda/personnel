@extends('layouts.app')
@section('title', $application->application_id)
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <tbody>
                        <tr>
                            <th>Applicant Name</th>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <th>Application ID</th>
                            <td>{{ $application->application_id }}</td>
                        </tr>
                        <tr>
                            <th>Register For</th>
                            <td>{{ $application->personnelCategory->name }}</td>
                        </tr>
                        <tr>
                            <th>Training Domain</th>
                            <td>{{ $application->training }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $application->created_at->format('d/m/Y h:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <div class="badge {{ $application->application_status == "pending" ? "badge-primary" : ($application->application_status == "in-review" ? "badge-secondary" : ($application->application_status == "rejected" ? "badge-danger" : "badge-success")) }}">
                                    {{ $application->application_status }}
                                </div>
                            </td>
                        </tr>
                        @if($application->application_status == "rejected")
                            <tr>
                                <th class="bg-danger">Rejection Comment</th>
                                <td class="bg-danger">{{ $comment }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h6 class="text-bold">List of Documents</h6>
            <ol class="list-group">
                @foreach($application->relevant_files as $document)
                    <li class="list-group-item">{{$loop->iteration }} <a href="{{ Storage::url($document) }}" target="_blank" class="text-decoration-none">{{ str_replace('public/uploads/'.$application->user_id.'/relevant_documents/', '', $document) }}</a></li>
                @endforeach
            </ol>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 mb-2">
            <h5>Academic Qualifications</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Qualification</th>
                            <th>Month/Year</th>
                            <th>Institution</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($application->academic_qualification as $aq)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$aq['acq']}}</td>
                                <td>{{$aq['acd']}}</td>
                                <td>{{$aq['aci']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <h5>Professional Qualifications</h5>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Qualification</th>
                        <th>Month/Year</th>
                        <th>Institution</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($application->professional_qualification as $pq)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$pq['prc']}}</td>
                            <td>{{$pq['prd']}}</td>
                            <td>{{$pq['pri']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if($application->application_status == "rejected")
        <div class="row">
            <div class="col-md-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>
                                    <a href="{{ route('applications.edit', $application->id) }}" class="btn btn-sm brand-bg-color">Edit</a>
                                </th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
