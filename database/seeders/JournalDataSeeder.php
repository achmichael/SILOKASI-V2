<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\PairwiseComparison;
use App\Models\AnpInterdependency;
use App\Models\AlternativeRating;
use App\Models\User;
use App\Models\BordaPoint;
use App\Services\AhpService;
use App\Services\AnpService;

class JournalDataSeeder extends Seeder
{
    public function run(): void
    {
        $ahpService = app(AhpService::class);
        $anpService = app(AnpService::class);

        /* ============================================================
         * 1. Seed Criteria
         * ========================================================== */
        $criteria = [
            ['code' => 'KT', 'name' => 'Kedekatan Tempat', 'type' => 'benefit'],
            ['code' => 'BB', 'name' => 'Biaya Bangunan', 'type' => 'benefit'],
            ['code' => 'II', 'name' => 'Infrastruktur dan Indeks', 'type' => 'benefit'],
            ['code' => 'LPD', 'name' => 'Legalitas Perizinan Daerah', 'type' => 'benefit'],
            ['code' => 'ST', 'name' => 'Sistem Transportasi', 'type' => 'benefit'],
            ['code' => 'BPT', 'name' => 'Biaya Pajak Tanah', 'type' => 'cost'],
            ['code' => 'SPL', 'name' => 'Sarana dan Prasarana Lingkungan', 'type' => 'benefit'],
            ['code' => 'VM', 'name' => 'Visi dan Misi', 'type' => 'benefit'],
        ];

        // Kosongkan tabel jika perlu, atau gunakan firstOrCreate
        // Criteria::truncate(); 
        foreach ($criteria as $c) Criteria::create($c);
        $criteriaList = Criteria::orderBy('id')->get();
        $criteriaMap  = Criteria::pluck('id','code')->toArray();


        /* ============================================================
         * 2. Seed Alternatives
         * ========================================================== */
        $alternatives = [
            ['code' => 'A1', 'name' => 'Gentan'],
            ['code' => 'A2', 'name' => 'Palur Raya'],
            ['code' => 'A3', 'name' => 'Bekonang'],
            ['code' => 'A4', 'name' => 'Makamhaji'],
            ['code' => 'A5', 'name' => 'Baturetno'],
        ];

        foreach ($alternatives as $alt) Alternative::create($alt);
        $alternativesList = Alternative::orderBy('id')->get();


        /* ============================================================
         * 3. Seed Users (DM1, DM2, DM3)
         * ========================================================== */
        $dmUsers = [];
        $roles = ['land_geotech', 'infrastructure', 'manager'];
        for ($i=1; $i<=3; $i++) {
            $dmUsers[] = User::create([
                'name' => "DM $i",
                'email' => "dm$i@example.com",
                'password' => bcrypt('password'), // Sebaiknya gunakan Hash::make()
                'role' => $roles[$i-1]
            ]);
        }

        User::create([
            'name' => "Admin",
            'email' => "admin@example.com",
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);


        /* ============================================================
         * 4. PAIRWISE COMPARISONS (Skala Saaty: 1-9 dan kebalikannya)
         * ========================================================== */

        // DM1 - Data base (dari jurnal asli)
        $pairwiseDM1 = [
            ['KT','BB',1.00], ['KT','II',3.00], ['KT','LPD',3.00], ['KT','ST',3.00], 
            ['KT','BPT',4.00], ['KT','SPL',5.00], ['KT','VM',3.00],

            ['BB','II',5.00], ['BB','LPD',7.00], ['BB','ST',2.00], 
            ['BB','BPT',7.00], ['BB','SPL',5.00], ['BB','VM',5.00],

            ['II','LPD',2.00], ['II','ST',3.00], ['II','BPT',4.00], 
            ['II','SPL',2.00], ['II','VM',2.00],

            ['LPD','ST',1.00], ['LPD','BPT',2.00], ['LPD','SPL',3.00], ['LPD','VM',3.00],

            ['ST','BPT',4.00], ['ST','SPL',3.00], ['ST','VM',5.00],

            ['BPT','SPL',2.00], ['BPT','VM',3.00],

            ['SPL','VM',2.00],
        ];

        // DM2 - Variasi (prioritas: BB > KT > ST > II > LPD > SPL > BPT > VM)
        $pairwiseDM2 = [
            ['KT','BB',1/2], ['KT','II',2.00], ['KT','LPD',3.00], ['KT','ST',1/2], 
            ['KT','BPT',4.00], ['KT','SPL',3.00], ['KT','VM',5.00],

            ['BB','II',4.00], ['BB','LPD',5.00], ['BB','ST',2.00], 
            ['BB','BPT',6.00], ['BB','SPL',5.00], ['BB','VM',7.00],

            ['II','LPD',2.00], ['II','ST',1/3], ['II','BPT',3.00], 
            ['II','SPL',2.00], ['II','VM',4.00],

            ['LPD','ST',1/4], ['LPD','BPT',2.00], ['LPD','SPL',1.00], ['LPD','VM',2.00],

            ['ST','BPT',5.00], ['ST','SPL',4.00], ['ST','VM',6.00],

            ['BPT','SPL',1/2], ['BPT','VM',2.00],

            ['SPL','VM',3.00],
        ];

        // DM3 - Variasi (prioritas: KT > LPD > BB > BPT > II > ST > VM > SPL)
        $pairwiseDM3 = [
            ['KT','BB',2.00], ['KT','II',4.00], ['KT','LPD',1.00], ['KT','ST',5.00], 
            ['KT','BPT',3.00], ['KT','SPL',7.00], ['KT','VM',6.00],

            ['BB','II',3.00], ['BB','LPD',1/2], ['BB','ST',4.00], 
            ['BB','BPT',2.00], ['BB','SPL',5.00], ['BB','VM',4.00],

            ['II','LPD',1/3], ['II','ST',2.00], ['II','BPT',1/2], 
            ['II','SPL',3.00], ['II','VM',2.00],

            ['LPD','ST',4.00], ['LPD','BPT',2.00], ['LPD','SPL',6.00], ['LPD','VM',5.00],

            ['ST','BPT',1/3], ['ST','SPL',2.00], ['ST','VM',1.00],

            ['BPT','SPL',4.00], ['BPT','VM',3.00],

            ['SPL','VM',1/2],
        ];

        $pairwiseMatrices = [$pairwiseDM1, $pairwiseDM2, $pairwiseDM3];

        foreach ($dmUsers as $idx => $dm) {
            $pairwiseData = $pairwiseMatrices[$idx];

            foreach ($pairwiseData as $row) {
                PairwiseComparison::create([
                    'user_id' => $dm->id,
                    'criteria_i' => $criteriaMap[$row[0]],
                    'criteria_j' => $criteriaMap[$row[1]],
                    'value' => $row[2] 
                ]);
            }

            // Process AHP untuk DM
            $ahpService->processAHP($dm->id);
        }


        /* ============================================================
         * 5. ANP INTERDEPENDENCY MATRIX
         * Setiap kolom harus berjumlah 1.0 (normalized) atau 0 jika tidak ada ketergantungan
         * ========================================================== */

        // DM1 - Data base ANP (dari jurnal asli)
        // Kolom: KT, BB, II, LPD, ST, BPT, SPL, VM
        $anpDM1 = [
            [0.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],  // KT
            [0.00, 0.17, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],  // BB
            [0.00, 0.00, 0.14, 0.00, 0.00, 0.00, 0.00, 0.00],  // II
            [0.00, 0.50, 0.29, 1.00, 0.49, 0.00, 0.00, 0.75],  // LPD
            [0.00, 0.00, 0.00, 0.00, 0.13, 0.00, 0.67, 0.00],  // ST
            [0.75, 0.00, 0.57, 0.00, 0.00, 1.00, 0.00, 0.00],  // BPT
            [0.00, 0.33, 0.00, 0.00, 0.38, 0.00, 0.33, 0.00],  // SPL
            [0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.25],  // VM
        ];

        // DM2 - Variasi ANP (fokus berbeda pada interdependensi)
        // Kolom harus berjumlah 1.0
        $anpDM2 = [
            [0.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],  // KT
            [0.00, 0.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],  // BB
            [0.00, 0.00, 0.20, 0.00, 0.00, 0.00, 0.00, 0.00],  // II
            [0.00, 0.35, 0.40, 1.00, 0.35, 0.00, 0.00, 0.65],  // LPD
            [0.00, 0.00, 0.00, 0.00, 0.20, 0.00, 0.55, 0.00],  // ST
            [0.70, 0.00, 0.40, 0.00, 0.00, 1.00, 0.00, 0.00],  // BPT
            [0.00, 0.40, 0.00, 0.00, 0.45, 0.00, 0.45, 0.00],  // SPL
            [0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.35],  // VM
        ];

        // DM3 - Variasi ANP (fokus berbeda pada interdependensi)
        // Kolom harus berjumlah 1.0
        $anpDM3 = [
            [0.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],  // KT
            [0.00, 0.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],  // BB
            [0.00, 0.00, 0.15, 0.00, 0.00, 0.00, 0.00, 0.00],  // II
            [0.00, 0.45, 0.25, 1.00, 0.55, 0.00, 0.00, 0.80],  // LPD
            [0.00, 0.00, 0.00, 0.00, 0.10, 0.00, 0.75, 0.00],  // ST
            [0.80, 0.00, 0.60, 0.00, 0.00, 1.00, 0.00, 0.00],  // BPT
            [0.00, 0.35, 0.00, 0.00, 0.35, 0.00, 0.25, 0.00],  // SPL
            [0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.20],  // VM
        ];

        $anpMatrices = [$anpDM1, $anpDM2, $anpDM3];

        foreach ($dmUsers as $idx => $dm) {
            $anpData = $anpMatrices[$idx];

            for ($i=0; $i<8; $i++) {
                for ($j=0; $j<8; $j++) {
                    $exactValue = $anpData[$i][$j];

                    AnpInterdependency::create([
                        'user_id' => $dm->id,
                        'criteria_i' => $criteriaList[$i]->id,
                        'criteria_j' => $criteriaList[$j]->id,
                        'value' => $exactValue,
                    ]);
                }
            }

            $anpService->processANP($dm->id);
        }


        /* ============================================================
         * 6. Alternative Ratings - Berbeda untuk setiap DM
         * Skala 1-5 (1=Sangat Buruk, 5=Sangat Baik)
         * Kriteria: KT, BB, II, LPD, ST, BPT(cost), SPL, VM
         * ========================================================== */

        // DM1 - Rating base (dari jurnal asli)
        $ratingsDM1 = [
            [5, 4, 5, 2, 3, 3, 2, 2],  // A1 Gentan
            [4, 3, 2, 5, 2, 4, 3, 4],  // A2 Palur Raya
            [5, 5, 3, 3, 2, 4, 1, 4],  // A3 Bekonang
            [3, 4, 5, 3, 3, 2, 4, 3],  // A4 Makamhaji
            [4, 3, 5, 4, 2, 3, 3, 3],  // A5 Baturetno
        ];

        // DM2 - Rating variasi (prioritas berbeda: A2 dan A3 lebih bagus)
        $ratingsDM2 = [
            [4, 3, 4, 3, 2, 4, 3, 2],  // A1 Gentan
            [5, 4, 3, 5, 4, 3, 4, 5],  // A2 Palur Raya - lebih tinggi
            [5, 5, 4, 4, 3, 3, 2, 4],  // A3 Bekonang - lebih tinggi
            [3, 3, 4, 2, 3, 3, 3, 3],  // A4 Makamhaji
            [3, 4, 5, 3, 2, 4, 4, 2],  // A5 Baturetno
        ];

        // DM3 - Rating variasi (prioritas berbeda: A1 dan A5 lebih bagus)
        $ratingsDM3 = [
            [5, 5, 5, 3, 4, 2, 3, 3],  // A1 Gentan - lebih tinggi
            [3, 3, 2, 4, 2, 5, 2, 3],  // A2 Palur Raya
            [4, 4, 3, 3, 2, 4, 2, 4],  // A3 Bekonang
            [4, 3, 4, 4, 3, 3, 4, 3],  // A4 Makamhaji
            [5, 4, 5, 5, 3, 2, 4, 4],  // A5 Baturetno - lebih tinggi
        ];

        $ratingsMatrices = [$ratingsDM1, $ratingsDM2, $ratingsDM3];

        foreach ($dmUsers as $idx => $dm) {
            $ratingsMatrix = $ratingsMatrices[$idx];
            for ($i = 0; $i < 5; $i++) {
                for ($j = 0; $j < 8; $j++) {
                    AlternativeRating::create([
                        'user_id' => $dm->id,
                        'alternative_id' => $alternativesList[$i]->id,
                        'criteria_id' => $criteriaList[$j]->id,
                        'rating' => $ratingsMatrix[$i][$j],
                    ]);
                }
            }
        }


        /* ============================================================
         * 7. BORDA POINTS
         * Catatan: Borda Points akan dihitung otomatis dari hasil WP
         * saat menjalankan Calculate All di aplikasi.
         * Data di bawah hanya untuk initial seed jika diperlukan.
         * ========================================================== */

        // Borda Points akan di-generate dari WP Rankings
        // Tidak perlu seed manual karena akan ditimpa saat Calculate All
        // Jika ingin test tanpa Calculate All, uncomment kode di bawah:
        
        /*
        $bordaPoints = [
            [5, 1, 3, 4, 2], // DM 1
            [4, 3, 5, 2, 1], // DM 2
            [4, 2, 5, 1, 2], // DM 3
        ];

        foreach ($dmUsers as $idx => $dm) {
            for ($i = 0; $i < 5; $i++) {
                BordaPoint::create([
                    'user_id' => $dm->id,
                    'alternative_id' => $alternativesList[$i]->id,
                    'points' => $bordaPoints[$idx][$i]
                ]);
            }
        }
        */

        $this->command->info("Journal Data Seeder berhasil dijalankan!");
        $this->command->info("Catatan: Jalankan 'Calculate All' di aplikasi untuk menghitung WP dan Borda.");
    }
}