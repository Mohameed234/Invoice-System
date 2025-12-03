<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name', 'company_email', 'company_phone', 'company_address', 'company_website',
        'tax_number', 'registration_number', 'logo_path', 'favicon_path', 'invoice_terms',
        'invoice_footer', 'invoice_prefix', 'invoice_start_number', 'invoice_padding',
        'default_currency', 'default_language', 'show_tax_details', 'show_discount_details',
        'enable_recurring_invoices', 'enable_attachments'
    ];
}
