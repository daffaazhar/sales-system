@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Pelanggan: {{ $customer->name }}</h3>
    </div>
    
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label" for="name">Nama Pelanggan *</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
            @error('name')<small style="color: red;">{{ $message }}</small>@enderror
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}">
                @error('email')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="phone">Telepon</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                @error('phone')<small style="color: red;">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="address">Alamat</label>
            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
