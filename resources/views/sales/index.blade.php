@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Transaksi Penjualan</h3>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">+ Transaksi Baru</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th>Total Item</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td><strong>{{ $sale->invoice_number }}</strong></td>
                    <td>{{ $sale->customer->name }}</td>
                    <td>{{ $sale->items->count() }} item</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>
                        @if($sale->status == 'completed')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($sale->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-primary">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $sales->links() }}
</div>
@endsection
