<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockImport;
use Illuminate\Http\Request;

class StockImportController extends Controller
{
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('admin.stock_imports.create', compact('product'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($productId);

        StockImport::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'total_cost' => $request->quantity * $request->cost_price,
        ]);

        $product->quantity += $request->quantity;
        $product->cost_price = $request->cost_price; 
        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Nhập hàng thành công');
    }
}