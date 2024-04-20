@extends('layouts.app')
@section('title', $application->application_id)
@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="page active">
        <div class="row">
            <div class="col-md-12">
                <h5>General Details</h5>
                <div class="card-body mt-2">
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <tbody>
                            <tr>
                                <th>Applicant Name</th>
                                <td>{{ $user->first_name }}  {{ $user->last_name }}</td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div></div>
    <div class="page">
        <div class="row">
            <div class="col-md-12">
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
            <div class="col-md-12">
                <h5>Professional Qualifications</h5>
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Registration</th>
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
    </div>
    <div class="page">
        <div class="row">
            <div class="col-md-6">
                <h5>List of Documents</h5>
                <ol class="list-group">
                    @foreach($application->relevant_files as $document)
                        <li class="list-group-item">{{$loop->iteration }} <a href="{{ Storage::url(urlencode($document)) }}" target="_blank" class="text-decoration-none">{{ str_replace('public/uploads/'.$application->user_id.'/relevant_documents/', '', $document) }}</a></li>
                    @endforeach
                </ol>
            </div>
        </div>
        @if($application->application_status !== "rejected")
        <div class="row">
                <div class="col-md-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>
                                        @if($application->application_status !== "in-review")
                                            <form action="{{ route('applications.screen.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="1">
                                                <input type="hidden" name="application_id" value="{{ $application->id }}">
                                                <button type="submit" class="btn btn-sm brand-bg-color">Send for Approval</button>
                                            </form>
                                        @else
                                            {{-- Delete --}}
                                            <form action="{{ route('applications.screen.destroy', $application->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-warning">Cancel</button>
                                            </form>
                                        @endif
                                    </th>
                                    @if($application->application_status !== "in-review")
                                        <th>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModalCenter">
                                                Reject
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="rejectModalCenter" tabindex="-1" role="dialog" aria-labelledby="rejectModalCenter" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rejectModalCenterTitle">Reject Application</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('applications.screen.store') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <label for="comment">Reason for rejection <span class="text-danger">*</span></label>
                                                                <textarea name="comment" id="comment" cols="30" rows="10" class="form-control" required></textarea>
                                                                <input type="hidden" name="status" value="0">
                                                                <input type="hidden" name="application_id" value="{{ $application->id }}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-sm btn-danger">Confirm</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <button id="prev" class="btn btn-sm brand-bg-color">Previous</button>
    <button id="next" class="btn btn-sm brand-bg-color">Next</button>

@endsection
@section('scripts')
    <script>
    $(document).ready(function() {
        let pages = $('.page');
        let currentPage = 0;
        pages.hide();
        pages.first().show();
        $('#prev').hide();
        $('#next').click(function() {
            $('#prev').show();
            pages.eq(currentPage).hide();
            currentPage = currentPage + 1;
            pages.eq(currentPage).show();
            if (currentPage === pages.length - 1) {
                $('#next').hide();
            }
        });
        $('#prev').click(function() {
            $('#next').show();
            pages.eq(currentPage).hide();
            currentPage = currentPage - 1;
            pages.eq(currentPage).show();
            if (currentPage === 0) {
                $('#prev').hide();
            }
        });
    });
    </script>
@endsection
