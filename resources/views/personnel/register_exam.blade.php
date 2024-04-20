@extends('layouts.app')
@section('title', 'Register Exam')
@section('content')
    <div class="container">
        <div class="row">
            @if($application_is_approved == "approved")
                @if($registeredPersonnel->is_registered == 0)
                    <h5><b>{{ $exam->exam_name }} Personnel Registration Exams are open for registration.</b></h5>
                    <div class="alert alert-danger">
                        <p><b>Attention!</b> You are not registered for the exam. Please click the register for exam button below to confirm that you will sit for this exam.</p>
                    </div>
                   <div class="col-md-12 ">
                    <div class="card">
                        <div class="card-header">
                            <h5>You will be required to sit for the following subjects</h5>
                        </div>
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
                    <div class="col-md-12">
                        <form action="{{ route('personnel.exam.register') }}" method="post">
                            @csrf
                            <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <button type="submit" class="btn btn-sm brand-bg-color">Register for Exam</button>
                        </form>
                    </div>
                     @else
                    <h5><b>{{ $exam->exam_name }} Personnel Registration Exams.</b></h5>
                    <div class="alert alert-success text-center">
                        <p><b>You are registered for the exam. Your exam number is {{ $exam_number->exam_number }}</b></p>
                    </div>
                @endif
            @else
                <div class="alert alert-danger">
                    <p><b>Your application is not approved yet. Please come back soon.</b></p>
                </div>
            @endif
        </div>
@endsection
