@extends('layouts.app')
@section('title', 'Subjects')
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
        <div class="modal fade" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="courseModalLongTitle">Add Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('courses.create') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                           <div class="form-group">
                                 <label for="name">Subject Name <span class="text-danger">*</span></label>
                                 <input type="text" name="name" id="name" class="form-control" required>
                           </div>
                            <div class="form-group">
                                <label for="code">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm brand-bg-color">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <h5>
                Subject List
                @if(Auth::user()->can('create subjects'))
                <a href="#" class="btn btn-sm brand-bg-color float-right" data-toggle="modal" data-target="#courseModal">Add Subject</a>
                @endif
            </h5>
            <table class="table table-bordered" id="subjectsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Subject Created</th>
                        <th>Subject Updated</th>
                        @if(Auth::user()->can('edit subjects') || Auth::user()->can('delete subjects'))
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ date('d F Y', strtotime($course->created_at)) }}</td>
                        <td>{{ date('d F Y', strtotime($course->update_at)) }}</td>
                        @if(Auth::user()->can('edit subjects') || Auth::user()->can('delete subjects'))
                        <td>
                            <div class="row">
                                <div class="col-md-6"><a href="{{ route('course.show', $course->id) }}" class="btn btn-sm brand-bg-color">Edit</a></div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6"></div>
                            </div>

                            <form style="display:inline" action="{{ route('course.destroy', $course->id) }}" method="POST"  id="deleteForm{{ $course->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('Delete Subject, {{$course->name}}!', {{ $course->id }})">
                                    Delete
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
            <script>
                $(document).ready(function() {
                    $('#subjectsTable').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "order": [[0, "asc"]],
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                    });
                });

            </script>
            <script>
                function confirmDelete(title, courseId) {
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
                            document.getElementById('deleteForm' + courseId).submit();
                        }
                    });
                }
            </script>
@endsection
