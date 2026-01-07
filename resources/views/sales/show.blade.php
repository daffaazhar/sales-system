@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Transaksi: {{ $sale->invoice_number }}</h3>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div>
            <h4 style="margin-bottom: 1rem; color: #555;">Informasi Transaksi</h4>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 0.5rem 0; color: #666;">Invoice</td>
                    <td style="padding: 0.5rem 0;"><strong>{{ $sale->invoice_number }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0; color: #666;">Tanggal</td>
                    <td style="padding: 0.5rem 0;">{{ $sale->sale_date->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0; color: #666;">Status</td>
                    <td style="padding: 0.5rem 0;">
                        @if($sale->status == 'completed')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($sale->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <h4 style="margin-bottom: 1rem; color: #555;">Informasi Pelanggan</h4>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 0.5rem 0; color: #666;">Nama</td>
                    <td style="padding: 0.5rem 0;"><strong>{{ $sale->customer->name }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0; color: #666;">Email</td>
                    <td style="padding: 0.5rem 0;">{{ $sale->customer->email ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem 0; color: #666;">Telepon</td>
                    <td style="padding: 0.5rem 0;">{{ $sale->customer->phone ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <h4 style="margin-bottom: 1rem; color: #555;">Daftar Barang</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Barang</th>
                <th>SKU</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->product->name }}</strong></td>
                    <td><code>{{ $item->product->sku }}</code></td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($sale->tax > 0)
            <tr>
                <td colspan="5" class="text-right">Pajak:</td>
                <td class="text-right">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($sale->discount > 0)
            <tr>
                <td colspan="5" class="text-right">Diskon:</td>
                <td class="text-right">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong style="font-size: 1.25rem;">Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
