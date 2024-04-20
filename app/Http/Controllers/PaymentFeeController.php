<?php

namespace App\Http\Controllers;

use App\Models\PaymentFee;
use Illuminate\Http\Request;

class PaymentFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_fees = PaymentFee::with('paymentCategory', 'personnelCategory')->get();
        return view('payments.fees', compact('payment_fees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentFee $paymentFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentFee $paymentFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentFee $paymentFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentFee $paymentFee)
    {
        //
    }
}
