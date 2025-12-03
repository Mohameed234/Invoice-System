<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Payment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'total_invoices' => Invoice::count(),
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::sum('amount'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'overdue_invoices' => Invoice::where('due_date', '<', now())->where('status', '!=', 'paid')->count(),
        ];

        $recent_invoices = Invoice::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $recent_payments = Payment::with('invoice')
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact('stats', 'recent_invoices', 'recent_payments'));
    }
}
