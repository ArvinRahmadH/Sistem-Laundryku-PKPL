// Fungsi untuk menampilkan modal login
function showLoginModal() {
    $('#loginForm')[0].reset();
    $('#loginError').addClass('d-none');
    $('#loginModal').modal('show');
}

// Fungsi untuk menampilkan modal register
function showRegisterModal() {
    $('#registerForm')[0].reset();
    $('#registerError').addClass('d-none');
    $('#registerModal').modal('show');
}

// Login form handler
$('#loginForm').on('submit', function(e) {
    e.preventDefault();
    
    const email = $('#loginEmail').val();
    const password = $('#loginPassword').val();
    
    $.ajax({
        url: API_BASE_URL + '/login',
        method: 'POST',
        data: { email, password },
        success: function(response) {
            localStorage.setItem('token', response.access_token);
            authToken = response.access_token;
            
            $('#loginModal').modal('hide');
            checkAuth();
        },
        error: function(xhr) {
            $('#loginError').text(xhr.responseJSON?.message || 'Login gagal').removeClass('d-none');
        }
    });
});

// Register form handler
$('#registerForm').on('submit', function(e) {
    e.preventDefault();
    
    const name = $('#registerName').val();
    const email = $('#registerEmail').val();
    const password = $('#registerPassword').val();
    
    $.ajax({
        url: API_BASE_URL + '/register',
        method: 'POST',
        data: { name, email, password },
        success: function(response) {
            alert('Register berhasil! Silakan login.');
            $('#registerModal').modal('hide');
            showLoginModal();
        },
        error: function(xhr) {
            $('#registerError').text(xhr.responseJSON?.message || 'Register gagal').removeClass('d-none');
        }
    });
});

// Logout function
function logout() {
    if (!authToken) return;
    
    $.ajax({
        url: API_BASE_URL + '/logout',
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + authToken },
        success: function() {
            localStorage.removeItem('token');
            authToken = null;
            checkAuth();
        }
    });
}

// Cek status autentikasi
function checkAuth() {
    if (!authToken) {
        $('#authSection').removeClass('d-none');
        $('#mainSection').addClass('d-none');
        $('#userInfo').addClass('d-none');
        return;
    }
    
    // Dapatkan info user
    $.ajax({
        url: API_BASE_URL + '/me',
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + authToken },
        success: function(user) {
            $('#userName').text('Halo, ' + user.name);
            $('#userInfo').removeClass('d-none');
            $('#authSection').addClass('d-none');
            $('#mainSection').removeClass('d-none');
            
            // Load orders setelah login
            loadOrders();
        },
        error: function() {
            // Token invalid, clear
            localStorage.removeItem('token');
            authToken = null;
            checkAuth();
        }
    });
}

// Event handler untuk logout
$(document).on('click', '#logoutBtn', logout);

// Cek auth saat halaman dimuat
$(document).ready(function() {
    checkAuth();
});