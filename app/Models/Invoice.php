<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'customer_id', 'user_id', 'currency_id', 'invoice_date', 'due_date',
        'subtotal', 'tax_total', 'discount_total', 'total', 'paid_amount', 'balance', 'status',
        'notes', 'terms_conditions', 'payment_terms', 'is_recurring', 'recurring_interval', 'next_recurring_date'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'next_recurring_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    /**
     * Update invoice status based on payment amount
     */
    public function updateStatusBasedOnPayments()
    {
        if ($this->balance <= 0) {
            $this->update(['status' => 'paid']);
        } elseif ($this->paid_amount > 0) {
            $this->update(['status' => 'partially_paid']);
        } elseif ($this->due_date < now()) {
            $this->update(['status' => 'overdue']);
        } else {
            $this->update(['status' => 'sent']);
        }
    }

    /**
     * Check if invoice is fully paid
     */
    public function isFullyPaid()
    {
        return $this->balance <= 0;
    }

    /**
     * Check if invoice is partially paid
     */
    public function isPartiallyPaid()
    {
        return $this->paid_amount > 0 && $this->balance > 0;
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now() && $this->balance > 0;
    }

    /**
     * Get payment status text
     */
    public function getPaymentStatusText()
    {
        if ($this->isFullyPaid()) {
            return 'Paid';
        } elseif ($this->isPartiallyPaid()) {
            return 'Partial';
        } elseif ($this->isOverdue()) {
            return 'Overdue';
        } else {
            return 'Pending';
        }
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeClass()
    {
        if ($this->isFullyPaid()) {
            return 'badge bg-success';
        } elseif ($this->isPartiallyPaid()) {
            return 'badge bg-warning';
        } elseif ($this->isOverdue()) {
            return 'badge bg-danger';
        } else {
            return 'badge bg-secondary';
        }
    }
}
