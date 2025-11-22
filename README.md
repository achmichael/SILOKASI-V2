# GDSS Pemilihan Lokasi Perumahan (ANP-WP-BORDA)

**Group Decision Support System** untuk pemilihan lokasi pembangunan perumahan menggunakan metode **Analytical Network Process (ANP)**, **Weighted Product (WP)**, dan **BORDA**.

## 📋 Deskripsi Proyek

Sistem ini mengimplementasikan metodologi pengambilan keputusan kelompok untuk menentukan lokasi terbaik pembangunan perumahan. Proyek ini dibuat berdasarkan Jurnal IJCCS Vol. 13, No. 4, Oktober 2019 dengan modifikasi pada Langkah 3 menggunakan **Weighted Product** (menggantikan SAW).

### Metodologi

1. **AHP (Analytical Hierarchy Process)**: Menghitung bobot kriteria dari matriks perbandingan berpasangan
2. **ANP (Analytical Network Process)**: Menghitung bobot kriteria dengan mempertimbangkan interdependensi
3. **Weighted Product**: Menghitung ranking individu untuk setiap decision maker
4. **BORDA**: Agregasi keputusan kelompok untuk mendapatkan ranking akhir

## ✨ Fitur

- ✅ Implementasi lengkap metode ANP-WP-BORDA
- ✅ RESTful API untuk semua operasi
- ✅ Input data dinamis dari user melalui API
- ✅ Validasi konsistensi AHP (Consistency Ratio)
- ✅ Data seeder dari jurnal untuk testing
- ✅ Hasil perhitungan tersimpan di database
- ✅ Dokumentasi API lengkap

## 🎯 Output yang Diharapkan

Berdasarkan data jurnal, hasil akhir adalah:

| Rank | Alternatif | Lokasi | Skor Borda |
|------|-----------|--------|------------|
| 1 | A3 | Bekonang | **8.4** |
| 2 | A1 | Gentan | 7.5 |
| 3 | A2 | Palur Raya | 3.8 |
| 4 | A4 | Makamhaji | 3.2 |
| 5 | A5 | Baturetno | 3.1 |

## 🚀 Quick Start

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Laravel 11

### Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd UAS-Project
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gdss_housing
DB_USERNAME=root
DB_PASSWORD=
```

5. **Migrasi & Seeding**
```bash
php artisan migrate
php artisan db:seed
```

6. **Jalankan Server**
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## 📖 Penggunaan API

### Urutan Eksekusi (Menggunakan Data Jurnal)

Data jurnal sudah di-seed otomatis. Jalankan perhitungan secara berurutan:

```bash
# 1. Hitung AHP (Bobot Kriteria)
POST http://localhost:8000/api/calculate/ahp

# 2. Hitung ANP (Bobot Interdependensi)
POST http://localhost:8000/api/calculate/anp

# 3. Hitung Weighted Product (Ranking Individu)
POST http://localhost:8000/api/calculate/wp

# 4. Hitung BORDA (Ranking Kelompok - Hasil Akhir)
POST http://localhost:8000/api/calculate/borda

# Atau jalankan semua sekaligus:
POST http://localhost:8000/api/calculate/all

# Lihat hasil akhir:
GET http://localhost:8000/api/results/final-ranking
```

### Input Data Custom

Untuk menggunakan data custom, ikuti dokumentasi lengkap di [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

Urutan input data:
1. Input Kriteria (`POST /api/criteria`)
2. Input Alternatif (`POST /api/alternatives`)
3. Input Matriks Perbandingan Berpasangan (`POST /api/pairwise-comparisons/matrix`)
4. Input Matriks Interdependensi ANP (`POST /api/anp-interdependencies/matrix`)
5. Input Rating Alternatif (`POST /api/alternative-ratings/matrix`)
6. Input Decision Makers (`POST /api/decision-makers`)
7. Input Poin Borda (`POST /api/borda-points/matrix`)
8. Jalankan Perhitungan

## 📁 Struktur Proyek

```
app/
├── Http/Controllers/
│   ├── AlternativeController.php
│   ├── AlternativeRatingController.php
│   ├── AnpInterdependencyController.php
│   ├── BordaPointController.php
│   ├── CalculationController.php
│   ├── CriteriaController.php
│   ├── DecisionMakerController.php
│   └── PairwiseComparisonController.php
├── Models/
│   ├── Alternative.php
│   ├── AlternativeRating.php
│   ├── AnpInterdependency.php
│   ├── BordaPoint.php
│   ├── CalculationResult.php
│   ├── Criteria.php
│   ├── DecisionMaker.php
│   └── PairwiseComparison.php
└── Services/
    ├── AhpService.php
    ├── AnpService.php
    ├── BordaService.php
    └── WeightedProductService.php

database/
├── migrations/
│   ├── 2024_11_18_000001_create_criteria_table.php
│   ├── 2024_11_18_000002_create_alternatives_table.php
│   ├── 2024_11_18_000003_create_pairwise_comparisons_table.php
│   ├── 2024_11_18_000004_create_anp_interdependencies_table.php
│   ├── 2024_11_18_000005_create_alternative_ratings_table.php
│   ├── 2024_11_18_000006_create_decision_makers_table.php
│   ├── 2024_11_18_000007_create_borda_points_table.php
│   └── 2024_11_18_000008_create_calculation_results_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── JournalDataSeeder.php

routes/
└── api.php
```

## 🔬 Testing

### Test dengan Data Jurnal

```bash
# Seed data jurnal
php artisan db:seed --class=JournalDataSeeder

# Test API menggunakan Postman/Thunder Client
# Atau gunakan curl:

curl -X POST http://localhost:8000/api/calculate/all
curl -X GET http://localhost:8000/api/results/final-ranking
```

### Validasi Hasil

Pastikan hasil BORDA menunjukkan:
- **A3 (Bekonang)** sebagai Rank 1 dengan skor **8.4**
- Sesuai dengan output jurnal

## 📚 Dokumentasi

- [API Documentation](API_DOCUMENTATION.md) - Dokumentasi lengkap semua endpoint API
- Jurnal Referensi: IJCCS Vol. 13, No. 4, Oktober 2019

## 🛠️ Teknologi

- **Framework**: Laravel 11
- **Database**: MySQL
- **PHP**: 8.2+
- **Metode**: ANP, Weighted Product, BORDA

## 📊 Data Kriteria

| Kode | Nama Kriteria | Type |
|------|--------------|------|
| KT | Kedekatan Tempat | Benefit |
| BB | Biaya Bangunan | Benefit |
| II | Infrastruktur dan Indeks | Benefit |
| LPD | Legalitas Perizinan Daerah | Benefit |
| ST | Sistem Transportasi | Benefit |
| BPT | Biaya Pajak Tanah | **Cost** |
| SPL | Sarana dan Prasarana Lingkungan | Benefit |
| VM | Visi dan Misi | Benefit |

## 📊 Data Alternatif

| Kode | Nama Lokasi |
|------|------------|
| A1 | Gentan |
| A2 | Palur Raya |
| A3 | Bekonang |
| A4 | Makamhaji |
| A5 | Baturetno |

## 🤝 Kontribusi

Proyek ini dibuat untuk keperluan UAS Praktikum Sistem Informasi.

## 📝 License

Open source untuk keperluan edukasi.

## 📧 Contact

Untuk pertanyaan dan masukan, silakan hubungi tim development.

---

**Built with ❤️ using Laravel**


## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
