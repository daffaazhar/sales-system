@extends('layouts.app')

@section('title', 'Transaksi Baru')

@push('styles')
<style>
    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    .item-row .form-group {
        margin-bottom: 0;
    }
    .total-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1rem;
    }
    .total-section h3 {
        font-size: 2rem;
    }
    .stock-info {
        font-size: 0.75rem;
        color: #666;
    }
</style>
@endpush

@section('content')
<form action="{{ route('sales.store') }}" method="POST" id="saleForm">
    @csrf
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transaksi Penjualan Baru</h3>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="customer_id">Pilih Pelanggan *</label>
            <select class="form-control" id="customer_id" name="customer_id" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }} {{ $customer->phone ? '('.$customer->phone.')' : '' }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')<small style="color: red;">{{ $message }}</small>@enderror
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Barang</h3>
            <button type="button" class="btn btn-success" onclick="addItem()">+ Tambah Barang</button>
        </div>
        
        <div id="itemsContainer">
            <div class="item-row" data-index="0">
                <div class="form-group">
                    <label class="form-label">Barang *</label>
                    <select class="form-control product-select" name="items[0][product_id]" required onchange="updatePrice(this)">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                {{ $product->name }} (Stok: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga</label>
                    <input type="text" class="form-control price-display" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Qty *</label>
                    <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" value="1" required onchange="calculateSubtotal(this)">
                    <span class="stock-info"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control subtotal-display" readonly>
                </div>
                <div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">✕</button>
                </div>
            </div>
        </div>

        <div class="total-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Total Transaksi:</span>
                <h3 id="grandTotal">Rp 0</h3>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">💾 Simpan Transaksi</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

@push('scripts')
<script>
    let itemIndex = 0;
    const products = @json($products);

    function addItem() {
        itemIndex++;
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('div');
        row.className = 'item-row';
        row.dataset.index = itemIndex;
        
        let productOptions = '<option value="">-- Pilih Barang --</option>';
        products.forEach(p => {
            productOptions += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">${p.name} (Stok: ${p.stock})</option>`;
        });

        row.innerHTML = `
            <div class="form-group">
                <label class="form-label">Barang *</label>
                <select class="form-control product-select" name="items[${itemIndex}][product_id]" required onchange="updatePrice(this)">
                    ${productOptions}
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Harga</label>
                <input type="text" class="form-control price-display" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Qty *</label>
                <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" min="1" value="1" required onchange="calculateSubtotal(this)">
                <span class="stock-info"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal-display" readonly>
            </div>
            <div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">✕</button>
            </div>
        `;
        container.appendChild(row);
    }

    function removeItem(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('.item-row').remove();
            calculateGrandTotal();
        } else {
            alert('Minimal harus ada 1 item');
        }
    }

    function updatePrice(select) {
        const row = select.closest('.item-row');
        const option = select.options[select.selectedIndex];
        const price = option.dataset.price || 0;
        const stock = option.dataset.stock || 0;
        
        row.querySelector('.price-display').value = formatRupiah(price);
        row.querySelector('.price-display').dataset.price = price;
        row.querySelector('.quantity-input').max = stock;
        row.querySelector('.stock-info').textContent = stock > 0 ? `Maks: ${stock}` : '';
        
        calculateSubtotal(row.querySelector('.quantity-input'));
    }

    function calculateSubtotal(input) {
        const row = input.closest('.item-row');
        const price = parseFloat(row.querySelector('.price-display').dataset.price) || 0;
        const qty = parseInt(input.value) || 0;
        const subtotal = price * qty;
        
        row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
        row.querySelector('.subtotal-display').dataset.subtotal = subtotal;
        
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-display').forEach(el => {
            total += parseFloat(el.dataset.subtotal) || 0;
        });
        document.getElementById('grandTotal').textContent = formatRupiah(total);
    }

    function formatRupiah(num) {
        return 'Rp ' + parseInt(num).toLocaleString('id-ID');
    }
</script>
@endpush
@endsection
