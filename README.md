# Order Service — UTS Microservices

## 1. Deskripsi Proyek

Order Service merupakan layanan berbasis Laravel yang bertanggung jawab untuk mengelola pesanan pengguna dalam arsitektur microservices.

Order Service berkomunikasi dengan:
- **Product Service** untuk mengambil informasi produk, harga, dan stok.
- **Cart Service** untuk mengambil dan mengosongkan keranjang pengguna.
- **Order Database** untuk menyimpan data pesanan dan detail item pesanan.

Order Service memiliki database sendiri sehingga data pesanan tidak disimpan di database layanan lain.

## 2. Arsitektur Sistem

Sistem terdiri dari beberapa layanan:

| Komponen | Teknologi | Port |
|---|---|---:|
| Product Service | Node.js, Express | 3000 |
| Product Database | MySQL 8 | Internal |
| Cart Service | Laravel | 3001 |
| Cart Database | PostgreSQL 16 | Internal |
| Order Service | Laravel 13, PHP 8.4 | 3003 |
| Order Database | MySQL 8 | Internal |

### Alur Checkout

1. Client mengirim permintaan checkout ke Order Service.
2. Order Service mengambil data keranjang dari Cart Service.
3. Order Service mengambil informasi setiap produk dari Product Service.
4. Order Service memeriksa keberadaan produk dan kecukupan stok.
5. Order Service menghitung total pesanan.
6. Order Service menyimpan pesanan dan detail item ke Order Database.
7. Order Service meminta Cart Service mengosongkan keranjang.
8. Order Service mengembalikan informasi pesanan kepada client.

## 3. Cara Menjalankan Sistem

### Persyaratan

- Docker Desktop
- Docker Compose
- Git

### Langkah menjalankan

Jalankan terminal pada direktori utama proyek yang berisi `docker-compose.yml`.

**1. Menyiapkan APP_KEY**

Pada PowerShell, jalankan:

```powershell
$line = Select-String -Path .\order-service\.env -Pattern '^APP_KEY='
$env:ORDER_APP_KEY = ($line.Line -replace '^APP_KEY=', '').Trim()
```

Pastikan file `.env` Order Service sudah memiliki APP_KEY yang valid.

**2. Membangun dan menjalankan seluruh layanan**

```powershell
docker compose up -d --build
```

**3. Memeriksa container**

```powershell
docker compose ps
```

Pastikan container layanan dan database berjalan dengan status yang sesuai.

**4. Memeriksa Order Service**

```powershell
curl.exe -i http://localhost:3003/health
```

Respons yang diharapkan:

```json
{
  "status": "ok",
  "service": "order-service"
}
```

## 4. Daftar Endpoint

Base URL:

```text
http://localhost:3003
```

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/health` | Memeriksa kondisi layanan |
| POST | `/orders` | Membuat pesanan melalui checkout |
| GET | `/orders/{id}` | Melihat detail pesanan |
| GET | `/orders/user/{userId}` | Melihat daftar pesanan pengguna |
| PATCH | `/orders/{id}/pay` | Mengubah status pesanan menjadi PAID |

### Contoh Request

**A. Membuat pesanan**

```http
POST /orders
Content-Type: application/json
Accept: application/json
```

Body:

```json
{
  "user_id": 7
}
```

**B. Melihat detail pesanan**

```http
GET /orders/1
Accept: application/json
```

**C. Melihat pesanan berdasarkan pengguna**

```http
GET /orders/user/7
Accept: application/json
```

**D. Memfilter pesanan berdasarkan status**

```http
GET /orders/user/7?status=PENDING
Accept: application/json
```

**E. Simulasi pembayaran**

```http
PATCH /orders/1/pay
Accept: application/json
```

Catatan: Endpoint pembayaran akan didokumentasikan setelah implementasi dan pengujiannya selesai.

## 5. Environment Variables

Environment variable digunakan untuk mengatur konfigurasi layanan dan koneksi database.

### Order Service

| Variabel | Fungsi |
|---|---|
| APP_ENV | Menentukan lingkungan aplikasi |
| APP_DEBUG | Mengatur mode debug |
| APP_URL | Alamat aplikasi |
| APP_KEY | Kunci enkripsi Laravel |
| DB_CONNECTION | Jenis database |
| DB_HOST | Host database |
| DB_PORT | Port database |
| DB_DATABASE | Nama database |
| DB_USERNAME | Username database |
| DB_PASSWORD | Password database |
| SESSION_DRIVER | Media penyimpanan session |
| PRODUCT_SERVICE_URL | Alamat Product Service |
| CART_SERVICE_URL | Alamat Cart Service |

### Product Service

| Variabel | Fungsi |
|---|---|
| DB_HOST | Host Product Database |
| DB_USER | Username database |
| DB_PASSWORD | Password database |
| DB_NAME | Nama database |

### Cart Service

| Variabel | Fungsi |
|---|---|
| DB_CONNECTION | Jenis database |
| DB_HOST | Host Cart Database |
| DB_PORT | Port database |
| DB_DATABASE | Nama database |
| DB_USERNAME | Username database |
| DB_PASSWORD | Password database |
| LOG_CHANNEL | Saluran pencatatan log |

Nilai rahasia seperti APP_KEY dan password database tidak dicantumkan dalam dokumentasi.

## 6. Keputusan Desain dan Keterbatasan

### Keputusan Desain

1. Setiap service memiliki tanggung jawab masing-masing.
2. Order Service menggunakan database tersendiri.
3. Komunikasi antarservice dilakukan melalui HTTP API.
4. Informasi nama dan harga produk disimpan sebagai snapshot pada detail pesanan.
5. Penyimpanan pesanan dan detail item menggunakan database transaction.
6. Keranjang dikosongkan setelah pesanan berhasil disimpan.

### Keterbatasan Sistem

1. Sistem belum menerapkan autentikasi pengguna.
2. Pembayaran hanya berupa simulasi perubahan status pesanan.
3. Stok produk hanya diperiksa dan belum dikurangi.
4. Sistem belum memiliki fitur pembatalan pesanan.
5. Jika pengosongan keranjang gagal setelah pesanan tersimpan, pesanan tetap dipertahankan dan kegagalan dicatat pada log.

## 7. Informasi Repository

Repository:  
https://github.com/andiniyeri/sistem-enterprise.git

Dokumentasi ini menjelaskan konfigurasi dan fitur yang dikembangkan pada proyek UTS Microservices Order Service.