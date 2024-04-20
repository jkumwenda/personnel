@extends('layouts.app')
@section('title', 'Upload Exams Results')
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
    <div class="row">
        <div class="col-md-12">
            <div class="card elevation-0">
                <div class="card-header">
                    <h5 class="card-title">Open Exam List</h5>
                </div>
                <div class="card-body">
                    @if(count($exams) > 0)
                        <table class="table table-bordered" id="open_exams_list">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Exam Name</th>
                            <th>Created On</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($exams as $exam)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if(!$exam->send_for_approval)
                                        <a href="{{ route('exams.upload-view.personnel', $exam->id) }}">{{ $exam->exam_name }}</a>
                                    @else
                                        {{ $exam->exam_name }}
                                    @endif
                                </td>
                                <td>{{ date('d F Y', strtotime($exam->created_at)) }}</td>

                                <td>
                                    @if(!$exam->send_for_approval)
                                        <a href="{{ route('exams.upload-view.personnel', $exam->id) }}" class="btn btn-primary btn-sm">Upload Grades</a>
                                        {{-- TODO: Show send for approval if grades are entered--}}
                                        <a href="{{ route('exam.send', $exam->id) }}" class="btn brand-bg-color btn-sm">Send for approval</a>
                                    @else
                                        <div class="badge badge-success">{{__("Sent for approvals")}}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                            <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                            <p>There are not any open exams at the moment.</p>
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
        $(document).ready(function() {
            $('#open_exams_list').DataTable();
        });
    </script>
@endsection
