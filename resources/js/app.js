// resources/js/app.js

import "./bootstrap"; // Memastikan bootstrap.js atau dependensi utama lainnya dimuat

import Swal from "sweetalert2"; // Mengimpor SweetAlert2

// Mengekspor Swal ke global scope
window.Swal = Swal;

// Logika Notifikasi Global dari APP_FLASH_MESSAGES
document.addEventListener("DOMContentLoaded", function () {
    const flashMessages = window.APP_FLASH_MESSAGES;

    if (flashMessages.success) {
        Swal.fire({
            title: "Berhasil!",
            text: flashMessages.success,
            icon: "success",
            toast: true,
            position: "bottom-left", // Atur ke 'top-end' jika ingin di kanan atas
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    if (flashMessages.error) {
        Swal.fire({
            title: "Gagal!",
            text: flashMessages.error,
            icon: "error",
            toast: true,
            position: "bottom-left", // Atur ke 'top-end' jika ingin di kanan atas
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
        });
    }

    if (
        flashMessages.validationErrors &&
        flashMessages.validationErrors.length > 0
    ) {
        let errorsHtml = "<ul>";
        flashMessages.validationErrors.forEach((error) => {
            errorsHtml += `<li>${error}</li>`;
        });
        errorsHtml += "</ul>";

        Swal.fire({
            title: "Terjadi Kesalahan Validasi!",
            html: errorsHtml,
            icon: "error",
            confirmButtonText: "Tutup",
        });
    }
});

// Fungsi confirmDelete - HAPUS TANDA KOMENTAR (//) DARI SINI
window.confirmDelete = function (button) {
    const productId = button.getAttribute('data-id');
    Swal.fire({
        title: 'Anda yakin?',
        text: "Produk ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + productId).submit();
        }
    });
};