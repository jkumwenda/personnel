@extends('layouts.app')
@section('title', 'Make Payment')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h6>Payment For <b>{{ $application->application_id }}</b> [{{ $category_id == 1 ? "New Application Fee" : ($category_id == 2 ? "Exam Resit Fee" : "License Retention Fee") }}]</h6>
            </div>
            <div class="card-body">
                <form action="">
                    <div class="form-group mb-2">
                        <label for="payment_method">Select Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">Select Payment Method</option>
                            <option value="1">National Bank</option>
                            <option value="3">Airtel Money</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_category">Amount to Pay <span class="brand-color">MWK {{ number_format($payment_fee->amount) }}</span></label>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Proceed" class="btn btn-sm brand-bg-color">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
