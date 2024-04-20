@extends('layouts.app')
@section('title', 'Exams')
@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="modal fade" id="examModal" tabindex="-1" role="dialog" aria-labelledby="examModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="examModalLongTitle">Add Exam</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('exams.create') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exam_name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="exam_name" id="exam_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="is_open">Is Open <span class="text-danger">*</span></label>
                                    <select name="is_open" id="is_open" class="form-control" required>
                                        <option value="">Select Option</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-sm btn-secondary">Clear</button>
                                <button type="submit" class="btn btn-sm brand-bg-color">Add Exam</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h5>
                    Exam List
                    @if(Auth::user()->can('create exams'))
                        <a href="#" class="btn btn-sm brand-bg-color float-right" data-toggle="modal" data-target="#examModal">Add Exam</a>
                    @endif
                </h5>
                    <div class="table-responsive">
                        @if(count($exams) > 0)
                            <table class="table table-bordered" id="examsTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exam Name</th>
                                    <th>Status</th>
                                    <th>DG Approved</th>
                                    <th>Published</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    @if(Auth::user()->can('edit exams') || Auth::user()->can('delete exams') || Auth::user()->can('publish exams') || Auth::user()->can('finalize license approval'))
                                        <th>Actions</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($exams as $exam)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $exam->exam_name }}</td>
                                        <td><div class="badge {{ $exam->is_open ? "badge-success" : "badge-danger" }}">{{ $exam->is_open ? "Open" : "Closed" }}</div></td>
                                        <td><div class="badge {{ $exam->dg_approved ? "badge-success" : "badge-danger" }}">{{ $exam->dg_approved ? "Approved" : "Not Approved" }}</div></td>
                                        <td><div class="badge {{ $exam->published && $exam->bc_approved ? "badge-success" : "badge-danger" }}">{{ $exam->published && $exam->bc_approved ? "Published" : "Not Published" }}</div></td>
                                        <td>{{ date('d F Y', strtotime($exam->start_date)) }}</td>
                                        <td>{{ date('d F Y', strtotime($exam->end_date)) }}</td>
                                        @if(Auth::user()->can('edit exams') || Auth::user()->can('delete exams') || Auth::user()->can('publish exams') || Auth::user()->can('finalize license approval'))
                                            <td>
                                                @if(Auth::user()->can('edit exams'))
                                                    <a href="{{ route('exam.edit', $exam->id) }}" class="btn btn-sm brand-bg-color">Edit</a>
                                                @endif

                                                @if(Auth::user()->can('finalize license approval'))
                                                    @if(!$exam->dg_approved && !$exam->bc_approved)
                                                        <a href="{{ route('exam.finalize', $exam->id) }}" class="btn btn-sm btn-primary">Finalize</a>
                                                    @endif
                                                @endif

                                                @if(Auth::user()->can('publish exams'))
                                                    @if(!$exam->published && !$exam->is_open && $exam->dg_approved && !$exam->bc_approved)
                                                        <a href="{{ route('exam.publish', $exam->id) }}" class="btn btn-sm btn-primary">Approve Licences</a>
                                                    @endif
                                                @endif
                                                <a href="{{route('results.list', $exam->id)}}" class="btn btn-sm brand-bg-color">view results</a>
                                                {{--                                <form style="display:inline" action="{{ route('exam.destroy', $exam->id) }}" method="POST"  id="deleteForm{{ $exam->id }}">--}}
                                                {{--                                    @csrf--}}
                                                {{--                                    @method('DELETE')--}}
                                                {{--                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('Delete Exam, {{$exam->exam_name}}!', {{ $exam->id }})">--}}
                                                {{--                                        Delete--}}
                                                {{--                                    </button>--}}
                                                {{--                                </form>--}}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                <p>There are not any exams at the moment. Please create an exam.</p>
                            </div>
                        @endif
                    </div>
            </div>
        </div>
        @endsection
        @section('scripts')
            <script>
                $(document).ready(function() {
                    $('#examsTable').DataTable();
                });
            </script>
            <script>
                function confirmDelete(title, examId) {
                    Swal.fire({
                        title: title,
                        text: 'Are you sure you want to delete? This cannot be reversed!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If confirmed, submit the form
                            document.getElementById('deleteForm' + examId).submit();
                        }
                    });
                }
            </script>
@endsection

