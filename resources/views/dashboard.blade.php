@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem;">Selamat Datang, {{ Auth::user()->name }}!</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 10px;">
            <h3 style="font-size: 2rem;">{{ \App\Models\Product::count() }}</h3>
            <p>Total Barang</p>
            <a href="{{ route('products.index') }}" style="color: rgba(255,255,255,0.8); font-size: 0.875rem;">Lihat semua →</a>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem; border-radius: 10px;">
            <h3 style="font-size: 2rem;">{{ \App\Models\Customer::count() }}</h3>
            <p>Total Pelanggan</p>
            <a href="{{ route('customers.index') }}" style="color: rgba(255,255,255,0.8); font-size: 0.875rem;">Lihat semua →</a>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem; border-radius: 10px;">
            <h3 style="font-size: 2rem;">{{ \App\Models\Sale::count() }}</h3>
            <p>Total Transaksi</p>
            <a href="{{ route('sales.index') }}" style="color: rgba(255,255,255,0.8); font-size: 0.875rem;">Lihat semua →</a>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 1.5rem; border-radius: 10px;">
            <h3 style="font-size: 2rem;">Rp {{ number_format(\App\Models\Sale::sum('total'), 0, ',', '.') }}</h3>
            <p>Total Penjualan</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Transaksi Terbaru</h3>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">+ Transaksi Baru</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse(\App\Models\Sale::with('customer')->latest()->take(5)->get() as $sale)
                <tr>
                    <td><strong>{{ $sale->invoice_number }}</strong></td>
                    <td>{{ $sale->customer->name }}</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-primary">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
