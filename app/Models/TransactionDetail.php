<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id', 'product_name', 'price', 'qty', 'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        // Dengan WithDefault agar bisa bertahan ketika produk sebenarnya telah dihapus.
        return $this->belongsTo(Product::class)->withDefault([
            'name' => $this->product_name,
            'price' => $this->price
        ]);
    }
}
