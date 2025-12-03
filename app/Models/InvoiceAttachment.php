<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceAttachment extends Model
{
    protected $fillable = [
        'invoice_id', 'user_id', 'file_name', 'original_name', 'file_path', 'file_type',
        'file_size', 'description', 'type', 'is_public'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
