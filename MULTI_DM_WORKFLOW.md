# Panduan Multi Decision Maker Workflow

## Konsep GDSS (Group Decision Support System)

Dalam GDSS, terdapat **beberapa Decision Maker (DM)** yang masing-masing memberikan penilaian independen terhadap alternatif. Sistem kemudian mengagregasi keputusan mereka untuk mendapatkan keputusan kelompok.

---

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                    LANGKAH PERSIAPAN BERSAMA                 │
│  (Dilakukan sekali, digunakan oleh semua Decision Maker)     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                  ┌───────────────────────┐
                  │  1. Setup Master Data │
                  │  - Kriteria (8)       │
                  │  - Alternatif (5)     │
                  └───────────────────────┘
                              │
                              ▼
                  ┌───────────────────────┐
                  │  2. Hitung AHP        │
                  │  (Bobot Kriteria)     │
                  └───────────────────────┘
                              │
                              ▼
                  ┌───────────────────────┐
                  │  3. Hitung ANP        │
                  │  (Bobot Interdepen.)  │
                  └───────────────────────┘
                              │
┌─────────────────────────────┼─────────────────────────────┐
│                             │                             │
▼                             ▼                             ▼
┌─────────────┐    ┌─────────────┐           ┌─────────────┐
│   DM 1      │    │   DM 2      │    ...    │   DM 3      │
│  (w=0.3)    │    │  (w=0.5)    │           │  (w=1.0)    │
└─────────────┘    └─────────────┘           └─────────────┘
      │                   │                          │
      ▼                   ▼                          ▼
┌─────────────┐    ┌─────────────┐           ┌─────────────┐
│ 4. Rating   │    │ 4. Rating   │           │ 4. Rating   │
│ Alternatif  │    │ Alternatif  │           │ Alternatif  │
│ (per DM)    │    │ (per DM)    │           │ (per DM)    │
└─────────────┘    └─────────────┘           └─────────────┘
      │                   │                          │
      ▼                   ▼                          ▼
┌─────────────┐    ┌─────────────┐           ┌─────────────┐
│ 5. Hitung WP│    │ 5. Hitung WP│           │ 5. Hitung WP│
│ (Ranking DM)│    │ (Ranking DM)│           │ (Ranking DM)│
└─────────────┘    └─────────────┘           └─────────────┘
      │                   │                          │
      └───────────────────┼──────────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │ 6. Konversi ke Poin   │
              │    Borda (1-5)        │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │ 7. BORDA Agregasi     │
              │ (Weighted Voting)     │
              │                       │
              │ V_Borda = Σ(P_ik×w_k) │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │  HASIL AKHIR KELOMPOK │
              │  A3 Bekonang (8.4)    │
              └───────────────────────┘
```

---

## Alur Lengkap Penggunaan API

### **FASE 1: Persiapan Bersama (Shared Setup)**

Langkah ini dilakukan **sekali saja** dan hasilnya digunakan oleh semua DM.

#### **Step 1: Setup Master Data**

```bash
# Jalankan seeder untuk data kriteria dan alternatif
php artisan db:seed --class=JournalDataSeeder

# Atau input manual via API
POST /api/criteria
POST /api/alternatives
```

#### **Step 2: Input Matriks Perbandingan Berpasangan (AHP)**

```bash
POST /api/pairwise-comparisons/matrix
Content-Type: application/json

{
  "matrix": [
    [1.00, 1.00, 3.00, 3.00, 3.00, 4.00, 5.00, 3.00],
    [1.00, 1.00, 5.00, 7.00, 2.00, 7.00, 5.00, 5.00],
    ...
  ]
}
```

#### **Step 3: Hitung AHP (Bobot Kriteria Global)**

```bash
POST /api/calculate/ahp
```

**Output:**
- Bobot kriteria W = [0.24, 0.31, 0.12, 0.09, 0.11, 0.05, 0.04, 0.04]

#### **Step 4: Input Matriks Interdependensi ANP**

```bash
POST /api/anp-interdependencies/matrix
Content-Type: application/json

{
  "matrix": [
    [0.25, 0.00, 0.00, ...],
    ...
  ]
}
```

#### **Step 5: Hitung ANP (Bobot Kriteria Interdependen)**

```bash
POST /api/calculate/anp
```

**Output:**
- Bobot ANP untuk digunakan dalam WP

---

### **FASE 2: Penilaian Individu Decision Maker**

Setiap DM melakukan penilaian secara independen.

#### **Decision Maker 1 (Bobot DM = 0.3)**

##### **Step 6.1: Register Decision Maker 1**

```bash
POST /api/decision-makers
Content-Type: application/json

{
  "name": "DM 1",
  "weight": 0.3
}
```

**Response:** `{ "id": 1, ... }`

##### **Step 6.2: DM 1 Memberikan Rating Alternatif**

```bash
POST /api/alternative-ratings/matrix?dm_id=1
Content-Type: application/json

{
  "decision_maker_id": 1,
  "matrix": [
    [5, 4, 5, 2, 3, 3, 2, 2],  // A1
    [4, 3, 2, 5, 2, 4, 3, 4],  // A2
    [5, 5, 3, 3, 2, 4, 1, 4],  // A3
    [3, 4, 5, 3, 3, 2, 4, 3],  // A4
    [4, 3, 5, 4, 2, 3, 3, 3]   // A5
  ]
}
```

##### **Step 6.3: Hitung WP untuk DM 1**

```bash
POST /api/calculate/wp?dm_id=1
```

**Output:**
```json
{
  "decision_maker_id": 1,
  "rankings": [5, 2, 1, 4, 3],  // Ranking: A3=1, A2=2, A5=3, A4=4, A1=5
  "borda_points": [1, 4, 5, 2, 3]  // Konversi ke poin Borda
}
```

##### **Step 6.4: Simpan Poin Borda DM 1**

```bash
POST /api/borda-points/bulk
Content-Type: application/json

{
  "points": [
    {"decision_maker_id": 1, "alternative_id": 1, "points": 5},
    {"decision_maker_id": 1, "alternative_id": 2, "points": 1},
    {"decision_maker_id": 1, "alternative_id": 3, "points": 3},
    {"decision_maker_id": 1, "alternative_id": 4, "points": 4},
    {"decision_maker_id": 1, "alternative_id": 5, "points": 2}
  ]
}
```

---

#### **Decision Maker 2 (Bobot DM = 0.5)**

##### **Step 7.1: Register DM 2**

```bash
POST /api/decision-makers
Content-Type: application/json

{
  "name": "DM 2",
  "weight": 0.5
}
```

##### **Step 7.2: DM 2 Memberikan Rating**

```bash
POST /api/alternative-ratings/matrix?dm_id=2
Content-Type: application/json

{
  "decision_maker_id": 2,
  "matrix": [
    [4, 5, 4, 3, 4, 2, 3, 3],  // A1 (rating berbeda dari DM1)
    [3, 4, 3, 4, 3, 3, 4, 3],  // A2
    [5, 5, 4, 5, 3, 3, 2, 5],  // A3
    [3, 3, 4, 2, 2, 3, 3, 2],  // A4
    [2, 3, 3, 3, 2, 4, 2, 3]   // A5
  ]
}
```

##### **Step 7.3: Hitung WP untuk DM 2**

```bash
POST /api/calculate/wp?dm_id=2
```

##### **Step 7.4: Simpan Poin Borda DM 2**

```bash
# Berdasarkan ranking WP DM 2
POST /api/borda-points/bulk
```

---

#### **Decision Maker 3 (Bobot DM = 1.0)**

Ulangi langkah yang sama untuk DM 3.

---

### **FASE 3: Agregasi Keputusan Kelompok**

#### **Step 8: Hitung BORDA (Final Group Decision)**

```bash
POST /api/calculate/borda
```

**Rumus:**
```
V_Borda(A_i) = Σ (P_ik × w_k)

Contoh A3:
V_Borda(A3) = (3 × 0.3) + (5 × 0.5) + (5 × 1.0)
             = 0.9 + 2.5 + 5.0
             = 8.4 ✓
```

**Output:**
```json
{
  "alternatives": [
    {
      "code": "A3",
      "name": "Bekonang",
      "borda_score": 8.4,
      "rank": 1
    },
    {
      "code": "A1",
      "name": "Gentan",
      "borda_score": 7.5,
      "rank": 2
    },
    ...
  ]
}
```

---

## Modifikasi yang Diperlukan

Untuk mendukung multi-DM workflow, sistem perlu modifikasi:

### **1. Tambah Kolom `decision_maker_id` di Tabel `alternative_ratings`**

```php
// Migration
Schema::table('alternative_ratings', function (Blueprint $table) {
    $table->foreignId('decision_maker_id')
          ->nullable()
          ->constrained('decision_makers')
          ->onDelete('cascade');
});
```

### **2. Update Endpoint WP untuk Support Multi-DM**

```php
// CalculationController.php
public function calculateWP(Request $request)
{
    $dmId = $request->query('dm_id');
    
    if ($dmId) {
        // Hitung WP untuk DM tertentu
        $result = $this->wpService->processWPForDM($dmId);
    } else {
        // Hitung WP menggunakan rating agregat (rata-rata semua DM)
        $result = $this->wpService->processWP();
    }
    
    return response()->json([
        'success' => true,
        'data' => $result,
    ]);
}
```

### **3. Update WeightedProductService**

```php
// WeightedProductService.php
public function processWPForDM($dmId): array
{
    // Ambil rating untuk DM tertentu
    $ratingsMatrix = $this->buildRatingsMatrixForDM($dmId);
    
    // Hitung WP
    $result = $this->calculateWP($ratingsMatrix, $anpWeights, $criteriaTypes);
    
    // Konversi ranking ke poin Borda
    $result['borda_points'] = $this->convertRankingsToBordaPoints($result['rankings']);
    
    return $result;
}

private function buildRatingsMatrixForDM($dmId): array
{
    $alternatives = Alternative::orderBy('id')->get();
    $criteria = Criteria::orderBy('id')->get();

    $matrix = [];
    foreach ($alternatives as $alternative) {
        $row = [];
        foreach ($criteria as $criterion) {
            $rating = AlternativeRating::where('alternative_id', $alternative->id)
                ->where('criteria_id', $criterion->id)
                ->where('decision_maker_id', $dmId)
                ->first();
            
            $row[] = $rating ? $rating->rating : 0;
        }
        $matrix[] = $row;
    }

    return $matrix;
}
```

---

## Perbedaan Antara Pendekatan

### **Pendekatan 1: Single Rating (Jurnal)**
- 1 set rating untuk semua alternatif
- WP dihitung 1 kali
- Poin Borda langsung dari jurnal (Tabel 6)
- **Cocok untuk**: Validasi hasil jurnal

### **Pendekatan 2: Multi DM (Real GDSS)**
- Setiap DM memberikan rating berbeda
- WP dihitung per DM
- Ranking WP dikonversi ke poin Borda
- Agregasi dengan BORDA
- **Cocok untuk**: Aplikasi real-world

---

## Workflow Sederhana (Menggunakan Data Jurnal)

Jika menggunakan data dari jurnal (seperti sekarang):

```bash
# 1. Setup database
php artisan migrate:fresh --seed

# 2. Hitung semua (AHP → ANP → WP → BORDA)
POST /api/calculate/all

# 3. Lihat hasil akhir
GET /api/results/final-ranking
```

**Kenapa langsung bisa?**
- Seeder sudah menyediakan rating alternatif (Tabel 4)
- Poin Borda sudah tersedia (Tabel 6)
- Sistem menghitung dengan data yang sudah ada

---

## Workflow Multi-DM (Aplikasi Real)

```bash
# FASE 1: Setup Bersama
POST /api/pairwise-comparisons/matrix
POST /api/anp-interdependencies/matrix
POST /api/calculate/ahp
POST /api/calculate/anp

# FASE 2: DM 1
POST /api/decision-makers (DM 1, weight=0.3)
POST /api/alternative-ratings/matrix?dm_id=1 (rating DM 1)
POST /api/calculate/wp?dm_id=1
POST /api/borda-points/bulk (simpan poin dari ranking WP)

# FASE 3: DM 2
POST /api/decision-makers (DM 2, weight=0.5)
POST /api/alternative-ratings/matrix?dm_id=2
POST /api/calculate/wp?dm_id=2
POST /api/borda-points/bulk

# FASE 4: DM 3
POST /api/decision-makers (DM 3, weight=1.0)
POST /api/alternative-ratings/matrix?dm_id=3
POST /api/calculate/wp?dm_id=3
POST /api/borda-points/bulk

# FASE 5: Agregasi
POST /api/calculate/borda
GET /api/results/final-ranking
```

---

## Tabel Perbandingan Data

### **Data Rating (Tabel 4 - Jurnal)**
Hanya 1 set rating, digunakan untuk semua DM:

| Alt | KT | BB | II | LPD | ST | BPT | SPL | VM |
|-----|----|----|----|----|----|----|-----|-----|
| A1  | 5  | 4  | 5  | 2  | 3  | 3  | 2   | 2   |
| A2  | 4  | 3  | 2  | 5  | 2  | 4  | 3   | 4   |
| A3  | 5  | 5  | 3  | 3  | 2  | 4  | 1   | 4   |

### **Poin Borda (Tabel 6 - Jurnal)**
DM langsung memberikan poin, bukan dari WP:

| DM  | A1 | A2 | A3 | A4 | A5 |
|-----|----|----|----|----|-----|
| DM1 | 5  | 1  | 3  | 4  | 2   |
| DM2 | 4  | 3  | 5  | 2  | 1   |
| DM3 | 4  | 2  | 5  | 1  | 2   |

**Dalam jurnal, poin Borda tidak berasal dari ranking WP!**

---

## Kesimpulan

### **Skenario Jurnal (Saat Ini)**
1. AHP → ANP → WP → BORDA
2. Rating 1 set (Tabel 4)
3. Poin Borda manual (Tabel 6)
4. **Output: A3 = 8.4 ✓**

### **Skenario Multi-DM (Ideal GDSS)**
1. AHP → ANP (shared)
2. Setiap DM: Rating → WP → Ranking → Poin Borda
3. BORDA agregasi
4. **Output: Tergantung rating DM**

Untuk mengimplementasikan **true multi-DM**, perlu modifikasi tabel dan service seperti yang saya jelaskan di atas. Namun untuk **validasi jurnal**, sistem saat ini sudah benar karena mengikuti data jurnal.

---

**Apakah Anda ingin saya implementasikan modifikasi untuk true multi-DM workflow?** 🤔
