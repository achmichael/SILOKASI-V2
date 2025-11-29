<?php
namespace App\Services;

use App\Models\Alternative;
use App\Models\AlternativeRating;
use App\Models\Criteria;
use Illuminate\Support\Facades\Log;

class WeightedProductService
{
    /**
     * Hitung Weighted Product (WP)
     *
     * @param array $ratingsMatrix Rating alternatif x kriteria (M x N)
     * @param array $weights Bobot kriteria dari ANP
     * @param array $criteriaTypes Type kriteria ('benefit' atau 'cost')
     * @return array ['scores' => [...], 'rankings' => [...]]
     */
    // public function calculateWP(array $ratingsMatrix, array $weights, array $criteriaTypes): array
    // {
    //     $m = count($ratingsMatrix); // Jumlah alternatif
    //     $n = count($weights);       // Jumlah kriteria

    //     $this->wp_log("Mulai perhitungan WP");
    //     $this->wp_log("Rating Matrix (Alternatif x Kriteria)", $ratingsMatrix);
    //     $this->wp_log("Weights ANP (Belum Normalisasi WP)", $weights);
    //     $this->wp_log("Criteria Types", $criteriaTypes);

    //     // Step 1: Hitung S_i (Vector S)
    //     $vectorS = [];
    //     for ($i = 0; $i < $m; $i++) {
    //         $s = 1.0;
    //         for ($j = 0; $j < $n; $j++) {
    //             $rating = $ratingsMatrix[$i][$j];
    //             $weight = $weights[$j];

    //             // Untuk cost, gunakan eksponen negatif
    //             if ($criteriaTypes[$j] === 'cost') {
    //                 $weight = -$weight;
    //             }

    //             // S_i = product of (X_ij ^ w_j)
    //             $s *= pow($rating, $weight);
    //         }
    //         $vectorS[] = $s;
    //     }

    //     // Step 2: Hitung V_i (Vector V - Normalisasi)
    //     $totalS = array_sum($vectorS);

    //     // Prevent division by zero
    //     if ($totalS == 0) {
    //         throw new \Exception('Total vector S is zero. Please check if ratings are provided.');
    //     }

    //     $vectorV = [];
    //     for ($i = 0; $i < $m; $i++) {
    //         $vectorV[] = round($vectorS[$i] / $totalS, 6);
    //     }

    //     // Step 3: Ranking berdasarkan nilai V (descending)
    //     $rankings = $this->getRankings($vectorV);

    //     return [
    //         'vector_s' => array_map(fn($s) => round($s, 6), $vectorS),
    //         'vector_v' => $vectorV,
    //         'rankings' => $rankings,
    //     ];
    // }

    public function calculateWP(array $ratingsMatrix, array $weights, array $criteriaTypes): array
    {
        $m = count($ratingsMatrix);
        $n = count($weights);

        $this->wp_log("Mulai perhitungan WP");
        $this->wp_log("Rating Matrix (Alternatif x Kriteria)", $ratingsMatrix);
        $this->wp_log("Weights ANP (Belum Normalisasi WP)", $weights);
        $this->wp_log("Criteria Types", $criteriaTypes);

        // Step 1: Hitung Vector S
        $vectorS = [];

        for ($i = 0; $i < $m; $i++) {
            $s = 1.0;
            $this->wp_log("Menghitung S untuk alternatif index $i");

            for ($j = 0; $j < $n; $j++) {
                $rating = $ratingsMatrix[$i][$j];
                $weight = $weights[$j];

                $this->wp_log("  Rating x_{$i}{$j} = $rating, weight w_j = $weight, type = {$criteriaTypes[$j]}");

                if ($criteriaTypes[$j] === 'cost') {
                    $weight = -$weight;
                    $this->wp_log("  Cost → weight negatif = $weight");
                }

                $powValue = pow($rating, $weight);
                $this->wp_log("  pow($rating, $weight) = $powValue");

                $s *= $powValue;
                $this->wp_log("  S sementara = $s");
            }

            $vectorS[] = $s;
            $this->wp_log("Final S_$i = $s");
        }

        $totalS = array_sum($vectorS);

        $this->wp_log("Total S = $totalS");

        if ($totalS == 0) {
            throw new \Exception("Total S = 0, tidak valid");
        }

        // Step 2: Hitung Vector V
        $vectorV = [];
        foreach ($vectorS as $i => $s) {
            $v         = round($s / $totalS, 6);
            $vectorV[] = $v;
            $this->wp_log("V_$i = S_$i / totalS = $v");
        }

        // Step 3: Ranking
        $rankings = $this->getRankings($vectorV);

        $this->wp_log("Ranking hasil WP", $rankings);

        return [
            'vector_s' => array_map(fn($s) => round($s, 6), $vectorS),
            'vector_v' => $vectorV,
            'rankings' => $rankings,
        ];
    }

    /**
     * Konversi nilai V menjadi ranking
     */
    private function getRankings(array $scores): array
    {
        $indexed = [];
        foreach ($scores as $index => $score) {
            $indexed[] = ['index' => $index, 'score' => $score];
        }

        // Sort descending
        usort($indexed, fn($a, $b) => $b['score'] <=> $a['score']);

        $rankings = array_fill(0, count($scores), 0);
        foreach ($indexed as $rank => $item) {
            $rankings[$item['index']] = $rank + 1; // Rank dimulai dari 1
        }

        return $rankings;
    }

    /**
     * Build matriks rating dari database (agregat dari semua DM)
     */
    public function buildRatingsMatrix(): array
    {
        $alternatives   = Alternative::orderBy('id')->get();
        $criteria       = Criteria::orderBy('id')->get();
        $decisionMakers = \App\Models\User::decisionMakers()->get();

        if ($decisionMakers->isEmpty()) {
            throw new \Exception('No decision makers found. Please ensure users with decision_maker role exist.');
        }

        $matrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($criteria as $criterion) {
                // Ambil rata-rata rating dari semua decision makers
                $ratings = AlternativeRating::where('alternative_id', $alternative->id)
                    ->where('criteria_id', $criterion->id)
                    ->whereIn('user_id', $decisionMakers->pluck('id'))
                    ->pluck('rating');

                // Jika ada rating, gunakan rata-rata; jika tidak ada, default 3 (nilai tengah)
                $avgRating = $ratings->isNotEmpty() ? $ratings->average() : 3;
                $row[]     = round($avgRating, 2);
            }
            $matrix[] = $row;
        }

        return $matrix;
    }

    private function wp_log($message, $data = null)
    {
        if ($data !== null) {
            Log::info("[WP] $message", ['data' => $data]);
        } else {
            Log::info("[WP] $message");
        }
    }

    /**
     * Build matriks rating untuk Decision Maker tertentu
     */
    public function buildRatingsMatrixForDM($dmId): array
    {
        $alternatives = Alternative::orderBy('id')->get();
        $criteria     = Criteria::orderBy('id')->get();

        $matrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($criteria as $criterion) {
                $rating = AlternativeRating::where('alternative_id', $alternative->id)
                    ->where('criteria_id', $criterion->id)
                    ->where('user_id', $dmId)
                    ->first();

                // Default to 3 (middle value) if no rating exists to avoid identical scores
                $row[] = $rating ? $rating->rating : 3;
            }
            $matrix[] = $row;
        }

        return $matrix;
    }

    /**
     * Proses Weighted Product dari database (agregat dari semua DM)
     */
    public function processWP(): array
    {
        // Ambil hasil ANP terakhir
        $anpResult = \App\Models\CalculationResult::where('method', 'ANP')
            ->latest('calculated_at')
            ->first();

        if (! $anpResult) {
            throw new \Exception('ANP calculation not found. Please run ANP first.');
        }

        $anpData    = $anpResult->data;
        $anpWeights = $anpData['weights'];

        // Build matriks rating (agregat dari semua DM)
        $ratingsMatrix = $this->buildRatingsMatrix();

        // Ambil tipe kriteria
        $criteria      = Criteria::orderBy('id')->get();
        $criteriaTypes = $criteria->pluck('type')->toArray();

        // Hitung WP
        $result = $this->calculateWP($ratingsMatrix, $anpWeights, $criteriaTypes);

        // Get decision makers info
        $decisionMakers = \App\Models\User::decisionMakers()->get();

        // Tambahkan mapping alternatif
        $alternatives           = Alternative::orderBy('id')->get();
        $result['alternatives'] = $alternatives->map(function ($alt, $index) use ($result) {
            return [
                'id'               => $alt->id,
                'code'             => $alt->code,
                'name'             => $alt->name,
                'vector_s'         => $result['vector_s'][$index],
                'vector_v'         => $result['vector_v'][$index],
                'preference_score' => $result['vector_v'][$index], // Alias untuk kejelasan
                'rank'             => $result['rankings'][$index],
            ];
        })->toArray();

        // Sort by rank untuk presentasi
        $sortedAlternatives = $result['alternatives'];
        usort($sortedAlternatives, fn($a, $b) => $a['rank'] <=> $b['rank']);
        $result['alternatives_by_rank'] = $sortedAlternatives;

        $result['ratings_matrix']        = $ratingsMatrix;
        $result['criteria_types']        = $criteriaTypes;
        $result['anp_weights']           = $anpWeights;
        $result['decision_makers_count'] = $decisionMakers->count();
        $result['aggregation_method']    = 'average';

        // Simpan ke database
        \App\Models\CalculationResult::create([
            'method'        => 'WP',
            'data'          => $result,
            'calculated_at' => now(),
        ]);

        return $result;
    }

    /**
     * Proses Weighted Product untuk Decision Maker tertentu
     */
    public function processWPForDM($dmId): array
    {
        // Check if DM has provided any ratings
        $ratingsCount = AlternativeRating::where('user_id', $dmId)->count();

        // Ambil hasil ANP terakhir untuk DM ini
        $method    = 'ANP_DM_' . $dmId;
        $anpResult = \App\Models\CalculationResult::where('method', $method)
            ->latest('calculated_at')
            ->first();

        if (! $anpResult) {
            // Fallback to global ANP if specific not found (optional)
            // But in multi-DM, we expect specific ANP
            $anpResult = \App\Models\CalculationResult::where('method', 'ANP')
                ->latest('calculated_at')
                ->first();

            if (! $anpResult) {
                throw new \Exception("ANP calculation not found for DM {$dmId} or global. Please run ANP first.");
            }
        }

        $anpData    = $anpResult->data;
        $anpWeights = $anpData['weights'];

        // Build matriks rating untuk DM ini
        $ratingsMatrix = $this->buildRatingsMatrixForDM($dmId);

        // Ambil tipe kriteria
        $criteria      = Criteria::orderBy('id')->get();
        $criteriaTypes = $criteria->pluck('type')->toArray();

        // Hitung WP
        $result = $this->calculateWP($ratingsMatrix, $anpWeights, $criteriaTypes);

        // Konversi ranking ke poin Borda
        $bordaPoints            = $this->convertRankingsToBordaPoints($result['rankings']);
        $result['borda_points'] = $bordaPoints;

        // Tambahkan mapping alternatif
        $alternatives           = Alternative::orderBy('id')->get();
        $result['alternatives'] = $alternatives->map(function ($alt, $index) use ($result, $bordaPoints) {
            return [
                'id'               => $alt->id,
                'code'             => $alt->code,
                'name'             => $alt->name,
                'vector_s'         => $result['vector_s'][$index],
                'vector_v'         => $result['vector_v'][$index],
                'preference_score' => $result['vector_v'][$index], // Alias untuk kejelasan
                'rank'             => $result['rankings'][$index],
                'borda_point'      => $bordaPoints[$index],
            ];
        })->toArray();

        // Sort by rank untuk presentasi
        $sortedAlternatives = $result['alternatives'];
        usort($sortedAlternatives, fn($a, $b) => $a['rank'] <=> $b['rank']);
        $result['alternatives_by_rank'] = $sortedAlternatives;

        $result['decision_maker_id'] = $dmId;
        $result['ratings_matrix']    = $ratingsMatrix;
        $result['criteria_types']    = $criteriaTypes;
        $result['anp_weights']       = $anpWeights;
        $result['has_ratings']       = $ratingsCount > 0;
        $result['ratings_count']     = $ratingsCount;

        // Simpan ke database
        \App\Models\CalculationResult::create([
            'method'        => 'WP_DM_' . $dmId,
            'user_id'       => $dmId,
            'data'          => $result,
            'calculated_at' => now(),
        ]);

        return $result;
    }

    /**
     * Konversi ranking WP menjadi poin Borda (untuk integrasi dengan Borda)
     * Rank 1 = 5 poin, Rank 2 = 4 poin, ..., Rank 5 = 1 poin
     */
    public function convertRankingsToBordaPoints(array $rankings): array
    {
        $n      = count($rankings);
        $points = [];

        foreach ($rankings as $rank) {
            // Poin Borda = N - Rank + 1
            $points[] = $n - $rank + 1;
        }

        return $points;
    }
}
