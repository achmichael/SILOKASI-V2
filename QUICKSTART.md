# 🚀 Quick Start Guide - GDSS Application

## Aplikasi Group Decision Support System
### Pemilihan Lokasi Perumahan dengan Metode ANP-WP-BORDA

---

## ⚡ Quick Setup (5 Menit)

### 1. Prerequisites Check
```bash
# Pastikan semua tools ter-install
node --version   # v18 atau lebih baru
npm --version    # v9 atau lebih baru
php --version    # 8.2 atau lebih baru
composer --version
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Setup Database
```bash
# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Edit .env - sesuaikan database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gdss_db
DB_USERNAME=root
DB_PASSWORD=

# Run migrations dengan seeder jurnal
php artisan migrate:fresh --seed
```

### 4. Build Frontend
```bash
# Development build (dengan hot reload)
npm run dev

# ATAU Production build
npm run build
```

### 5. Start Server
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (jika pakai npm run dev)
npm run dev

# Akses aplikasi:
# http://localhost:8000
```

---

## 🎯 Workflow Penggunaan (Step by Step)

### Phase 1: PERSIAPAN DATA (5-10 menit)

#### ✅ Step 1: Setup Criteria
1. Klik menu **"Criteria"**
2. Klik tombol **"Add Criteria"**
3. Isi data 8 criteria:
   ```
   C1 - Lokasi Strategis (Benefit)
   C2 - Harga Tanah (Cost)
   C3 - Sarana Transportasi (Benefit)
   C4 - Fasilitas Umum (Benefit)
   C5 - Potensi Banjir (Cost)
   C6 - Kondisi Lingkungan (Benefit)
   C7 - Akses Jalan (Benefit)
   C8 - Keamanan (Benefit)
   ```
4. Klik **"Save Criteria"**
5. Ulangi untuk semua 8 criteria

#### ✅ Step 2: Setup Alternatives
1. Klik menu **"Alternatives"**
2. Klik tombol **"Add Alternative"**
3. Isi data 5 alternatif lokasi:
   ```
   A1 - Gentan
   A2 - Palur
   A3 - Bekonang
   A4 - Ngemplak
   A5 - Baturetno
   ```
4. Klik **"Save Alternative"**
5. Ulangi untuk semua 5 alternatives

#### ✅ Step 3: Setup Decision Makers
1. Klik menu **"Decision Makers"**
2. Klik tombol **"Add Decision Maker"**
3. Isi data 3 DM:
   ```
   DM 1 - Weight: 0.3 (30%)
   DM 2 - Weight: 0.5 (50%)
   DM 3 - Weight: 1.0 (100%)
   ```
4. Klik **"Save Decision Maker"**
5. Ulangi untuk semua 3 DM

**💡 Tip**: Gunakan data seeder yang sudah ada dengan command:
```bash
php artisan migrate:fresh --seed
```

---

### Phase 2: INPUT MATRIKS (10-15 menit)

#### ✅ Step 4: AHP Pairwise Comparison
1. Klik menu **"Step 1: AHP Matrix"**
2. Isi matriks perbandingan 8x8 (criteria vs criteria)
3. Gunakan skala Saaty (1-9):
   - **1** = Sama penting
   - **3** = Sedikit lebih penting
   - **5** = Lebih penting
   - **7** = Sangat penting
   - **9** = Mutlak lebih penting
4. Klik **"Save Matrix"**
5. Klik **"Calculate AHP"**
6. Cek Consistency Ratio (CR harus < 0.1)

**Contoh input baris pertama (C1 vs semua):**
```
C1 vs C1 = 1 (diagonal, auto-filled)
C1 vs C2 = 2
C1 vs C3 = 3
C1 vs C4 = 4
... dst
```

#### ✅ Step 5: ANP Interdependency Matrix
1. Klik menu **"Step 2: ANP Matrix"**
2. Isi matriks interdependency 8x8
3. Nilai 0-1 (desimal, contoh: 0.5)
4. Klik **"Save Matrix"**
5. Klik **"Calculate ANP"**

**Contoh input:**
```
C1 ke C2 = 0.1
C1 ke C3 = 0.2
... dst
```

#### ✅ Step 6: Alternative Ratings (Per DM)
1. Klik menu **"Step 3: Ratings"**
2. **Pilih Decision Maker** dari dropdown (DM 1)
3. Isi rating untuk setiap alternatif di setiap criteria:
   - Skala: 1-5
   - 1 = Sangat buruk
   - 3 = Cukup
   - 5 = Sangat baik
4. Klik **"Save Ratings for DM 1"**
5. **Ulangi untuk DM 2 dan DM 3**

**Contoh matriks rating DM 1 (5 alternatif × 8 criteria):**
```
        C1  C2  C3  C4  C5  C6  C7  C8
A1      3   4   3   3   3   3   3   4
A2      3   3   3   3   4   3   3   3
A3      4   5   4   4   3   4   4   4
A4      2   3   2   2   2   2   3   3
A5      2   2   2   3   2   3   2   2
```

---

### Phase 3: KALKULASI & HASIL (1 menit)

#### ✅ Step 7: Run Calculation
1. Klik menu **"Step 4: Results"**
2. Klik tombol besar **"Calculate All"** (hijau)
3. Tunggu proses (2-3 detik)
4. Muncul notifikasi sukses

#### ✅ Step 8: View Results
Klik tab untuk melihat hasil:

**📊 Tab "Final Ranking"**
- Lihat pemenang (biasanya A3 - Bekonang dengan score 8.4)
- Bar chart perbandingan
- Ranking lengkap 1-5

**📈 Tab "AHP Results"**
- Bobot criteria hasil AHP
- Consistency Ratio
- Eigenvektor

**📉 Tab "ANP Results"**
- Bobot criteria setelah interdependency
- Perbandingan AHP vs ANP weights

**📊 Tab "WP Results"**
- Vector S dan Vector V per alternatif
- Preference score (0-1)
- Ranking berdasarkan WP

**👥 Tab "BORDA Results"**
- Skor final agregasi multi-DM
- Detail kontribusi setiap DM
- Vector V, Ranking, dan Borda Points per DM

---

## 🎨 Fitur UI/UX

### Responsive Design
- ✅ **Mobile**: Sidebar collapsible, touch-friendly
- ✅ **Tablet**: Layout 2 kolom
- ✅ **Desktop**: Sidebar fixed, multi-column

### Dark Mode
- Auto-detect system preference
- Toggle manual (jika diimplementasikan)

### Interactive Elements
- Hover effects pada tombol dan cards
- Smooth transitions
- Loading spinners
- Success/error notifications (SweetAlert2)
- Animated charts (Chart.js)

### Navigation
- Breadcrumbs di header
- Active state menu
- Step indicators
- Quick actions di dashboard

---

## 📊 Expected Output (Validation)

Jika menggunakan data seeder, hasil yang diharapkan:

```
🏆 FINAL RANKING:
1. A3 - Bekonang     Score: 8.40
2. A1 - Gentan       Score: 7.50
3. A2 - Palur        Score: 3.80
4. A4 - Ngemplak     Score: 3.20
5. A5 - Baturetno    Score: 3.10

✅ AHP Weights (sample):
C1: 0.24  C2: 0.31  C3: 0.12  C4: 0.09
C5: 0.11  C6: 0.05  C7: 0.04  C8: 0.04

✅ ANP Weights (sample):
C1: 0.06  C2: 0.05  C3: 0.02  C4: 0.26
C5: 0.09  C6: 0.12  C7: 0.05  C8: 0.01
```

---

## 🐛 Troubleshooting

### Problem: "No data found"
**Solution:**
```bash
# Re-run seeder
php artisan migrate:fresh --seed
```

### Problem: "API Error 404"
**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Problem: "Styles not loading"
**Solution:**
```bash
# Rebuild assets
npm run build
# Atau restart dev server
npm run dev
```

### Problem: "Consistency Ratio > 0.1"
**Solution:**
- Review matriks AHP
- Pastikan konsistensi perbandingan
- Contoh inkonsisten: C1>C2 (nilai 5), C2>C3 (nilai 3), tapi C1>C3 (nilai 2)
- Seharusnya: C1>C3 ≈ 5×3 = 15

### Problem: "Database connection failed"
**Solution:**
```bash
# Check .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=gdss_db

# Create database manually
mysql -u root -p
CREATE DATABASE gdss_db;
exit;

# Migrate again
php artisan migrate:fresh --seed
```

---

## 🎓 Tips & Best Practices

### Data Input
1. **Criteria**: Minimal 3, maksimal 15 (optimal 5-9)
2. **Alternatives**: Minimal 2, maksimal 20 (optimal 3-7)
3. **Decision Makers**: Minimal 1, maksimal 10 (optimal 3-5)

### AHP Matrix
- Isi **hanya segitiga atas** matrix
- Diagonal otomatis = 1
- Segitiga bawah = 1/nilai_atas

### Consistency
- CR < 0.1 = Konsisten ✅
- CR 0.1-0.15 = Borderline ⚠️
- CR > 0.15 = Tidak konsisten ❌

### Weight Distribution
- Total weight DM tidak harus = 1
- Nilai relatif (misal: 0.3, 0.5, 1.0)
- Weight lebih besar = pengaruh lebih kuat

---

## 📱 Keyboard Shortcuts (Opsional)

```
Ctrl + K     : Search/Navigate
Ctrl + S     : Save form
Esc          : Close modal
Tab          : Next field
Shift + Tab  : Previous field
```

---

## 📈 Performance Metrics

### Loading Times (Typical)
- Dashboard: < 1s
- Criteria list: < 500ms
- Matrix input: < 1s
- Calculate All: 2-5s
- Results display: < 1s

### Data Limits
- Max criteria: 15
- Max alternatives: 20
- Max DM: 10
- Max matrix size: 15×15

---

## 🔐 Security Notes

- CSRF protection enabled
- Input validation (client & server)
- SQL injection prevention
- XSS protection
- Rate limiting (API)

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check dokumentasi lengkap: `README_FRONTEND.md`
2. Check API docs: `API_DOCUMENTATION.md`
3. Check workflow: `MULTI_DM_WORKFLOW.md`

---

**Selamat menggunakan GDSS! 🎉**

*Developed with ❤️ using Laravel + Tailwind CSS*
