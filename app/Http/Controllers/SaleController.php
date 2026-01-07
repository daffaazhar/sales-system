<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Check stock availability for all items
        $stockErrors = [];
        foreach ($request->items as $index => $item) {
            $product = Product::find($item['product_id']);
            if (!$product->hasStock($item['quantity'])) {
                $stockErrors[] = "Stok {$product->name} tidak mencukupi. Tersedia: {$product->stock}, diminta: {$item['quantity']}";
            }
        }

        if (!empty($stockErrors)) {
            return back()
                ->withInput()
                ->with('error', implode('<br>', $stockErrors));
        }

        DB::beginTransaction();
        try {
            // Create sale
            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'customer_id' => $request->customer_id,
                'subtotal' => 0,
                'tax' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => 'completed',
                'sale_date' => now(),
            ]);

            $subtotal = 0;

            // Create sale items and reduce stock
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $itemSubtotal = $product->price * $item['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ]);

                // Reduce stock
                $product->decreaseStock($item['quantity']);

                $subtotal += $itemSubtotal;
            }

            // Update sale totals
            $sale->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Transaksi berhasil disimpan. Invoice: ' . $sale->invoice_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return view('sales.show', compact('sale'));
    }
}
