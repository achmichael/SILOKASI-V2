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

class JournalDataSeeder extends Seeder
{
    /**
     * Seed data sesuai Jurnal IJCCS Vol. 13, No. 4, Oktober 2019
     */
    public function run(): void
    {
        // 1. Seed Criteria (Tabel 1)
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

        foreach ($criteria as $criterion) {
            Criteria::create($criterion);
        }

        // 2. Seed Alternatives (Tabel 4)
        $alternatives = [
            ['code' => 'A1', 'name' => 'Gentan', 'description' => 'Lokasi Gentan'],
            ['code' => 'A2', 'name' => 'Palur Raya', 'description' => 'Lokasi Palur Raya'],
            ['code' => 'A3', 'name' => 'Bekonang', 'description' => 'Lokasi Bekonang'],
            ['code' => 'A4', 'name' => 'Makamhaji', 'description' => 'Lokasi Makamhaji'],
            ['code' => 'A5', 'name' => 'Baturetno', 'description' => 'Lokasi Baturetno'],
        ];

        foreach ($alternatives as $alternative) {
            Alternative::create($alternative);
        }

        // 3. Seed Decision Makers (Users with role decision_maker)
        $decisionMakers = [
            ['name' => 'DM 1', 'email' => 'dm1@example.com', 'password' => bcrypt('password'), 'role' => 'decision_maker'],
            ['name' => 'DM 2', 'email' => 'dm2@example.com', 'password' => bcrypt('password'), 'role' => 'decision_maker'],
            ['name' => 'DM 3', 'email' => 'dm3@example.com', 'password' => bcrypt('password'), 'role' => 'decision_maker'],
        ];

        foreach ($decisionMakers as $dm) {
            User::create($dm);
        }

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // // 4. Seed Pairwise Comparisons (Tabel 1)
        // $pairwiseData = [
        //     // KT row
        //     ['KT', 'BB', 1.00], ['KT', 'II', 3.00], ['KT', 'LPD', 3.00], ['KT', 'ST', 3.00], 
        //     ['KT', 'BPT', 4.00], ['KT', 'SPL', 5.00], ['KT', 'VM', 3.00],
        //     // BB row
        //     ['BB', 'II', 5.00], ['BB', 'LPD', 7.00], ['BB', 'ST', 2.00], 
        //     ['BB', 'BPT', 7.00], ['BB', 'SPL', 5.00], ['BB', 'VM', 5.00],
        //     // II row
        //     ['II', 'LPD', 2.00], ['II', 'ST', 3.00], ['II', 'BPT', 4.00], 
        //     ['II', 'SPL', 2.00], ['II', 'VM', 2.00],
        //     // LPD row
        //     ['LPD', 'ST', 1.00], ['LPD', 'BPT', 2.00], ['LPD', 'SPL', 3.00], ['LPD', 'VM', 3.00],
        //     // ST row
        //     ['ST', 'BPT', 4.00], ['ST', 'SPL', 3.00], ['ST', 'VM', 5.00],
        //     // BPT row
        //     ['BPT', 'SPL', 2.00], ['BPT', 'VM', 3.00],
        //     // SPL row
        //     ['SPL', 'VM', 2.00],
        // ];

        // $criteriaMap = Criteria::pluck('id', 'code')->toArray();
        
        // foreach ($pairwiseData as $data) {
        //     PairwiseComparison::create([
        //         'criteria_i' => $criteriaMap[$data[0]],
        //         'criteria_j' => $criteriaMap[$data[1]],
        //         'value' => $data[2],
        //     ]);
        // }

        // // 5. Seed ANP Interdependency Matrix (Tabel 3)
        // $anpMatrix = [
        //     [0.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00], // KT
        //     [0.00, 0.17, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00], // BB
        //     [0.00, 0.00, 0.14, 0.00, 0.00, 0.00, 0.00, 0.00], // II
        //     [0.00, 0.50, 0.29, 1.00, 0.50, 0.00, 0.00, 0.75], // LPD
        //     [0.00, 0.00, 0.00, 0.00, 0.13, 0.00, 0.67, 0.00], // ST
        //     [0.75, 0.00, 0.57, 0.00, 0.00, 1.00, 0.00, 0.00], // BPT
        //     [0.00, 0.33, 0.00, 0.00, 0.38, 0.00, 0.33, 0.00], // SPL
        //     [0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.25], // VM
        // ];

        // $criteriaList = Criteria::orderBy('id')->get();
        // for ($i = 0; $i < 8; $i++) {
        //     for ($j = 0; $j < 8; $j++) {
        //         AnpInterdependency::create([
        //             'criteria_i' => $criteriaList[$i]->id,
        //             'criteria_j' => $criteriaList[$j]->id,
        //             'value' => $anpMatrix[$i][$j],
        //         ]);
        //     }
        // }

        // // 6. Seed Alternative Ratings (Tabel 4)
        // $ratingsMatrix = [
        //     [5, 4, 5, 2, 3, 3, 2, 2], // A1
        //     [4, 3, 2, 5, 2, 4, 3, 4], // A2
        //     [5, 5, 3, 3, 2, 4, 1, 4], // A3
        //     [3, 4, 5, 3, 3, 2, 4, 3], // A4
        //     [4, 3, 5, 4, 2, 3, 3, 3], // A5
        // ];

        // $alternativesList = Alternative::orderBy('id')->get();
        // for ($i = 0; $i < 5; $i++) {
        //     for ($j = 0; $j < 8; $j++) {
        //         AlternativeRating::create([
        //             'alternative_id' => $alternativesList[$i]->id,
        //             'criteria_id' => $criteriaList[$j]->id,
        //             'rating' => $ratingsMatrix[$i][$j],
        //         ]);
        //     }
        // }

        // // 7. Seed Borda Points (Tabel 6) - Now using user_id instead of decision_maker_id
        // $bordaPointsMatrix = [
        //     [5, 1, 3, 4, 2], // DM 1: A1=5, A2=1, A3=3, A4=4, A5=2
        //     [4, 3, 5, 2, 1], // DM 2: A1=4, A2=3, A3=5, A4=2, A5=1
        //     [4, 2, 5, 1, 2], // DM 3: A1=4, A2=2, A3=5, A4=1, A5=2
        // ];

        // $dmList = User::decisionMakers()->orderBy('id')->get();
        // for ($k = 0; $k < 3; $k++) {
        //     for ($i = 0; $i < 5; $i++) {
        //         BordaPoint::create([
        //             'user_id' => $dmList[$k]->id,
        //             'alternative_id' => $alternativesList[$i]->id,
        //             'points' => $bordaPointsMatrix[$k][$i],
        //         ]);
        //     }
        // }

        $this->command->info('Journal data seeded successfully!');
    }
}
