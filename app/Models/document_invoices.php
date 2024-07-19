<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document_invoices extends Model
{
    use HasFactory;
    protected $table = 'document_invoice';
    protected $fillable = [
        'Invoice_ID',
        'Quotation_ID',
    ];
}
