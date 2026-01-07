@extends('layouts.app')

@section('title', 'Master Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Master Barang</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Barang</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td><code>{{ $product->sku }}</code></td>
                    <td>{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        @if($product->stock > 10)
                            <span class="badge badge-success">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="badge badge-warning">{{ $product->stock }}</span>
                        @else
                            <span class="badge badge-danger">Habis</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data barang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
