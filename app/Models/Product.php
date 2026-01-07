<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function hasStock($quantity)
    {
        return $this->stock >= $quantity;
    }

    public function decreaseStock($quantity)
    {
        if (!$this->hasStock($quantity)) {
            throw new \Exception('Stok tidak mencukupi untuk produk: ' . $this->name);
        }
        $this->decrement('stock', $quantity);
    }

    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
    }
}