<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
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
        $this->authorize('view invoices');

        $invoices = Invoice::with(['customer', 'currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create invoices');

        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->with('category')->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->orderBy('name')->get();

        return view('invoices.create', compact('customers', 'products', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create invoices');

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'required|exists:currencies,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:draft,sent,partially_paid,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'currency_id' => $validated['currency_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        $this->createInvoiceItems($invoice, $validated['items']);
        $this->calculateInvoiceTotals($invoice);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view invoices');

        $invoice->load(['customer', 'currency', 'items.product', 'payments', 'attachments']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Export invoice as PDF.
     */
    public function pdf(Invoice $invoice)
    {
        $this->authorize('view invoices');

        $invoice->load(['customer', 'currency', 'items.product', 'payments']);

        $pdf = \PDF::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $this->authorize('edit invoices');

        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be edited.');
        }

        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->with('category')->orderBy('name')->get();
        $currencies = Currency::where('is_active', true)->orderBy('name')->get();

        $invoice->load('items');

        return view('invoices.edit', compact('invoice', 'customers', 'products', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('edit invoices');

        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be edited.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'required|exists:currencies,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:draft,sent,partially_paid,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'currency_id' => $validated['currency_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        // Delete existing items and recreate
        $invoice->items()->delete();
        $this->createInvoiceItems($invoice, $validated['items']);
        $this->calculateInvoiceTotals($invoice);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete invoices');

        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be deleted.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    private function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 3)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    private function createInvoiceItems($invoice, $items)
    {
        foreach ($items as $index => $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $item['quantity'] * $item['unit_price'];
            $taxAmount = ($item['tax_rate'] / 100) * $subtotal;
            $total = $subtotal + $taxAmount;

            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'item_name' => $product->name,
                'description' => $product->description,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rate' => $item['tax_rate'],
                'discount_rate' => 0,
                'discount_amount' => 0,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function calculateInvoiceTotals($invoice)
    {
        $subtotal = $invoice->items->sum('subtotal');
        $taxTotal = $invoice->items->sum('tax_amount');
        $discountTotal = $invoice->items->sum('discount_amount');
        $total = $invoice->items->sum('total');

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'discount_total' => $discountTotal,
            'total' => $total,
            'balance' => $total - $invoice->paid_amount,
        ]);
    }
}
