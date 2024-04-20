@extends('layouts.app')
@section('title', ' Edit Profile For '.  $user->name)
@section('content')
    <div class="">
        <div class="card elevation-0">
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    <di class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-outline">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <div class="form-outline">
                                <label class="form-label" for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" value="{{ $user->phone_number }}" class="form-control">
                            </div>
                        </div>
                    </di>
                    <di class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-outline">
                                <label class="form-label" for="physical_address">Physical Address <span class="text-danger">*</span></label>
                                <input type="text" name="physical_address" value="{{ $user->physical_address }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <div class="form-outline">
                                <label class="form-label" for="postal_address">Postal Address <span class="text-danger">*</span></label>
                                <input type="text" name="postal_address" value="{{ $user->postal_address }}" class="form-control">
                            </div>
                        </div>
                    </di>

                    <button type="submit" class="btn brand-bg-color btn-sm mb-4">
                        {{__("Save Changes")}}
                    </button>

                </form>
            </div>
        </div>
    </div>

@endsection
