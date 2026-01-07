@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Barang Baru</h3>
    </div>
    
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Nama Barang *</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')<small style="color: red;">{{ $message }}</small>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="sku">SKU *</label>
            <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" required>
            @error('sku')<small style="color: red;">{{ $message }}</small>@enderror
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="price">Harga *</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', 0) }}" min="0" step="1" required>
                @error('price')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="stock">Stok *</label>
                <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                @error('stock')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="description">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
