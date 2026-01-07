<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'sale_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $lastSale = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastSale ? (int) str_replace('INV-', '', $lastSale->invoice_number) : 0;
        $newNumber = $lastNumber + 1;
        return 'INV-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal + $this->tax - $this->discount;
        $this->save();
    }
}