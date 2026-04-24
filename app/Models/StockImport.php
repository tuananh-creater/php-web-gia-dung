<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'cost_price',
        'total_cost',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}