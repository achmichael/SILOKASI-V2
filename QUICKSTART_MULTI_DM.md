# Quick Start Guide - Multi Decision Maker

## Setup Awal

### 1. Jalankan Migration Baru
```bash
php artisan migrate
```

Migration akan menambahkan kolom `decision_maker_id` ke tabel `alternative_ratings`.

### 2. Seed Data Dasar (Opsional)
```bash
# Reset database dan seed data jurnal
php artisan migrate:fresh --seed
```

---

## Workflow A: Menggunakan Data Jurnal (Validasi)

Untuk memvalidasi hasil sesuai jurnal (A3 = 8.4):

```bash
# 1. Setup sudah dilakukan oleh seeder
php artisan migrate:fresh --seed

# 2. Hitung semua
curl -X POST http://localhost:8000/api/calculate/all

# 3. Lihat hasil
curl -X GET http://localhost:8000/api/results/final-ranking
```

**Output yang diharapkan:** A3 Bekonang (8.4) ✓

---

## Workflow B: Multi Decision Maker (Real GDSS)

### FASE 1: Persiapan Bersama (Sekali Saja)

#### 1.1 Setup Master Data
```bash
# Jika belum ada, jalankan seeder
php artisan db:seed --class=JournalDataSeeder
```

#### 1.2 Hitung AHP & ANP
```bash
# Hitung AHP
curl -X POST http://localhost:8000/api/calculate/ahp

# Hitung ANP
curl -X POST http://localhost:8000/api/calculate/anp
```

---

### FASE 2: Decision Maker 1

#### 2.1 Register DM 1
```bash
curl -X POST http://localhost:8000/api/decision-makers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "DM 1 - Manajer Proyek",
    "weight": 0.3
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "DM 1 - Manajer Proyek",
    "weight": 0.3
  }
}
```

#### 2.2 DM 1 Input Rating Alternatif
```bash
curl -X POST 'http://localhost:8000/api/alternative-ratings/matrix?dm_id=1' \
  -H "Content-Type: application/json" \
  -d '{
    "decision_maker_id": 1,
    "matrix": [
      [5, 4, 5, 2, 3, 3, 2, 2],
      [4, 3, 2, 5, 2, 4, 3, 4],
      [5, 5, 3, 3, 2, 4, 1, 4],
      [3, 4, 5, 3, 3, 2, 4, 3],
      [4, 3, 5, 4, 2, 3, 3, 3]
    ]
  }'
```

**Atau melalui body saja:**
```bash
curl -X POST http://localhost:8000/api/alternative-ratings/matrix \
  -H "Content-Type: application/json" \
  -d '{
    "decision_maker_id": 1,
    "matrix": [...]
  }'
```

#### 2.3 Hitung WP untuk DM 1
```bash
curl -X POST 'http://localhost:8000/api/calculate/wp?dm_id=1'
```

**Response:**
```json
{
  "success": true,
  "message": "Weighted Product calculation completed for DM 1",
  "data": {
    "decision_maker_id": 1,
    "vector_s": [...],
    "vector_v": [...],
    "rankings": [3, 5, 1, 2, 4],
    "borda_points": [3, 1, 5, 4, 2],
    "alternatives": [
      {
        "id": 1,
        "code": "A1",
        "name": "Gentan",
        "rank": 3,
        "borda_point": 3
      },
      ...
    ]
  }
}
```

#### 2.4 Simpan Poin Borda DM 1
```bash
curl -X POST http://localhost:8000/api/borda-points/bulk \
  -H "Content-Type: application/json" \
  -d '{
    "points": [
      {"decision_maker_id": 1, "alternative_id": 1, "points": 3},
      {"decision_maker_id": 1, "alternative_id": 2, "points": 1},
      {"decision_maker_id": 1, "alternative_id": 3, "points": 5},
      {"decision_maker_id": 1, "alternative_id": 4, "points": 4},
      {"decision_maker_id": 1, "alternative_id": 5, "points": 2}
    ]
  }'
```

---

### FASE 3: Decision Maker 2

#### 3.1 Register DM 2
```bash
curl -X POST http://localhost:8000/api/decision-makers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "DM 2 - Direktur Keuangan",
    "weight": 0.5
  }'
```

#### 3.2 DM 2 Input Rating (Rating Berbeda)
```bash
curl -X POST 'http://localhost:8000/api/alternative-ratings/matrix?dm_id=2' \
  -H "Content-Type: application/json" \
  -d '{
    "decision_maker_id": 2,
    "matrix": [
      [4, 5, 4, 3, 4, 2, 3, 3],
      [3, 4, 3, 4, 3, 3, 4, 3],
      [5, 5, 4, 5, 3, 3, 2, 5],
      [3, 3, 4, 2, 2, 3, 3, 2],
      [2, 3, 3, 3, 2, 4, 2, 3]
    ]
  }'
```

#### 3.3 Hitung WP untuk DM 2
```bash
curl -X POST 'http://localhost:8000/api/calculate/wp?dm_id=2'
```

#### 3.4 Simpan Poin Borda DM 2
```bash
curl -X POST http://localhost:8000/api/borda-points/bulk \
  -H "Content-Type: application/json" \
  -d '{
    "points": [
      {"decision_maker_id": 2, "alternative_id": 1, "points": 4},
      {"decision_maker_id": 2, "alternative_id": 2, "points": 3},
      {"decision_maker_id": 2, "alternative_id": 3, "points": 5},
      {"decision_maker_id": 2, "alternative_id": 4, "points": 2},
      {"decision_maker_id": 2, "alternative_id": 5, "points": 1}
    ]
  }'
```

---

### FASE 4: Decision Maker 3

Ulangi langkah yang sama untuk DM 3 (weight = 1.0)

---

### FASE 5: Agregasi BORDA

```bash
curl -X POST http://localhost:8000/api/calculate/borda
```

**Response:**
```json
{
  "success": true,
  "message": "BORDA calculation completed",
  "data": {
    "alternatives": [
      {
        "code": "A3",
        "name": "Bekonang",
        "borda_score": 8.5,
        "rank": 1
      },
      {
        "code": "A1",
        "name": "Gentan",
        "borda_score": 6.7,
        "rank": 2
      },
      ...
    ]
  }
}
```

---

## Postman Collection

### Collection Setup

1. **Create Collection**: "GDSS Multi-DM"
2. **Add Variable**: `base_url = http://localhost:8000/api`

### Requests

#### 1. Setup AHP & ANP
```
POST {{base_url}}/calculate/ahp
POST {{base_url}}/calculate/anp
```

#### 2. Register DM 1
```
POST {{base_url}}/decision-makers
Body:
{
  "name": "DM 1",
  "weight": 0.3
}

# Save response.data.id to variable {{dm1_id}}
```

#### 3. DM 1 Input Rating
```
POST {{base_url}}/alternative-ratings/matrix?dm_id={{dm1_id}}
Body:
{
  "decision_maker_id": {{dm1_id}},
  "matrix": [...]
}
```

#### 4. DM 1 Calculate WP
```
POST {{base_url}}/calculate/wp?dm_id={{dm1_id}}

# Copy borda_points dari response
```

#### 5. DM 1 Save Borda Points
```
POST {{base_url}}/borda-points/bulk
Body:
{
  "points": [
    {"decision_maker_id": {{dm1_id}}, "alternative_id": 1, "points": 3},
    ...
  ]
}
```

#### 6. Repeat for DM 2 & DM 3

#### 7. Final BORDA
```
POST {{base_url}}/calculate/borda
GET {{base_url}}/results/final-ranking
```

---

## Automated Script (Bash)

Buat file `test-multi-dm.sh`:

```bash
#!/bin/bash

BASE_URL="http://localhost:8000/api"

echo "=== FASE 1: Setup Bersama ==="
curl -X POST $BASE_URL/calculate/ahp
curl -X POST $BASE_URL/calculate/anp

echo "\n=== FASE 2: DM 1 ==="
DM1=$(curl -X POST $BASE_URL/decision-makers \
  -H "Content-Type: application/json" \
  -d '{"name":"DM 1","weight":0.3}' | jq -r '.data.id')

curl -X POST "$BASE_URL/alternative-ratings/matrix?dm_id=$DM1" \
  -H "Content-Type: application/json" \
  -d @dm1-ratings.json

curl -X POST "$BASE_URL/calculate/wp?dm_id=$DM1"

# Extract borda points dari response dan simpan
curl -X POST $BASE_URL/borda-points/bulk \
  -H "Content-Type: application/json" \
  -d @dm1-borda.json

echo "\n=== FASE 3: DM 2 ==="
# Similar for DM 2

echo "\n=== FASE 4: DM 3 ==="
# Similar for DM 3

echo "\n=== FASE 5: BORDA ==="
curl -X POST $BASE_URL/calculate/borda
curl -X GET $BASE_URL/results/final-ranking
```

Jalankan:
```bash
chmod +x test-multi-dm.sh
./test-multi-dm.sh
```

---

## Troubleshooting

### Error: "Matrix size must be M x N"
- Pastikan matriks rating berukuran 5 (alternatif) × 8 (kriteria)

### Error: "ANP calculation not found"
- Jalankan AHP dan ANP terlebih dahulu

### Error: "Decision maker not found"
- Pastikan DM sudah terdaftar dengan ID yang benar

### Data Lama Tertimpa
- Setiap kali input rating dengan `dm_id` yang sama, data lama untuk DM tersebut akan dihapus
- Gunakan `dm_id` yang berbeda untuk setiap Decision Maker

---

## Perbedaan dengan Workflow Jurnal

| Aspek | Jurnal (Single) | Multi-DM (Real) |
|-------|----------------|-----------------|
| Rating | 1 set untuk semua | 1 set per DM |
| WP | 1 kali | Per DM |
| Poin Borda | Manual (Tabel 6) | Auto dari WP |
| Endpoint Rating | `/matrix` | `/matrix?dm_id=X` |
| Endpoint WP | `/wp` | `/wp?dm_id=X` |
| BORDA Input | Tabel 6 jurnal | Dari WP rankings |

---

**Selamat mencoba! 🚀**
