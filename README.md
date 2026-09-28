# Mini Project - Product Manager
Tugas Praktikum Pemrograman Web - Pertemuan 3

## Cara Menjalankan Aplikasi
1. Import database `database/store_db.sql` ke MySQL / phpMyAdmin.
2. Pastikan file `config/db.php` telah terkonfigurasi sesuai dengan akun database lokal Anda (Host, Username, Password).
3. Salin seluruh folder proyek ke dalam direktori web server (misalnya: `htdocs/product-manager`).
4. Buka peramban (*browser*) dan akses: `http://localhost/product-manager/public/index.php`.

## Fitur dan Kontrol Keamanan
1. **Pola Post-Redirect-Get (PRG)**: Mencegah terjadinya pengiriman ulang form (*resubmit*) dan duplikasi data saat halaman di-refresh setelah operasi *Create* atau *Update*.
2. **Prepared Statement (PDO)**: Seluruh query database (INSERT, SELECT, UPDATE, DELETE) menggunakan *prepared statement* untuk mencegah celah *SQL Injection*.
3. **Escaping Output (htmlspecialchars)**: Menghindari serangan *Cross-Site Scripting (XSS)* dengan mengubah karakter khusus HTML menjadi karakter yang aman saat ditampilkan ke layar.
4. **Proteksi CSRF**: Operasi penghapusan (*Delete*) dilindungi dengan *Session-based CSRF Token* dan dijalankan secara eksklusif menggunakan method `POST`.
5. **Validasi Server-Side**: Memastikan nama produk minimal 3 karakter dan bernilai unik, harga > 0, serta stok ≥ 0.