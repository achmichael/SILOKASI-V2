# Contoh Request API

## Quick Test (Menggunakan Data Jurnal)

### 1. Hitung Semua Sekaligus

```bash
curl -X POST http://localhost:8000/api/calculate/all
```

### 2. Lihat Hasil Akhir

```bash
curl -X GET http://localhost:8000/api/results/final-ranking
```

---

## Input Data Custom (Step by Step)

### Step 1: Input Kriteria

```bash
# KT - Kedekatan Tempat
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "KT",
    "name": "Kedekatan Tempat",
    "type": "benefit"
  }'

# BB - Biaya Bangunan
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "BB",
    "name": "Biaya Bangunan",
    "type": "benefit"
  }'

# II - Infrastruktur dan Indeks
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "II",
    "name": "Infrastruktur dan Indeks",
    "type": "benefit"
  }'

# LPD - Legalitas Perizinan Daerah
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "LPD",
    "name": "Legalitas Perizinan Daerah",
    "type": "benefit"
  }'

# ST - Sistem Transportasi
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "ST",
    "name": "Sistem Transportasi",
    "type": "benefit"
  }'

# BPT - Biaya Pajak Tanah (COST)
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "BPT",
    "name": "Biaya Pajak Tanah",
    "type": "cost"
  }'

# SPL - Sarana dan Prasarana Lingkungan
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "SPL",
    "name": "Sarana dan Prasarana Lingkungan",
    "type": "benefit"
  }'

# VM - Visi dan Misi
curl -X POST http://localhost:8000/api/criteria \
  -H "Content-Type: application/json" \
  -d '{
    "code": "VM",
    "name": "Visi dan Misi",
    "type": "benefit"
  }'
```

### Step 2: Input Alternatif

```bash
curl -X POST http://localhost:8000/api/alternatives \
  -H "Content-Type: application/json" \
  -d '{
    "code": "A1",
    "name": "Gentan",
    "description": "Lokasi Gentan"
  }'

curl -X POST http://localhost:8000/api/alternatives \
  -H "Content-Type: application/json" \
  -d '{
    "code": "A2",
    "name": "Palur Raya",
    "description": "Lokasi Palur Raya"
  }'

curl -X POST http://localhost:8000/api/alternatives \
  -H "Content-Type: application/json" \
  -d '{
    "code": "A3",
    "name": "Bekonang",
    "description": "Lokasi Bekonang"
  }'

curl -X POST http://localhost:8000/api/alternatives \
  -H "Content-Type: application/json" \
  -d '{
    "code": "A4",
    "name": "Makamhaji",
    "description": "Lokasi Makamhaji"
  }'

curl -X POST http://localhost:8000/api/alternatives \
  -H "Content-Type: application/json" \
  -d '{
    "code": "A5",
    "name": "Baturetno",
    "description": "Lokasi Baturetno"
  }'
```

### Step 3: Input Matriks Perbandingan Berpasangan (AHP)

```bash
curl -X POST http://localhost:8000/api/pairwise-comparisons/matrix \
  -H "Content-Type: application/json" \
  -d '{
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
  }'
```

### Step 4: Input Matriks Interdependensi ANP

```bash
curl -X POST http://localhost:8000/api/anp-interdependencies/matrix \
  -H "Content-Type: application/json" \
  -d '{
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
  }'
```

### Step 5: Input Rating Alternatif

```bash
curl -X POST http://localhost:8000/api/alternative-ratings/matrix \
  -H "Content-Type: application/json" \
  -d '{
    "matrix": [
      [5, 4, 5, 2, 3, 3, 2, 2],
      [4, 3, 2, 5, 2, 4, 3, 4],
      [5, 5, 3, 3, 2, 4, 1, 4],
      [3, 4, 5, 3, 3, 2, 4, 3],
      [4, 3, 5, 4, 2, 3, 3, 3]
    ]
  }'
```

### Step 6: Input Decision Makers

```bash
curl -X POST http://localhost:8000/api/decision-makers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "DM 1",
    "weight": 0.3
  }'

curl -X POST http://localhost:8000/api/decision-makers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "DM 2",
    "weight": 0.5
  }'

curl -X POST http://localhost:8000/api/decision-makers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "DM 3",
    "weight": 1.0
  }'
```

### Step 7: Input Poin Borda

```bash
curl -X POST http://localhost:8000/api/borda-points/matrix \
  -H "Content-Type: application/json" \
  -d '{
    "matrix": [
      [5, 1, 3, 4, 2],
      [4, 3, 5, 2, 1],
      [4, 2, 5, 1, 2]
    ]
  }'
```

### Step 8: Hitung AHP

```bash
curl -X POST http://localhost:8000/api/calculate/ahp
```

### Step 9: Hitung ANP

```bash
curl -X POST http://localhost:8000/api/calculate/anp
```

### Step 10: Hitung Weighted Product

```bash
curl -X POST http://localhost:8000/api/calculate/wp
```

### Step 11: Hitung BORDA

```bash
curl -X POST http://localhost:8000/api/calculate/borda
```

### Step 12: Lihat Hasil Akhir

```bash
curl -X GET http://localhost:8000/api/results/final-ranking
```

---

## Postman Collection (JSON)

Simpan sebagai file `.json` dan import ke Postman:

```json
{
  "info": {
    "name": "GDSS Housing API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Calculate All",
      "request": {
        "method": "POST",
        "header": [],
        "url": {
          "raw": "http://localhost:8000/api/calculate/all",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "calculate", "all"]
        }
      }
    },
    {
      "name": "Get Final Ranking",
      "request": {
        "method": "GET",
        "header": [],
        "url": {
          "raw": "http://localhost:8000/api/results/final-ranking",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "results", "final-ranking"]
        }
      }
    },
    {
      "name": "Calculate AHP",
      "request": {
        "method": "POST",
        "header": [],
        "url": {
          "raw": "http://localhost:8000/api/calculate/ahp",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "calculate", "ahp"]
        }
      }
    },
    {
      "name": "Calculate ANP",
      "request": {
        "method": "POST",
        "header": [],
        "url": {
          "raw": "http://localhost:8000/api/calculate/anp",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "calculate", "anp"]
        }
      }
    },
    {
      "name": "Calculate WP",
      "request": {
        "method": "POST",
        "header": [],
        "url": {
          "raw": "http://localhost:8000/api/calculate/wp",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "calculate", "wp"]
        }
      }
    },
    {
      "name": "Calculate BORDA",
      "request": {
        "method": "POST",
        "header": [],
        "url": {
          "raw": "http://localhost:8000/api/calculate/borda",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "calculate", "borda"]
        }
      }
    }
  ]
}
```

---

## Tips

1. Untuk testing cepat, gunakan `POST /api/calculate/all`
2. Data seeder sudah menyediakan semua data dari jurnal
3. Gunakan Postman, Thunder Client, atau curl untuk testing
4. Pastikan server Laravel sudah running di `http://localhost:8000`
