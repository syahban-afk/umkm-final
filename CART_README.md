# Fitur Keranjang Belanja UMKM-Breeze

## Deskripsi

Fitur keranjang belanja pada aplikasi UMKM-Breeze telah diperbarui untuk menyimpan data keranjang belanja secara permanen di database. Dengan perubahan ini, pengguna dapat melihat keranjang belanja mereka bahkan setelah keluar dari aplikasi dan masuk kembali.

## Struktur Database

### Tabel `carts`

| Kolom       | Tipe          | Deskripsi                                  |
| ----------- | ------------- | ------------------------------------------ |
| id          | bigint        | Primary key                                |
| customer_id | bigint        | Foreign key ke tabel customers             |
| product_id  | bigint        | Foreign key ke tabel products              |
| quantity    | integer       | Jumlah produk yang dibeli                  |
| price       | decimal(10,2) | Harga produk saat ditambahkan ke keranjang |
| created_at  | timestamp     | Waktu pembuatan record                     |
| updated_at  | timestamp     | Waktu pembaruan record                     |

## Model

### Cart.php

Model `Cart` memiliki relasi dengan model `Customer` dan `Product`. Model ini digunakan untuk menyimpan dan mengambil data keranjang belanja dari database.

## Controller

### CartController.php

Controller ini menangani operasi CRUD untuk keranjang belanja:

1. **index()** - Menampilkan keranjang belanja pengguna
2. **add()** - Menambahkan produk ke keranjang belanja
3. **update()** - Memperbarui jumlah produk di keranjang belanja
4. **remove()** - Menghapus produk dari keranjang belanja

## Cara Penggunaan

### Migrasi Database

Untuk menerapkan perubahan pada database, jalankan perintah migrasi:

```bash
php artisan migrate
```

### Seeding Data

Untuk mengisi data dummy pada tabel `carts`, jalankan perintah seeder:

```bash
php artisan db:seed --class=CartSeeder
```

### Penggunaan Fitur

1. **Melihat Keranjang Belanja**

    - Klik ikon keranjang di navbar atau kunjungi `/cart`

2. **Menambahkan Produk ke Keranjang**

    - Kunjungi halaman detail produk
    - Tentukan jumlah produk yang ingin dibeli
    - Klik tombol "Tambahkan ke Keranjang"

3. **Mengubah Jumlah Produk di Keranjang**

    - Pada halaman keranjang, gunakan tombol + dan - untuk mengubah jumlah
    - Klik tombol "Update" untuk menyimpan perubahan

4. **Menghapus Produk dari Keranjang**
    - Pada halaman keranjang, klik tombol "Hapus" pada produk yang ingin dihapus

## Catatan Pengembangan

-   Fitur ini memerlukan pengguna untuk memiliki data customer yang terkait dengan akun mereka
-   Jika pengguna belum memiliki data customer, sistem akan mencoba membuat data customer baru secara otomatis
-   Harga produk disimpan di keranjang untuk menghindari perubahan harga yang mempengaruhi keranjang belanja yang sudah ada
