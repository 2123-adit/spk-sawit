# SYSTEM REQUIREMENTS

# Functional Requirements

## Dashboard

Sistem menampilkan halaman utama yang berisi:

- Informasi mengenai Sistem Pendukung Keputusan (SPK)
- Penjelasan singkat metode MOORA
- Ringkasan jumlah alternatif
- Ringkasan jumlah kriteria
- Menu navigasi sistem

---

## Data Kriteria

Sistem dapat menampilkan daftar seluruh kriteria yang digunakan dalam proses perhitungan harga kelapa sawit.

Kriteria yang digunakan:

| Kode | Nama Kriteria |
|------|---------------|
| C1 | Umur Tanaman |
| C2 | Faktor K |
| C3 | Rendemen CPO |
| C4 | Harga CPO |
| C5 | Rendemen Kernel |
| C6 | Harga Kernel |

---

## Data Alternatif

Sistem dapat menampilkan data alternatif yang akan dihitung menggunakan metode MOORA.

Data alternatif meliputi:

- Umur Tanaman
- Faktor K
- Rendemen CPO
- Harga CPO
- Rendemen Kernel
- Harga Kernel

---

## Perhitungan MOORA

Sistem dapat melakukan proses perhitungan secara otomatis meliputi:

1. Menyusun Matriks Keputusan
2. Normalisasi Matriks
3. Pembobotan Kriteria
4. Menghitung Nilai Preferensi
5. Melakukan Perangkingan
6. Menentukan Alternatif Terbaik

---

## Hasil Perhitungan

Sistem menampilkan hasil berupa:

- Matriks Keputusan
- Matriks Normalisasi
- Nilai Optimasi
- Nilai Preferensi
- Ranking Alternatif
- Alternatif Terbaik

---

## Laporan

Sistem dapat mencetak hasil perhitungan dalam bentuk PDF.

---

# Non Functional Requirements

## Framework

- Laravel 12

---

## Bahasa Pemrograman

- PHP 8.3

---

## Frontend

- Tailwind CSS
- Blade Template
- Alpine.js

---

## Database

- MySQL

---

## Web Server

- Apache (XAMPP)

---

## Build Tools

- Vite

---

## Browser Support

- Google Chrome
- Microsoft Edge
- Mozilla Firefox

---

## Responsive Design

Sistem dapat digunakan pada:

- Desktop
- Laptop
- Tablet
- Smartphone

---

## Performance

- Proses perhitungan MOORA dilakukan secara otomatis.
- Halaman memiliki waktu respon yang cepat.
- Perhitungan dapat dilakukan tanpa reload yang berlebihan.

---

## Security

Walaupun sistem tidak menggunakan login, Laravel tetap memanfaatkan:

- CSRF Protection
- Validasi Input
- Eloquent ORM
- Error Handling

---

# Software Requirements

| Software | Versi |
|----------|-------|
| PHP | 8.3+ |
| Laravel | 12 |
| Composer | Terbaru |
| MySQL | 8.x |
| Node.js | 22 LTS |
| Tailwind CSS | 4.x |
| Vite | Terbaru |
| Visual Studio Code | Terbaru |
| Git | Terbaru |

---

# Hardware Requirements

Minimal:

- Processor Intel Core i3 Generasi 10 atau setara
- RAM 8 GB
- SSD 256 GB

Rekomendasi:

- Intel Core i5 / Ryzen 5
- RAM 16 GB
- SSD 512 GB

---

# Pengguna Sistem

Sistem hanya memiliki **satu jenis pengguna (User)**.

User dapat:

- Melihat Dashboard
- Melihat Data Kriteria
- Melihat Data Alternatif
- Menjalankan Perhitungan MOORA
- Melihat Matriks Keputusan
- Melihat Matriks Normalisasi
- Melihat Nilai Preferensi
- Melihat Ranking
- Melihat Alternatif Terbaik
- Mencetak Laporan PDF

Tidak terdapat fitur:

- Login
- Registrasi
- Manajemen Pengguna
- Hak Akses Admin

---

# Arsitektur Sistem

Sistem menggunakan pola arsitektur MVC (Model-View-Controller) yang disediakan oleh Laravel.

Struktur utama:

- Model
- Controller
- Blade View
- Service (Perhitungan MOORA)
- Migration
- Seeder
- Route

---

# Teknologi yang Digunakan

## Backend

- Laravel 12
- PHP 8.3

## Frontend

- Tailwind CSS 4
- Blade Template
- Alpine.js

## Database

- MySQL

## Build Tool

- Vite

## Export

- Laravel DomPDF

## Version Control

- Git