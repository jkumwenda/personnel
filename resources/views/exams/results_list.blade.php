@extends('layouts.app')
@section('title', 'Exam Results')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h5>
                    {{$exam_name}} Pharmacy Personnel Exams Results
                </h5>
                <table class="table table-striped table-hover table-avatar table-bordered" id="resultsTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Personnel Name</th>
                        <th>Category</th>
                        <th>Exam Number</th>
                        <th>Exam Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($examResults as $key => $result)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $result->user->name}}</td>
                            <td>{{ $result->user->PersonnelCategory->name  }}</td>
                            <td>{{ $result->exam_number }}</td>
                            <td>
                                <div class="badge {{ $result->all_passed == "true" ? "badge-success" : "badge-danger" }}">{{ $result->all_passed == "true" ? "Passed" : "Failed" }}</div></td>
                            <td>
                                <a href="{{route('exam.view.grades', [$result->user->id, $exam_id])}}" class="btn btn-sm brand-bg-color">View Grades</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#resultsTable').DataTable();
        });
    </script>
@endsection

