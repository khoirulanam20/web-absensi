<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'sender_name',
        'sender_email',
        'sender_address',
        'recipient_name',
        'recipient_email',
        'recipient_address',
        'invoice_date',
        'subtotal',
        'tax',
        'total',
        'bank_name',
        'account_name',
        'account_number',
        'payment_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateOrderNumber(): string
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? ((int) $lastInvoice->order_number) + 1 : 1001;
        return str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
