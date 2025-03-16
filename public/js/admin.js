/*!
* Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
*/
//
// Scripts
//

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle') || document.getElementById('sidebarToggle');

    // Kiểm tra trạng thái sidebar từ localStorage khi trang được tải
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');
    const sidebarState = localStorage.getItem('sidebar-state');

    // Nếu có trạng thái đã lưu, áp dụng nó
    if (sidebarState === 'hidden') {
        sidebar.classList.add('hidden');
        content.classList.add('full');
    } else {
        // Mặc định là hiển thị
        sidebar.classList.add('sidebar-visible');
        content.classList.add('content-with-sidebar');
    }

    // Toggle sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (sidebar.classList.contains('sidebar-visible') || !sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('sidebar-visible');
                sidebar.classList.add('hidden');
                content.classList.remove('content-with-sidebar');
                content.classList.add('full');
                localStorage.setItem('sidebar-state', 'hidden');
            } else {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('sidebar-visible');
                content.classList.remove('full');
                content.classList.add('content-with-sidebar');
                localStorage.setItem('sidebar-state', 'visible');
            }
        });
    }

    // Initialize DataTables
    const dataTables = document.querySelectorAll('.datatable');
    if (dataTables.length > 0) {
        dataTables.forEach(table => {
            new simpleDatatables.DataTable(table);
        });
    }

    // Image preview for file inputs
    const imageInputs = document.querySelectorAll('.image-input');
    if (imageInputs.length > 0) {
        imageInputs.forEach(input => {
            input.addEventListener('change', function() {
                const preview = document.querySelector(this.dataset.preview);
                if (preview && this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            } else {
                alert.style.display = 'none';
            }
        }, 5000);
    });

    // Enable tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('.btn-delete');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc chắn muốn xóa mục này không?')) {
                    e.preventDefault();
                }
            });
        });
    }

    // Date range picker initialization
    const dateRangePickers = document.querySelectorAll('.date-range-picker');
    if (dateRangePickers.length > 0 && typeof jQuery !== 'undefined' && typeof daterangepicker !== 'undefined') {
        dateRangePickers.forEach(picker => {
            $(picker).daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
        });
    }

    // User dropdown
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function() {
            document.getElementById('userDropdownMenu').classList.toggle('show');
        });
    }

    // Đóng dropdown khi click bên ngoài
    window.addEventListener('click', function(e) {
        if (!document.getElementById('userDropdown')?.contains(e.target)) {
            const dropdownMenu = document.getElementById('userDropdownMenu');
            if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        }
    });

    // Submenu toggle
    const menuItems = document.querySelectorAll('.sidebar-menu .has-submenu');
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('open');
            const submenu = this.querySelector('.submenu');
            if (submenu) {
                submenu.classList.toggle('show');
            }
        });
    });

    // Charts initialization (if Chart.js is included)
    if (typeof Chart !== 'undefined') {
        // Example: Bookings Chart
        const bookingsCtx = document.getElementById('bookingsChart');
        if (bookingsCtx) {
            new Chart(bookingsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Đặt phòng',
                        data: [12, 19, 3, 5, 2, 3, 20, 33, 23, 12, 33, 10],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Thống kê đặt phòng theo tháng'
                        }
                    }
                }
            });
        }

        // Example: Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Doanh thu (triệu VNĐ)',
                        data: [50, 60, 45, 80, 70, 90, 120, 130, 95, 85, 110, 140],
                        backgroundColor: 'rgba(46, 204, 113, 0.7)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Doanh thu theo tháng'
                        }
                    }
                }
            });
        }
    }

    // DataTables initialization (if DataTables is included)
    if (typeof jQuery !== 'undefined' && typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                infoFiltered: "(lọc từ _MAX_ mục)",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                }
            }
        });
    }
});
