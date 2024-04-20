@extends('layouts.app')
@section('title', 'MY Results')
@section('content')

    @if($exam !== null)
        @if($examCount > 1)
            <div class="row">
                <div class="col-md-12">
                    @if(count($results) !== 0)
                        @foreach($results as $exam_id => $groupedResults)
                            @if($exam_names[$exam_id]['published'] !== 0)
                                <div class="card elevation-0 border-0">
                                    <div class="header">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="title"><b>{{ array_key_exists($exam_id, $exam_names) ? $exam_names[$exam_id]['name'] : 'N/A' }} Pharmacy Personnel Exams Results</b></h5>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="title float-right">Overall Remark: <button class="btn btn-sm {{ $all_passed_per_exam[$exam_id] ? "btn-success" : "btn-danger" }}">{{ $all_passed_per_exam[$exam_id] ? "Passed" : "Failed" }}</button></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="resultsTable1">
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
                                            @foreach($groupedResults as $result)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $result->course_name }}</td>
                                                    <td>{{ $result->course_code }}</td>
                                                    <td>{{ $result->score }}</td>
                                                    <td>
                                                        <button class="btn btn-sm {{ $result->score >= 50 ? "btn-success" : "btn-danger" }}">{{ $result->score >= 50 ? "pass" : "fail" }}</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer">
                                        @php
                                            // Get the most recent failed exam ID
                                            $recent_failed_exam_id = max(array_keys($all_passed_per_exam, false));
    //                                        dd($all_passed_per_exam);
                                        @endphp

                                        @if($all_passed_per_exam[$exam_id])
                                            <a href="{{ route('license.original', $user_id) }}" class="btn btn-sm brand-bg-color">Download License</a>
                                        @elseif($exam_id == $recent_failed_exam_id && $all_passed_per_exam[$recent_failed_exam_id])
                                            <div class="mt-3">
                                                <h5><b>Notice</b></h5>
                                                <p>You may wish to apply to re-sit the examination. You shall be required to resit the following parts:</p>
                                                <ul>
                                                    @foreach($failed_subjects_per_exam[$exam_id] as $failed_subject)
                                                        <li>{{ $failed_subject['name'] }}</li>
                                                    @endforeach
                                                </ul>
                                                <a href="{{ route('license.provisional.personnel', $user_id) }}" class="btn btn-sm brand-bg-color">Download Notice</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @else
                                <div class="container">
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                        <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                        <p>Your results for {{ $exam_names[$exam_id]['name'] }} Pharmacy Personnel Exams are not yet available.</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="alert alert-danger">
                            <h5 class="text-center"><b>Your results are currently unavailable.</b></h5>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="row">
            <div class="col-md-12">
                @if(count($results) !== 0 && $exam_is_published !== 0)
                <div class="card elevation-0 border-0">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>{{ $exam_name }} Pharmacy Personnel Exams Results</b></h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title float-right">Overall Remark: <button class="btn btn-sm {{ $all_passed ? "btn-success" : "btn-danger" }}">{{ $all_passed ? "Passed" : "Failed" }}</button></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="resultsTable2">
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
                                        <button class="btn btn-sm {{ $result->score >= 50 ? "btn-success" : "btn-danger" }}">{{ $result->score >= 50 ? "pass" : "fail" }}</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if($all_passed)
                            <a href="{{ route('license.original', $user_id) }}" class="btn btn-sm brand-bg-color">Download License</a>
                        @else
                            <div class="mt-3">
                                <h5><b>Notice</b></h5>
                                <p>You may wish to apply to re-sit the examination. You shall be required to resit the following parts:</p>
                                <ul>
                                    @foreach($failed_subjects as $failed_subject)
                                        <li>{{ $failed_subject['name'] }}</li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('license.provisional.personnel', $user_id) }}" class="btn btn-sm brand-bg-color">Download Notice</a>
                            </div>
                        @endif
                    </div>
                </div>
                @else
                    <div class="container">
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                            <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                            <p>Your results are not available yet</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    @else
        <div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                <p>Your results are not available yet</p>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#resultsTable1').DataTable({
                "searching": false,
                "paging": false,
                "info": false,

            });
            $('#resultsTable2').DataTable({
                "searching": false,
                "paging": false,
                "info": false,

            });
        });
    </script>
@endsection
