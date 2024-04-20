@extends('layouts.app')
@section('title', 'Apply for licence')
@section('content')
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(!$application)
        <form action="{{ route('applications.store') }}" method="POST" id="applyForm" enctype="multipart/form-data">
            @csrf
            <h6 class="text-bold text-gray">General Details</h6>
            <hr style="width: 200px">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="training">Training Domain <span class="text-danger">*</span></label>
                    <select id="training" name="training" class="form-control" required>
                        <!-- Add your categories here -->
                        <option value="">Select Training Domain</option>
                        <option value="local">Locally trained</option>
                        <option value="foreign">Foreign trained</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="pe">Present employer</label>
                    <input type="text" name="present_employer" class="form-control" id="pe">
                </div>

                <div class="form-group col-md-4 mb-4">
                    <label for="pa">Present employer address</label>
                    <input type="text" name="present_employer_address" class="form-control" id="pa">
                </div>

            </div>

            <h6 class="text-bold text-gray">Qualifications</h6>
            <hr style="width: 200px">

            <div class="form-group">
                <label for="category">Academic Qualifications <span class="text-danger">*</span></label>
                <div class="row" id="academics">
                    <div class="form-group col-md-4">
                        <input type="text" name="acq[]" class="form-control" placeholder="Qualification" required>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="acd[]" class="form-control" placeholder="Month Year" required>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="aci[]" class="form-control" placeholder="Institution" required>
                    </div>
                </div>
                <button type="button" class="btn btn-sm brand-bg-color" id="addRow">Add New</button>
                {{--            <button type="button" class="btn btn-sm btn-danger" id="removeRow">Remove Row</button>--}}

            </div>

            <div class="form-group">
                <label for="category">Professional Qualifications </label>
                <div class="row" id="professional">
                    <div class="form-group col-md-4">
                        <input type="text" name="prc[]" class="form-control" placeholder="Qualification">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="prd[]" class="form-control" placeholder="Month Year">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="pri[]" class="form-control" placeholder="Institution">
                    </div>
                </div>
                <button type="button" class="btn btn-sm brand-bg-color" id="addRowProf">Add New</button>
                {{--            <button type="button" class="btn btn-sm btn-danger" id="removeRow">Remove Row</button>--}}

            </div>
            <div class="form-group">
                <label for="relevant_files">Upload Relevant Files</label>
                <input type="file" name="files[]" class="form-control" id="relevant_files" multiple required>
                @if(auth()->user()->personnel_category_id != 1)
                    <div class="ml-5 mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-danger"><b> For Pharmacy Technologist/Assistant</b></h6>
                                <p><small><i>1. A copy of Pharmacy Academic Certificate</i></small></p>
                                <p><small><i>2. Passport Photo</i></small></p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="ml-5 mt-2">
                        <div class="row">
                            <div class="col-md-6" id="locallyTrainedDocs"  style="display: none">
                                <h6 class="text-danger"><b>For locally trained pharmacist</b></h6>
                                <p><small><i>1. A copy of Pharmacy Academic Degree</i></small></p>
                                <p><small><i>2. Passport Photo</i></small></p>
                            </div>
                            <div class="col-md-6" id="foreignTrainedDocs" style="display: none">
                                <h6 class="text-danger"><b>For internationally trained pharmacist</b></h6>
                                <p><small><i>1. A copy of Pharmacy Academic Degree</i></small></p>
                                <p><small><i>2. Academic Transcript </i></small></p>
                                <p><small><i>3. Passport Photo</i></small></p>
                                <p><small><i>4. Proof of University accreditation from National Council for Higher Education (NCHE)</i></small></p>
                                <p><small><i>5. Letter of good standing from the licensing authority to those that were registered</i></small></p>
                                <p><small><i>6. Proof of Citizenship (i.e. copy of your passport for non-Malawians)</i></small></p>
                                <p><small><i>7. Proof of English language proficiency test results (TOEFL /IELTS)</i></small></p>
                                <p><small><i>8. Prospective employer in Malawi including Temporary Employment Permit issued by the Immigration Department (For Non-Malawian applicants only)
                                        </i></small></p>

                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="form-group mb-5">
                <input type="submit" class="btn btn-sm brand-bg-color" value="Submit">
            </div>
        </form>
    @else
        <div class="alert alert-success text-center">
            <p><b>Your application was already captured</b></p>
        </div>
    @endif

@endsection
@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var trainingSelect = document.getElementById('training');
        var locallyTrainedDocs = document.getElementById('locallyTrainedDocs');
        var foreignTrainedDocs = document.getElementById('foreignTrainedDocs');

        trainingSelect.addEventListener('change', function() {
            if (this.value === 'local') {
                locallyTrainedDocs.style.display = 'block';
                foreignTrainedDocs.style.display = 'none';
            } else if (this.value === 'foreign') {
                locallyTrainedDocs.style.display = 'none';
                foreignTrainedDocs.style.display = 'block';
            }
        });
    });
</script>
@endsection
