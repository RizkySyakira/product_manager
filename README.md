# Product Manager

Aplikasi sederhana untuk mengelola data produk menggunakan PHP dan MySQL.

Project ini dibuat untuk menerapkan konsep CRUD (Create, Read, Update, Delete), validasi input, prepared statement, CSRF protection, dan output escaping pada aplikasi web.

## Fitur

- Menampilkan daftar produk
- Menambahkan produk baru
- Mengedit data produk
- Menghapus produk
- Menghitung total nilai stok
- Validasi nama produk minimal 3 karakter
- Validasi harga harus lebih dari 0
- Validasi stok tidak boleh negatif
- Mencegah nama produk duplikat
- Menggunakan prepared statement
- Menggunakan CSRF token pada request POST
- Menggunakan `htmlspecialchars()` untuk mengamankan output
- Tampilan responsive pada layar sempit
- Redirect setelah berhasil menambahkan produk untuk mencegah duplikasi saat refresh

## Teknologi

- PHP
- MySQL / MariaDB
- HTML
- CSS
- PDO
- XAMPP
- Visual Studio Code

## Struktur Project

```text
product_manager/
│
├── config/
│   └── database.php
│
├── add.php
├── database.sql
├── delete.php
├── edit.php
├── index.php
└── README.md