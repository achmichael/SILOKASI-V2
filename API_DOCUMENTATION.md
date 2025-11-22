# API Documentation - GDSS Pemilihan Lokasi Perumahan (ANP-WP-BORDA)

## Daftar Isi
1. [Setup dan Instalasi](#setup-dan-instalasi)
2. [Urutan Penggunaan API](#urutan-penggunaan-api)
3. [Endpoint API](#endpoint-api)
4. [Contoh Request/Response](#contoh-requestresponse)

---

## Setup dan Instalasi

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gdss_housing
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi Database
```bash
php artisan migrate
```

### 5. Seed Data Awal (Data dari Jurnal)
```bash
php artisan db:seed --class=JournalDataSeeder
```

### 6. Jalankan Server
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

---

## Urutan Penggunaan API

Untuk mendapatkan hasil yang sesuai dengan jurnal (A3 Bekonang, Skor 8.4), ikuti urutan berikut:

### **Skenario 1: Menggunakan Data Jurnal (Recommended)**

**Langkah 1-5 sudah dilakukan otomatis oleh seeder. Langsung lanjut ke perhitungan:**

#### **Step 1: Hitung AHP (Bobot Kriteria)**
```http
POST http://localhost:8000/api/calculate/ahp
```

Output yang diharapkan:
- Bobot kriteria: W = [0.24, 0.31, 0.12, 0.09, 0.11, 0.05, 0.04, 0.04]
- CR < 0.1 (konsisten)

---

#### **Step 2: Hitung ANP (Bobot Interdependensi)**
```http
POST http://localhost:8000/api/calculate/anp
```

Output yang diharapkan:
- Bobot ANP: W_ANP = [0.06, 0.05, 0.02, 0.26, 0.09, 0.12, 0.05, 0.01]

---

#### **Step 3: Hitung Weighted Product (Ranking Individu)**
```http
POST http://localhost:8000/api/calculate/wp
```

Output:
- Vector S dan Vector V untuk setiap alternatif
- Ranking individu berdasarkan WP

---

#### **Step 4: Hitung BORDA (Ranking Kelompok)**
```http
POST http://localhost:8000/api/calculate/borda
```

Output yang diharapkan:
- **A3 (Bekonang): Skor 8.4 - Rank 1** ✓
- A1 (Gentan): Skor 7.5 - Rank 2
- A2 (Palur Raya): Skor 3.8 - Rank 3
- A4 (Makamhaji): Skor 3.2 - Rank 4
- A5 (Baturetno): Skor 3.1 - Rank 5

---

#### **Step 5: Lihat Hasil Akhir**
```http
GET http://localhost:8000/api/results/final-ranking
```

---

### **Skenario 2: Input Data Custom dari User**

Jika ingin menggunakan data custom (bukan dari jurnal), ikuti langkah berikut:

#### **Step 1: Input Kriteria**
```http
POST http://localhost:8000/api/criteria
Content-Type: application/json

{
  "code": "KT",
  "name": "Kedekatan Tempat",
  "type": "benefit"
}
```

Ulangi untuk semua kriteria (8 kriteria).

---

#### **Step 2: Input Alternatif**
```http
POST http://localhost:8000/api/alternatives
Content-Type: application/json

{
  "code": "A1",
  "name": "Gentan",
  "description": "Lokasi Gentan"
}
```

Ulangi untuk semua alternatif (5 alternatif).

---

#### **Step 3: Input Matriks Perbandingan Berpasangan (AHP)**
```http
POST http://localhost:8000/api/pairwise-comparisons/matrix
Content-Type: application/json

{
  "matrix": [
    [1.00, 1.00, 3.00, 3.00, 3.00, 4.00, 5.00, 3.00],
    [1.00, 1.00, 5.00, 7.00, 2.00, 7.00, 5.00, 5.00],
    [0.33, 0.20, 1.00, 2.00, 3.00, 4.00, 2.00, 2.00],
    [0.33, 0.14, 0.50, 1.00, 1.00, 2.00, 3.00, 3.00],
    [0.33, 0.50, 0.33, 1.00, 1.00, 4.00, 3.00, 5.00],
    [0.25, 0.14, 0.25, 0.50, 0.25, 1.00, 2.00, 3.00],
    [0.20, 0.20, 0.50, 0.33, 0.33, 0.50, 1.00, 2.00],
    [0.33, 0.20, 0.50, 0.33, 0.20, 0.33, 0.50, 1.00]
  ]
}
```

---

#### **Step 4: Input Matriks Interdependensi ANP**
```http
POST http://localhost:8000/api/anp-interdependencies/matrix
Content-Type: application/json

{
  "matrix": [
    [0.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
    [0.00, 0.17, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
    [0.00, 0.00, 0.14, 0.00, 0.00, 0.00, 0.00, 0.00],
    [0.00, 0.50, 0.29, 1.00, 0.50, 0.00, 0.00, 0.75],
    [0.00, 0.00, 0.00, 0.00, 0.13, 0.00, 0.67, 0.00],
    [0.75, 0.00, 0.57, 0.00, 0.00, 1.00, 0.00, 0.00],
    [0.00, 0.33, 0.00, 0.00, 0.38, 0.00, 0.33, 0.00],
    [0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.25]
  ]
}
```

---

#### **Step 5: Input Rating Alternatif**
```http
POST http://localhost:8000/api/alternative-ratings/matrix
Content-Type: application/json

{
  "matrix": [
    [5, 4, 5, 2, 3, 3, 2, 2],
    [4, 3, 2, 5, 2, 4, 3, 4],
    [5, 5, 3, 3, 2, 4, 1, 4],
    [3, 4, 5, 3, 3, 2, 4, 3],
    [4, 3, 5, 4, 2, 3, 3, 3]
  ]
}
```

---

#### **Step 6: Input Decision Makers**
```http
POST http://localhost:8000/api/decision-makers
Content-Type: application/json

{
  "name": "DM 1",
  "weight": 0.3
}
```

Ulangi untuk DM 2 (weight: 0.5) dan DM 3 (weight: 1.0).

---

#### **Step 7: Input Poin Borda**
```http
POST http://localhost:8000/api/borda-points/matrix
Content-Type: application/json

{
  "matrix": [
    [5, 1, 3, 4, 2],
    [4, 3, 5, 2, 1],
    [4, 2, 5, 1, 2]
  ]
}
```

---

#### **Step 8-11: Jalankan Perhitungan (sama seperti Skenario 1)**

Atau gunakan endpoint untuk menjalankan semua perhitungan sekaligus:

```http
POST http://localhost:8000/api/calculate/all
```

---

## Endpoint API

### **Master Data**

#### Criteria
- `GET /api/criteria` - List semua kriteria
- `POST /api/criteria` - Tambah kriteria baru
- `PUT /api/criteria/{id}` - Update kriteria
- `DELETE /api/criteria/{id}` - Hapus kriteria

#### Alternatives
- `GET /api/alternatives` - List semua alternatif
- `POST /api/alternatives` - Tambah alternatif baru
- `PUT /api/alternatives/{id}` - Update alternatif
- `DELETE /api/alternatives/{id}` - Hapus alternatif

#### Decision Makers
- `GET /api/decision-makers` - List semua decision makers
- `POST /api/decision-makers` - Tambah decision maker
- `PUT /api/decision-makers/{id}` - Update decision maker
- `DELETE /api/decision-makers/{id}` - Hapus decision maker

---

### **Input Data**

#### Pairwise Comparisons (AHP)
- `GET /api/pairwise-comparisons` - List semua perbandingan
- `POST /api/pairwise-comparisons/bulk` - Input bulk (array of objects)
- `POST /api/pairwise-comparisons/matrix` - Input matriks N x N

#### ANP Interdependencies
- `GET /api/anp-interdependencies` - List semua interdependensi
- `POST /api/anp-interdependencies/matrix` - Input matriks N x N

#### Alternative Ratings
- `GET /api/alternative-ratings` - List semua rating
- `POST /api/alternative-ratings/bulk` - Input bulk
- `POST /api/alternative-ratings/matrix` - Input matriks M x N

#### Borda Points
- `GET /api/borda-points` - List semua poin Borda
- `POST /api/borda-points/bulk` - Input bulk
- `POST /api/borda-points/matrix` - Input matriks DM x Alternative

---

### **Calculation**

- `POST /api/calculate/ahp` - Hitung AHP
- `POST /api/calculate/anp` - Hitung ANP
- `POST /api/calculate/wp` - Hitung Weighted Product
- `POST /api/calculate/borda` - Hitung BORDA
- `POST /api/calculate/all` - Hitung semua (AHP → ANP → WP → BORDA)

---

### **Results**

- `GET /api/results` - List semua hasil perhitungan
- `GET /api/results?method=AHP` - Filter hasil berdasarkan method
- `GET /api/results/final-ranking` - Hasil akhir (BORDA)

---

## Contoh Request/Response

### 1. Hitung AHP

**Request:**
```http
POST http://localhost:8000/api/calculate/ahp
```

**Response:**
```json
{
  "success": true,
  "message": "AHP calculation completed",
  "data": {
    "weights": [0.24, 0.31, 0.12, 0.09, 0.11, 0.05, 0.04, 0.04],
    "lambda_max": 8.5234,
    "ci": 0.0748,
    "cr": 0.0531,
    "ri": 1.41,
    "is_consistent": true,
    "n": 8,
    "criteria": [
      {
        "id": 1,
        "code": "KT",
        "name": "Kedekatan Tempat",
        "weight": 0.24
      },
      ...
    ]
  }
}
```

---

### 2. Hitung ANP

**Request:**
```http
POST http://localhost:8000/api/calculate/anp
```

**Response:**
```json
{
  "success": true,
  "message": "ANP calculation completed",
  "data": {
    "weights": [0.06, 0.05, 0.02, 0.26, 0.09, 0.12, 0.05, 0.01],
    "total_weight": 0.66,
    "criteria": [
      {
        "id": 1,
        "code": "KT",
        "name": "Kedekatan Tempat",
        "ahp_weight": 0.24,
        "anp_weight": 0.06
      },
      ...
    ]
  }
}
```

---

### 3. Hitung Weighted Product

**Request:**
```http
POST http://localhost:8000/api/calculate/wp
```

**Response:**
```json
{
  "success": true,
  "message": "Weighted Product calculation completed",
  "data": {
    "vector_s": [2.856743, 3.124567, 3.456789, 2.987654, 3.234567],
    "vector_v": [0.185234, 0.202456, 0.223789, 0.193654, 0.209567],
    "rankings": [5, 2, 1, 4, 3],
    "alternatives": [
      {
        "id": 1,
        "code": "A1",
        "name": "Gentan",
        "vector_s": 2.856743,
        "vector_v": 0.185234,
        "rank": 5
      },
      ...
    ]
  }
}
```

---

### 4. Hitung BORDA (Hasil Akhir)

**Request:**
```http
POST http://localhost:8000/api/calculate/borda
```

**Response:**
```json
{
  "success": true,
  "message": "BORDA calculation completed",
  "data": {
    "scores": [7.5, 3.8, 8.4, 3.2, 3.1],
    "rankings": [2, 3, 1, 4, 5],
    "alternatives": [
      {
        "id": 3,
        "code": "A3",
        "name": "Bekonang",
        "borda_score": 8.4,
        "rank": 1
      },
      {
        "id": 1,
        "code": "A1",
        "name": "Gentan",
        "borda_score": 7.5,
        "rank": 2
      },
      {
        "id": 2,
        "code": "A2",
        "name": "Palur Raya",
        "borda_score": 3.8,
        "rank": 3
      },
      {
        "id": 4,
        "code": "A4",
        "name": "Makamhaji",
        "borda_score": 3.2,
        "rank": 4
      },
      {
        "id": 5,
        "code": "A5",
        "name": "Baturetno",
        "borda_score": 3.1,
        "rank": 5
      }
    ]
  }
}
```

**✓ Hasil sesuai jurnal: A3 Bekonang dengan skor 8.4 sebagai Rank 1**

---

### 5. Hitung Semua Sekaligus

**Request:**
```http
POST http://localhost:8000/api/calculate/all
```

**Response:**
```json
{
  "success": true,
  "message": "All calculations completed successfully",
  "data": {
    "ahp": { ... },
    "anp": { ... },
    "wp": { ... },
    "borda": { ... }
  }
}
```

---

## Validasi Hasil

Untuk memverifikasi hasil sesuai dengan jurnal:

1. **AHP**: Bobot kriteria harus mendekati W = [0.24, 0.31, 0.12, 0.09, 0.11, 0.05, 0.04, 0.04]
2. **ANP**: Bobot ANP harus mendekati W_ANP = [0.06, 0.05, 0.02, 0.26, 0.09, 0.12, 0.05, 0.01]
3. **BORDA**: Hasil akhir harus menunjukkan **A3 (Bekonang) dengan skor 8.4 sebagai Rank 1**

---

## Error Handling

Jika terjadi error, response akan berbentuk:

```json
{
  "success": false,
  "message": "Error description"
}
```

Common errors:
- `AHP calculation not found. Please run AHP first.` - Jalankan AHP terlebih dahulu
- `ANP calculation not found. Please run ANP first.` - Jalankan ANP terlebih dahulu
- `Matrix size must be N x N` - Ukuran matriks tidak sesuai

---

## Tips

1. Untuk testing cepat, gunakan endpoint `POST /api/calculate/all`
2. Gunakan Postman atau Thunder Client untuk testing API
3. Data seeder sudah menyediakan data lengkap dari jurnal
4. Pastikan menjalankan perhitungan secara berurutan: AHP → ANP → WP → BORDA
5. Hasil perhitungan disimpan di database tabel `calculation_results`

---

## Kontributor

- Implementasi ANP-WP-BORDA untuk GDSS Pemilihan Lokasi Perumahan
- Berdasarkan Jurnal IJCCS Vol. 13, No. 4, Oktober 2019

---

**Happy Coding! 🚀**
