@extends('layouts.app')
@section('title', 'Edit Subject')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('courses.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" value="{{ $course->name }}" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="code">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" value="{{ $course->code }}" name="code" id="code" class="form-control" required>
                        </div>
                        <input type="hidden" name="id" value="{{ $course->id }}">
                        <button type="submit" class="btn btn-sm brand-bg-color">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
