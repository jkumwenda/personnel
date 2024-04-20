@extends('layouts.app')
@section('title', 'Edit Exam')
@section('content')
    <div class="container">
        <div class="card d-flex justify-content-center">
            <div class="card-header">
                <h5 class="card-title">{{ $exam->exam_name }} Exams</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('exam.update', $exam->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <div class="form-outline">
                                <label class="form-label" for="exam_name">Exam Name <span class="text-danger">*</span></label>
                                <input type="text" name="exam_name" value="{{$exam->exam_name}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8 mb-4">
                            <div class="form-outline">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" value="{{$exam->start_date}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8 mb-4">
                            <div class="form-outline">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" value="{{$exam->end_date}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8 mb-4">
                            <div class="form-outline">
                                <label for="is_open">Is Open <span class="text-danger">*</span></label>
                                <select name="is_open" id="is_open" class="form-control" required>
                                    <option value="1" {{ $exam->is_open == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $exam->is_open == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
</div>

<button type="submit" class="btn brand-bg-color btn-sm mb-4">
    {{__("Save Changes")}}
</button>

</form>
            </div>
        </div>
    </div>
@endsection
