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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">All Subjects</a>
                        <a class="nav-item nav-link" id="nav-assign-tab" data-toggle="tab" href="#nav-assign" role="tab" aria-controls="nav-assign" aria-selected="true">Assigned Subjects</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
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
                        <div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="courseModalLongTitle">Assign To</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('course.attach') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" id="course_id">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                @foreach($personnelCategories as $category)
                                                    <div class="form-check">
                                                        <input class="form-check form-check-input" type="checkbox" name="personnel_category_id[]" value="{{ $category->id }}">
                                                        <label class="form-check form-check-label">{{ $category->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-sm brand-bg-color">Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                       <div class="card elevation-0 border-0">
                            <div class="card-header">
                                <h5>
                                    @if(Auth::user()->can('create subjects'))
                                        <a href="#" class="btn btn-sm brand-bg-color float-right" data-toggle="modal" data-target="#courseModal">Add Subject</a>
                                    @endif
                                </h5>
                            </div>
                           <div class="card-body">
                               @if(count($courses) > 0)
                                    <table class="table table-hover table-striped table-bordered datatable" id="subjectsTable">
                                   <thead>
                                   <tr>
                                       <th>#</th>
                                       <th>Subject Name</th>
                                       <th>Code</th>
                                       <th>Created</th>
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
                                           @if(Auth::user()->can('edit subjects') || Auth::user()->can('delete subjects') || Auth::user()->can('assign subjects'))
                                               <td>
                                                   <div class="row">
                                                       <div class="col-sm-4">
                                                           <a href="{{ route('course.show', $course->id) }}" class="btn btn-sm brand-bg-color"><i class="fa fa-eye"></i> View</a>
                                                       </div>
                                                       <div class="col-sm-4">
                                                           <form style="display:inline" action="{{ route('course.destroy', $course->id) }}" method="POST"  id="deleteForm{{ $course->id }}">
                                                               @csrf
                                                               @method('DELETE')
                                                               <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('Delete Subject, {{$course->name}}!', {{ $course->id }})">
                                                                   Delete
                                                               </button>
                                                           </form>
                                                       </div>
                                                       @if(Auth::user()->can('assign subjects'))
                                                           <div class="col-sm-4">
                                                               <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-course-id="{{$course->id}}" data-target="#assignModal">Assign</a>
                                                           </div>
                                                        @endif
                                                   </div>
                                               </td>
                                           @endif
                                       </tr>
                                   @endforeach
                                   </tbody>
                               </table>
                               @else
                                   <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                       <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                       <p>No subjects available. Please add.</p>
                                   </div>
                               @endif
                           </div>
                       </div>
                    </div>
                    <div class="tab-pane fade" id="nav-assign" role="tabpanel" aria-labelledby="nav-assign-tab">
                        <div class="card elevation-0 border-0">
                            <div class="card-body">
                                @foreach($personnelCategories as $category)
                                    <h5 class="text-bold">{{ $category->name }}</h5>
                                    @if(count($category->courses) > 0)
                                        <table class="table table-bordered table-striped table-hover" id="assnTable{{ $loop->index }}">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject Name</th>
                                                <th>Subject Code</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($category->courses as $subject)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>{{ $subject->code }}</td>
                                                    <td>
                                                        <a href="{{ route('course.detach', [$subject->id, $category->id]) }}" class="btn btn-sm btn-danger">Remove</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
                                            <img src="{{asset('dist/img/searchlist.png')}}" alt="No venues">
                                            <p>No subject has been assigned to {{ $category->name }} category so far</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

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
        $(document).ready(function() {
            @foreach($personnelCategories as $category)
            $('#assnTable{{ $loop->index }}').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "order": [[0, "asc"]],
                "info": false,
                "autoWidth": false,
                "responsive": true,
            });
            @endforeach
        });
    </script>
    <script>
        $('a[data-target="#assignModal"]').on('click', function() {
            const courseId = $(this).data('course-id');
            console.log(courseId);
            $('#course_id').val(courseId);
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
