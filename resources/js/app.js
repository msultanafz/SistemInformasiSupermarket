// resources/js/app.js

import "./bootstrap"; // Memastikan bootstrap.js atau dependensi utama lainnya dimuat

import Swal from "sweetalert2"; // Mengimpor SweetAlert2
window.Swal = Swal;

// Mengekspor fungsi confirmDelete ke global scope (window)
// Agar dapat dipanggil langsung dari atribut onclick di HTML
window.confirmDelete = function (button) {
    const productId = button.getAttribute("data-id"); // Mengambil ID dari atribut data-id pada tombol

    // Menampilkan popup konfirmasi SweetAlert2
    Swal.fire({
        title: "Anda yakin?",
        text: "Produk ini akan dihapus secara permanen!",
        icon: "warning", // Ikon peringatan
        showCancelButton: true, // Menampilkan tombol batal
        confirmButtonColor: "#d33", // Warna merah untuk tombol konfirmasi
        cancelButtonColor: "#3085d6", // Warna biru untuk tombol batal
        confirmButtonText: "Ya, Hapus!", // Teks tombol konfirmasi
        cancelButtonText: "Batal", // Teks tombol batal
    }).then((result) => {
        // Jika pengguna mengklik 'Ya, Hapus!'
        if (result.isConfirmed) {
            // Mengirimkan formulir hapus yang tersembunyi secara programatis
            document.getElementById("delete-form-" + productId).submit();
        }
    });
};
