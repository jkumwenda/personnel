@extends('layouts.app')
@section('title', 'Edit Application | '.$application->application_id)
@section('content')
    <form action="{{ route('applications.update', $application->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h6 class="text-bold text-gray">General Details</h6>
        <hr style="width: 200px">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="personnel_category_id">Personnel Type <span class="text-danger">*</span></label>
                <select id="personnel_category_id" name="personnel_category_id" class="form-control">
                    <option value="1" {{ $application->personnel_category_id == "1" ? "selected" : "" }}>Pharmacist</option>
                    <option value="2" {{ $application->personnel_category_id == "2" ? "selected" : "" }}>Pharmacy Assistant</option>
                    <option value="3" {{ $application->personnel_category_id == "3" ? "selected" : "" }}>Pharmacy Technologist</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="training">Personnel Category <span class="text-danger">*</span></label>
                <select id="tra" name="training" class="form-control">
                    <!-- Add your categories here -->
                    <option value="local" {{ $application->category == "local" ? "selected" : "" }}>Locally trained</option>
                    <option value="foreign" {{ $application->category == "foreign" ? "selected" : "" }}>Foreign trained</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="pe">Present employer</label>
                <input type="text" name="present_employer" value="{{ $application->present_employer }}" class="form-control" id="pe">
            </div>
        </div>

        <div class="form-group col-md-4 mb-4">
            <label for="pa">Present employer address</label>
            <input type="text" name="present_employer_address" value="{{ $application->present_employer_address }}" class="form-control" id="pa">
        </div>

        <h6 class="text-bold text-gray">Qualifications</h6>
        <hr style="width: 200px">

        <div class="form-group">
            <label for="category">Academic Qualifications <span class="text-danger">*</span></label>

            @foreach($application->academic_qualification as $aq)
                <div class="row" id="academics">
                    <div class="form-group col-md-4">
                        <input type="text" name="acq[]" value="{{$aq['acq']}}" class="form-control" placeholder="Qualification">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="acd[]" value="{{$aq['acd']}}" class="form-control" placeholder="Month Year">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="aci[]" value="{{$aq['aci']}}" class="form-control" placeholder="Institution">
                    </div>
                </div>
            @endforeach
            <button type="button" class="btn btn-sm brand-bg-color" id="addRow">Add Row</button>
            {{--            <button type="button" class="btn btn-sm btn-danger" id="removeRow">Remove Row</button>--}}

        </div>

        <div class="form-group">
            <label for="category">Professional Qualifications <span class="text-danger">*</span></label>
            @foreach($application->professional_qualification as $pq)
            <div class="row" id="professional">
                <div class="form-group col-md-4">
                    <input type="text" name="prc[]" value="{{$pq['prc']}}" class="form-control" placeholder="Qualification">
                </div>
                <div class="form-group col-md-4">
                    <input type="text" name="prd[]" value="{{$pq['prd']}}" class="form-control" placeholder="Month Year">
                </div>
                <div class="form-group col-md-4">
                    <input type="text" name="pri[]" value="{{$pq['pri']}}" class="form-control" placeholder="Institution">
                </div>
            </div>
            @endforeach
            <button type="button" class="btn btn-sm brand-bg-color" id="addRowProf">Add Row</button>
            {{--            <button type="button" class="btn btn-sm btn-danger" id="removeRow">Remove Row</button>--}}

        </div>
        <div class="col-md-6 mb-2">
            <h6 class="text-bold text-gray">List of Documents</h6>
            <ol class="list-group">
                @foreach($application->relevant_files as $document)
                    <li class="list-group-item">{{$loop->iteration }} <a href="{{ Storage::url($document) }}" target="_blank" class="text-decoration-none">{{ str_replace('public/uploads/'.$application->user_id.'/relevant_documents/', '', $document) }}</a>
{{--                        <a href="" class="text-decoration-none"><i class="fa fa-trash text-danger"></i></a></li>--}}
                @endforeach
            </ol>
            <input type="file" name="files[]" class="form-control" id="relevant_files" multiple>
        </div>

        <input type="submit" value="Update" class="btn btn-sm brand-bg-color mb-4">
    </form>
@endsection
