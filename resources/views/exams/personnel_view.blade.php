@extends('layouts.app')
@section('title', 'Upload Exams Results')
@section('content')
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> {{ $exam->exam_name }} Personnel Registration Exams </h5>
                    <div class="card-tools">
                        <a href="{{route('results.template', $exam->id)}}" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Download Excel Template</a>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#importModal"> <i class="fas fa-upload"></i> Bulk Upload</button>
                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importModalLabel">Upload {{ $exam->exam_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{route('exam.bulk')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="file">Upload CSV/Excel <span class="text-danger">*</span></label>
                                                <input type="file" name="file" id="file" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="reset" class="btn btn-sm btn-secondary">Clear</button>
                                            <button type="submit" class="btn btn-sm brand-bg-color">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-striped dataTable" id="personnelListTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Personnel Name</th>
                            <th>Examination Number</th>
                            <th>Exam Status</th>
                            {{--                        <th>Action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($exam_numbers as $exam_number)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $exam_number->user->first_name }} {{ $exam_number->user->last_name }}</td>
                                <td>{{ $exam_number->exam_number }}</td>
                                <td>
                                    @if($users_has_results[$exam_number->user->id])
                                        @if($exam_number->user->exam_status == 'passed')
                                            <span class="badge badge-success">{{$exam_number->user->exam_status}}</span>
                                        @else
                                            <span class="badge badge-danger">{{$exam_number->user->exam_status}}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">Not Available</span>
                                    @endif
                                </td>
                                {{--                            <td>--}}
                                {{--                                @if($users_has_results[$exam_number->user->id])--}}
                                {{--                                    <a href="{{ route('results.edit', ['user_id' => $exam_number->user->id, 'exam_id' => $exam_id]) }}" class="btn btn-sm btn-primary"> <i class="fas fa-edit"></i> Edit</a>--}}
                                {{--                                @else--}}
                                {{--                                    <a href="{{ route('exams.upload.personnel.results', ['exam_id' => $exam_id, 'user_id' => $exam_number->user->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Enter Grades</a>--}}
                                {{--                                @endif--}}
                                {{--                                    <a href="#" class="btn btn-sm brand-bg-color"><i class="fas fa-eye"></i> View Results</a>--}}
                                {{--                            </td>--}}
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
        $('#personnelListTable').DataTable();
    });
</script>
@endsection
