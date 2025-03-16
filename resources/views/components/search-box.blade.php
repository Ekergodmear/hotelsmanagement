<div class="container">
    <div class="search-box">
        <form action="{{ route('hotels.search') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Địa điểm</label>
                        <input type="text" name="city" class="form-control" placeholder="Nhập điểm du lịch hoặc tên khách sạn">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Nhận phòng</label>
                        <input type="date" name="check_in" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Trả phòng</label>
                        <input type="date" name="check_out" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Số khách</label>
                        <select name="guests" class="form-control">
                            <option value="2">2 người lớn</option>
                            <option value="1">1 người lớn</option>
                            <option value="3">3 người lớn</option>
                            <option value="4">4 người lớn</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-search w-100">
                        <i class="fas fa-search me-2"></i>Tìm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
