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
        for ($i=1; $i<=3; $i++) {
            $dmUsers[] = User::create([
                'name' => "DM $i",
                'email' => "dm$i@example.com",
                'password' => bcrypt('password'), // Sebaiknya gunakan Hash::make()
                'role' => 'decision_maker'
            ]);
        }

        User::create([
            'name' => "Admin",
            'email' => "admin@example.com",
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);


        /* ============================================================
         * 4. PAIRWISE COMPARISONS
         * ========================================================== */

        $pairwiseBase = [
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

        foreach ($dmUsers as $dm) {

            foreach ($pairwiseBase as $row) {
                // MODIFIED: Langsung menggunakan nilai base ($row[2]) untuk semua user
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
         * ========================================================== */

        $anpBase = [
            [0.25,0.00,0.00,0.00,0.00,0.00,0.00,0.00],
            [0.00,0.17,0.00,0.00,0.00,0.00,0.00,0.00],
            [0.00,0.00,0.14,0.00,0.00,0.00,0.00,0.00],
            [0.00,0.50,0.29,1.00,0.50,0.00,0.00,0.75],
            [0.00,0.00,0.00,0.00,0.13,0.00,0.67,0.00],
            [0.75,0.00,0.57,0.00,0.00,1.00,0.00,0.00],
            [0.00,0.33,0.00,0.00,0.38,0.00,0.33,0.00],
            [0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.25],
        ];

        foreach ($dmUsers as $dm) {

            for ($i=0; $i<8; $i++) {
                for ($j=0; $j<8; $j++) {

                    // MODIFIED: Langsung ambil nilai dari base matrix tanpa random & tanpa normalisasi ulang
                    $exactValue = $anpBase[$i][$j];

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
         * 6. Alternative Ratings (Tabel 4)
         * ========================================================== */

        $ratingsMatrix = [
            [5,4,5,2,3,3,2,2],
            [4,3,2,5,2,4,3,4],
            [5,5,3,3,2,4,1,4],
            [3,4,5,3,3,2,4,3],
            [4,3,5,4,2,3,3,3],
        ];

        foreach ($dmUsers as $dm) {
            for ($i=0; $i<5; $i++) {
                for ($j=0; $j<8; $j++) {
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
         * 7. BORDA POINTS (Tabel 6)
         * ========================================================== */

        $bordaPoints = [
            [5,1,3,4,2], // DM 1
            [4,3,5,2,1], // DM 2
            [4,2,5,1,2], // DM 3
        ];

        foreach ($dmUsers as $idx => $dm) {
            for ($i=0; $i<5; $i++) {
                BordaPoint::create([
                    'user_id' => $dm->id,
                    'alternative_id' => $alternativesList[$i]->id,
                    'points' => $bordaPoints[$idx][$i]
                ]);
            }
        }

        $this->command->info("Journal Data Seeder berhasil dijalankan (Fixed Values)!");
    }
}