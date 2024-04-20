@extends('layouts.app')
@section('title', 'Licence Configurations')
@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card elevation-0 ">
                <div class="card-body">
                    <form action="{{ route('configs.licence.configure') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="inspector_number">Inspector Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('inspector_number') is-invalid @enderror" id="inspector_number" name="inspector_number"  value="{{ $licenceConfigs->INS  }}">
                            @error('inspector_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="dg_name">Director General <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('dg_name') is-invalid @enderror" id="dg_name" name="dg_name" value="{{ $licenceConfigs->DG_NAME }}">
                            @error('dg_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gd_signature">Director General Signature <span class="text-danger">*</span></label>
                            <br>
                            <img src="{{asset('images/sigs/'.$licenceConfigs->DG_SIGNATURE)}}" style="width: 200px" alt="dg_sig">
                            <input type="file" class="form-control @error('gd_signature') is-invalid @enderror" id="gd_signature" name="gd_signature" value="{{ $licenceConfigs->DG_SIGNATURE }}">
                            @error('gd_signature')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="bc_name">Board Chair <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('bc_name') is-invalid @enderror" id="bc_name" name="bc_name" value="{{ $licenceConfigs->BC_NAME }}">
                            @error('bc_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gd_signature">Board Chair Signature <span class="text-danger">*</span></label>
                            <br>
                            <img src="{{asset('images/sigs/'.$licenceConfigs->BC_SIGNATURE)}}" style="width: 200px" alt="dg_sig">
                            <input type="file" class="form-control @error('bc_signature') is-invalid @enderror" id="bc_signature" name="bc_signature" value="{{ $licenceConfigs->BC_SIGNATURE }}">
                            @error('bc_signature')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-sm brand-bg-color">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
