let currentPage = 1;
let searchQuery = '';
let orderBy = 'created_at';
let sortBy = 'desc';

// Fungsi untuk menampilkan modal order (create/edit)
function showOrderModal(orderId = null) {
    $('#orderForm')[0].reset();
    $('#fileInfo').text('');
    $('#orderError').addClass('d-none');
    
    if (orderId) {
        $('#orderModalTitle').text('Edit Order');
        $('#orderSubmitBtn').text('Update');
        $('#orderId').val(orderId);
        
        // Load order data
        $.ajax({
            url: API_BASE_URL + '/laundry/' + orderId,
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + authToken },
            success: function(order) {
                $('#customerName').val(order.customer_name);
                $('#phone').val(order.phone);
                $('#address').val(order.address);
                $('#serviceType').val(order.service_type);
                $('#weight').val(order.weight);
                $('#price').val(order.price);
                $('#status').val(order.status);
                $('#notes').val(order.notes);
                
                if (order.file_path) {
                    $('#fileInfo').text('File tersedia: ' + order.file_path.split('/').pop());
                }
            }
        });
    } else {
        $('#orderModalTitle').text('Tambah Order Baru');
        $('#orderSubmitBtn').text('Simpan');
        $('#orderId').val('');
    }
    
    $('#orderModal').modal('show');
}

// Format harga ke Rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Format tanggal
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Get status badge class
function getStatusBadge(status) {
    const statusMap = {
        'pending': { class: 'status-pending', text: 'Pending' },
        'in_progress': { class: 'status-in_progress', text: 'Dalam Proses' },
        'done': { class: 'status-done', text: 'Selesai' },
        'delivered': { class: 'status-delivered', text: 'Terkirim' }
    };
    return statusMap[status] || { class: 'status-pending', text: status };
}

// Get service type name
function getServiceName(type) {
    const serviceMap = {
        'kiloan': 'Kiloan',
        'dry_cleaning': 'Dry Cleaning',
        'antar_jemput': 'Antar Jemput',
        'setrika_premium': 'Setrika Premium'
    };
    return serviceMap[type] || type;
}

// Load orders dengan pagination
function loadOrders(page = 1) {
    if (!authToken) return;
    
    currentPage = page;
    let url = `${API_BASE_URL}/laundry?page=${page}`;
    
    if (searchQuery) url += `&search=${encodeURIComponent(searchQuery)}`;
    if (orderBy) url += `&orderBy=${orderBy}`;
    if (sortBy) url += `&sortBy=${sortBy}`;
    
    $.ajax({
        url: url,
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + authToken },
        success: function(response) {
            renderOrders(response.data);
            renderPagination(response);
        }
    });
}

// Render orders ke tabel
function renderOrders(orders) {
    const tbody = $('#ordersTableBody');
    tbody.empty();
    
    if (orders.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-2">Tidak ada data order</p>
                </td>
            </tr>
        `);
        return;
    }
    
    orders.forEach(order => {
        const status = getStatusBadge(order.status);
        
        const row = `
            <tr>
                <td>${order.id}</td>
                <td>
                    <strong>${order.customer_name}</strong>
                    ${order.phone ? `<br><small class="text-muted">${order.phone}</small>` : ''}
                </td>
                <td>${getServiceName(order.service_type)}</td>
                <td>${order.weight || 0} kg</td>
                <td>${formatRupiah(order.price || 0)}</td>
                <td>
                    <span class="status-badge ${status.class}">
                        ${status.text}
                    </span>
                </td>
                <td>${formatDate(order.created_at)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="showOrderModal(${order.id})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteOrder(${order.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Render pagination
function renderPagination(data) {
    const pagination = $('#paginationSection');
    pagination.empty();
    
    if (data.last_page <= 1) return;
    
    let paginationHtml = `
        <ul class="pagination justify-content-center">
    `;
    
    // Previous button
    if (data.current_page > 1) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="loadOrders(${data.current_page - 1})">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        `;
    }
    
    // Page numbers
    for (let i = 1; i <= data.last_page; i++) {
        if (i === data.current_page) {
            paginationHtml += `
                <li class="page-item active">
                    <span class="page-link">${i}</span>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" onclick="loadOrders(${i})">${i}</a>
                </li>
            `;
        }
    }
    
    // Next button
    if (data.current_page < data.last_page) {
        paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="loadOrders(${data.current_page + 1})">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        `;
    }
    
    paginationHtml += '</ul>';
    pagination.html(paginationHtml);
}

// Search orders
function searchOrders() {
    searchQuery = $('#searchInput').val();
    loadOrders(1);
}

// Handle order form submission
$('#orderForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const orderId = $('#orderId').val();
    
    formData.append('customer_name', $('#customerName').val());
    formData.append('phone', $('#phone').val());
    formData.append('address', $('#address').val());
    formData.append('service_type', $('#serviceType').val());
    formData.append('weight', $('#weight').val() || 0);
    formData.append('price', $('#price').val() || 0);
    formData.append('status', $('#status').val());
    formData.append('notes', $('#notes').val());
    
    const fileInput = $('#file')[0];
    if (fileInput.files[0]) {
        formData.append('file', fileInput.files[0]);
    }
    
    const url = orderId ? 
        `${API_BASE_URL}/laundry/${orderId}` : 
        `${API_BASE_URL}/laundry`;
    const method = orderId ? 'PATCH' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'Authorization': 'Bearer ' + authToken },
        success: function() {
            $('#orderModal').modal('hide');
            loadOrders(currentPage);
            alert(orderId ? 'Order berhasil diperbarui!' : 'Order berhasil ditambahkan!');
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            let errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
            
            if (Object.keys(errors).length > 0) {
                errorMsg = Object.values(errors).join('<br>');
            }
            
            $('#orderError').html(errorMsg).removeClass('d-none');
        }
    });
});

// Delete order
function deleteOrder(orderId) {
    if (!confirm('Apakah Anda yakin ingin menghapus order ini?')) return;
    
    $.ajax({
        url: API_BASE_URL + '/laundry/' + orderId,
        method: 'DELETE',
        headers: { 'Authorization': 'Bearer ' + authToken },
        success: function() {
            alert('Order berhasil dihapus!');
            loadOrders(currentPage);
        },
        error: function() {
            alert('Gagal menghapus order. Silakan coba lagi.');
        }
    });
}

// Search on enter
$('#searchInput').on('keypress', function(e) {
    if (e.which === 13) {
        searchOrders();
    }
});