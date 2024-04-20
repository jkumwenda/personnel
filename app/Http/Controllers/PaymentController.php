<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use App\Models\PaymentCategory;
use App\Models\PaymentFee;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function makePayment($application_id, $category_id)
    {
        $application = Application::find($application_id);
        $payment_categories = PaymentCategory::all();
        // get payment fees where personnel_category_id = $application->personnel_category_id
        $payment_fee = PaymentFee::where('personnel_category_id', $application->personnel_category_id)->where('payment_category_id', $category_id)->first();
        return view('payments.make_payment', compact('payment_categories', 'application', 'payment_fee', 'category_id'));
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
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
