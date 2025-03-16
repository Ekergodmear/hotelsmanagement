<!-- Form thêm dịch vụ -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thêm dịch vụ</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.booking-services.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="form-group">
                <label for="service_id">Chọn dịch vụ <span class="text-danger">*</span></label>
                <select class="form-control @error('service_id') is-invalid @enderror"
                        id="service_id"
                        name="service_id"
                        required>
                    <option value="">Chọn dịch vụ</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}"
                                data-price="{{ $service->price }}"
                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} - {{ number_format($service->price) }} VNĐ
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                <input type="number"
                       class="form-control @error('quantity') is-invalid @enderror"
                       id="quantity"
                       name="quantity"
                       value="{{ old('quantity', 1) }}"
                       min="1"
                       required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="service_date">Ngày sử dụng <span class="text-danger">*</span></label>
                <input type="datetime-local"
                       class="form-control @error('service_date') is-invalid @enderror"
                       id="service_date"
                       name="service_date"
                       value="{{ old('service_date') }}"
                       required>
                @error('service_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="notes">Ghi chú</label>
                <textarea class="form-control @error('notes') is-invalid @enderror"
                          id="notes"
                          name="notes"
                          rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Tổng tiền</label>
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="total_price"
                           readonly>
                    <div class="input-group-append">
                        <span class="input-group-text">VNĐ</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm dịch vụ
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const quantityInput = document.getElementById('quantity');
    const totalPriceInput = document.getElementById('total_price');

    function updateTotalPrice() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        if (selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseInt(quantityInput.value) || 0;
            const total = price * quantity;
            totalPriceInput.value = new Intl.NumberFormat('vi-VN').format(total);
        } else {
            totalPriceInput.value = '';
        }
    }

    serviceSelect.addEventListener('change', updateTotalPrice);
    quantityInput.addEventListener('input', updateTotalPrice);

    // Set default service date to current datetime
    const serviceDateInput = document.getElementById('service_date');
    if (!serviceDateInput.value) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        serviceDateInput.value = now.toISOString().slice(0, 16);
    }

    // Calculate initial total price
    updateTotalPrice();
});
</script>
@endpush
