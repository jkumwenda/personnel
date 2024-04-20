@extends('layouts.app')
@section('title', 'Payment Categories')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Category List</h3>
                <div class="card-tools">
                    <a href="{{ route('payments.categories.create') }}" class="btn btn-sm btn-success">Add New</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="payment-category-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payment_categories as $paymentCategory)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $paymentCategory->name }}</td>
                            <td>
                                <a href="{{ route('payments.categories.edit', $paymentCategory->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('payments.categories.destroy', $paymentCategory->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
                $('#payment-category-table').DataTable();
            });
        </script>
@endsection
