@extends('layouts.app')

@section('title', 'Master Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Master Pelanggan</h3>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah Pelanggan</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td><strong>{{ $customer->name }}</strong></td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td>{{ Str::limit($customer->address, 40) ?? '-' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data pelanggan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $customers->links() }}
</div>
@endsection
