@extends('layouts.app')
@section('title', 'Grades')
@section('content')
    <div class="row">
        <div class="col-md-12">
                <div class="card elevation-0 border-0">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>{{ $username }} | {{ $exam_number }}</b></h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title float-right">Overall Remark: <button class="btn btn-sm {{ $all_passed ? "btn-success" : "btn-danger" }}">{{ $all_passed ? "Passed" : "Failed" }}</button></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="resultsTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Course Code</th>
                                <th>Score</th>
                                <th>Remark</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->course_name }}</td>
                                    <td>{{ $result->course_code }}</td>
                                    <td>{{ $result->score }}</td>
                                    <td>
                                        <button class="btn btn-sm {{ $result->score >= 50 ? "btn-success" : "btn-danger" }}">{{ $result->score >= 50 ? "Passed" : "Failed" }}</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#resultsTable').DataTable({
            "lengthChange": false,
            "paging": false,
            "searching": false,
            "info": false,
        });
    });
</script>
@endsection

