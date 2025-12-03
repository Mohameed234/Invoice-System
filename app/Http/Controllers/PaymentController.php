<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view payments');

        $payments = Payment::with(['invoice.customer', 'invoice.currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create payments');

        return view('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create payments');

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,paypal,other',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed'
        ]);

        // Handle nullable reference_number properly
        $referenceNumber = $request->input('reference_number');
        if (empty($referenceNumber)) {
            $referenceNumber = null;
        }

        $payment = Payment::create([
            'invoice_id' => $validated['invoice_id'],
            'user_id' => auth()->id(),
            'payment_number' => $this->generatePaymentNumber(),
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $referenceNumber,
            'status' => $validated['status'],
        ]);

        // Update invoice paid amount
        $invoice = Invoice::find($validated['invoice_id']);
        $invoice->increment('paid_amount', $validated['amount']);
        $invoice->update(['balance' => $invoice->total - $invoice->paid_amount]);

        // Update invoice status based on payment
        $invoice->updateStatusBasedOnPayments();

        return redirect()->route('payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $this->authorize('view payments');

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $this->authorize('edit payments');

        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorize('edit payments');

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,paypal,other',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed'
        ]);

        // Handle nullable reference_number properly
        $referenceNumber = $request->input('reference_number');
        if (empty($referenceNumber)) {
            $referenceNumber = null;
        }

        // Calculate the difference in amount
        $oldAmount = $payment->amount;
        $newAmount = $validated['amount'];
        $difference = $newAmount - $oldAmount;

        $payment->update([
            'invoice_id' => $validated['invoice_id'],
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $referenceNumber,
            'status' => $validated['status'],
        ]);

        // Update invoice paid amount if amount changed
        if ($difference != 0) {
            $invoice = Invoice::find($validated['invoice_id']);
            $invoice->increment('paid_amount', $difference);
            $invoice->update(['balance' => $invoice->total - $invoice->paid_amount]);
        }

        // Update invoice status based on payment
        $invoice->updateStatusBasedOnPayments();

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $this->authorize('delete payments');

        // Decrease invoice paid amount
        $invoice = $payment->invoice;
        $invoice->decrement('paid_amount', $payment->amount);
        $invoice->update(['balance' => $invoice->total - $invoice->paid_amount]);

        $payment->delete();

        // Update invoice status based on payment
        $invoice->updateStatusBasedOnPayments();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    private function generatePaymentNumber()
    {
        $prefix = 'PAY';
        $lastPayment = Payment::orderBy('id', 'desc')->first();
        $nextNumber = $lastPayment ? intval(substr($lastPayment->payment_number, 3)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
