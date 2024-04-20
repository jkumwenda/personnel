@extends('layouts.app')
@section('title', 'Enter Grades')
@section('content')
    <div class="row">
        <h6>Enter Grades for <span class="text-bold">{{ $user->name }} | {{ $exam_number->exam_number }}</span></h6>
        <div class="col-md-12">
            <form action="{{route('results.store', ['user_id' => $user->id, 'exam_id' => $exam_number->exam_id])}}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Grade</th>
                    </thead>
                    <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->code }}</td>
                            <td>
                                <input type="hidden" name="subject_id[]" value="{{ $subject->id }}">
                                <input type="text" name="grade[]" class="form-control" placeholder="Enter Grade">
                            </td>
                        </tr>
                    @endforeach
                </table>
                <input type="submit" class="btn btn-sm brand-bg-color float-right" value="Submit">
            </form>
        </div>
    </div>
@endsection
