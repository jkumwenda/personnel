@extends('layouts.app')
@section('title', ' Change Password')
@section('content')

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="">
        <div class="card">
            <div class="card-body py-5 px-md-5">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="current_password" class="col-md-4 col-form-label text-md-right">{{ __('Current Password') }}</label>

                        <div class="col-md-6">
                            <input id="current_password" type="password" class="form-control"  name="current_password" required>
                            @error('current_password')
                            <span class="text-danger" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                        <div class="col-md-6">
                            <input id="new_password" type="password" class="form-control" name="new_password" required>
                            @error('new_password')
                            <span class="text-danger" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirm New Password') }}</label>

                        <div class="col-md-6">
                            <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn brand-bg-color">
                                {{ __('Change Password') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
