@extends('layouts.app')
@section('title', 'Payment Fees')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Fee List</h3>
                <div class="card-tools">
                    <a href="{{ route('payments.fees.create') }}" class="btn btn-sm btn-success">Add New</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="payment-fee-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment Category</th>
                        <th>Personnel Category</th>
                        <th>Amount (MWK)</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payment_fees as $paymentFee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $paymentFee->paymentCategory->name }}</td>
                            <td>{{ $paymentFee->personnelCategory->name }}</td>
                            <td>{{ number_format($paymentFee->amount) }}</td>
                            <td>
                                <a href="{{ route('payments.fees.edit', $paymentFee->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('payments.fees.destroy', $paymentFee->id) }}" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
        <script>
            $(document).ready(function () {
                $('#payment-fee-table').DataTable();
            });
        </script>
@endsection
