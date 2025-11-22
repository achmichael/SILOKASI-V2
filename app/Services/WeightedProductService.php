<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\AlternativeRating;
use App\Models\Criteria;

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
    public function calculateWP(array $ratingsMatrix, array $weights, array $criteriaTypes): array
    {
        $m = count($ratingsMatrix); // Jumlah alternatif
        $n = count($weights); // Jumlah kriteria

        // Step 1: Hitung S_i (Vector S)
        $vectorS = [];
        for ($i = 0; $i < $m; $i++) {
            $s = 1.0;
            for ($j = 0; $j < $n; $j++) {
                $rating = $ratingsMatrix[$i][$j];
                $weight = $weights[$j];
                
                // Untuk cost, gunakan eksponen negatif
                if ($criteriaTypes[$j] === 'cost') {
                    $weight = -$weight;
                }
                
                // S_i = product of (X_ij ^ w_j)
                $s *= pow($rating, $weight);
            }
            $vectorS[] = $s;
        }

        // Step 2: Hitung V_i (Vector V - Normalisasi)
        $totalS = array_sum($vectorS);
        $vectorV = [];
        for ($i = 0; $i < $m; $i++) {
            $vectorV[] = round($vectorS[$i] / $totalS, 6);
        }

        // Step 3: Ranking berdasarkan nilai V (descending)
        $rankings = $this->getRankings($vectorV);

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
     * Build matriks rating dari database
     */
    public function buildRatingsMatrix(): array
    {
        $alternatives = Alternative::orderBy('id')->get();
        $criteria = Criteria::orderBy('id')->get();

        $matrix = [];
        foreach ($alternatives as $alternative) {
            $row = [];
            foreach ($criteria as $criterion) {
                $rating = AlternativeRating::where('alternative_id', $alternative->id)
                    ->where('criteria_id', $criterion->id)
                    ->whereNull('decision_maker_id') // Default: tanpa DM
                    ->first();
                
                $row[] = $rating ? $rating->rating : 0;
            }
            $matrix[] = $row;
        }

        return $matrix;
    }

    /**
     * Build matriks rating untuk Decision Maker tertentu
     */
    public function buildRatingsMatrixForDM($dmId): array
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

    /**
     * Proses Weighted Product dari database
     */
    public function processWP(): array
    {
        // Ambil hasil ANP terakhir
        $anpResult = \App\Models\CalculationResult::where('method', 'ANP')
            ->latest('calculated_at')
            ->first();

        if (!$anpResult) {
            throw new \Exception('ANP calculation not found. Please run ANP first.');
        }

        $anpData = $anpResult->data;
        $anpWeights = $anpData['weights'];

        // Build matriks rating
        $ratingsMatrix = $this->buildRatingsMatrix();

        // Ambil tipe kriteria
        $criteria = Criteria::orderBy('id')->get();
        $criteriaTypes = $criteria->pluck('type')->toArray();

        // Hitung WP
        $result = $this->calculateWP($ratingsMatrix, $anpWeights, $criteriaTypes);

        // Tambahkan mapping alternatif
        $alternatives = Alternative::orderBy('id')->get();
        $result['alternatives'] = $alternatives->map(function($alt, $index) use ($result) {
            return [
                'id' => $alt->id,
                'code' => $alt->code,
                'name' => $alt->name,
                'vector_s' => $result['vector_s'][$index],
                'vector_v' => $result['vector_v'][$index],
                'preference_score' => $result['vector_v'][$index], // Alias untuk kejelasan
                'rank' => $result['rankings'][$index],
            ];
        })->toArray();

        // Sort by rank untuk presentasi
        $sortedAlternatives = $result['alternatives'];
        usort($sortedAlternatives, fn($a, $b) => $a['rank'] <=> $b['rank']);
        $result['alternatives_by_rank'] = $sortedAlternatives;

        $result['ratings_matrix'] = $ratingsMatrix;
        $result['criteria_types'] = $criteriaTypes;
        $result['anp_weights'] = $anpWeights;

        // Simpan ke database
        \App\Models\CalculationResult::create([
            'method' => 'WP',
            'data' => $result,
            'calculated_at' => now(),
        ]);

        return $result;
    }

    /**
     * Proses Weighted Product untuk Decision Maker tertentu
     */
    public function processWPForDM($dmId): array
    {
        // Ambil hasil ANP terakhir
        $anpResult = \App\Models\CalculationResult::where('method', 'ANP')
            ->latest('calculated_at')
            ->first();

        if (!$anpResult) {
            throw new \Exception('ANP calculation not found. Please run ANP first.');
        }

        $anpData = $anpResult->data;
        $anpWeights = $anpData['weights'];

        // Build matriks rating untuk DM ini
        $ratingsMatrix = $this->buildRatingsMatrixForDM($dmId);

        // Ambil tipe kriteria
        $criteria = Criteria::orderBy('id')->get();
        $criteriaTypes = $criteria->pluck('type')->toArray();

        // Hitung WP
        $result = $this->calculateWP($ratingsMatrix, $anpWeights, $criteriaTypes);

        // Konversi ranking ke poin Borda
        $bordaPoints = $this->convertRankingsToBordaPoints($result['rankings']);
        $result['borda_points'] = $bordaPoints;

        // Tambahkan mapping alternatif
        $alternatives = Alternative::orderBy('id')->get();
        $result['alternatives'] = $alternatives->map(function($alt, $index) use ($result, $bordaPoints) {
            return [
                'id' => $alt->id,
                'code' => $alt->code,
                'name' => $alt->name,
                'vector_s' => $result['vector_s'][$index],
                'vector_v' => $result['vector_v'][$index],
                'preference_score' => $result['vector_v'][$index], // Alias untuk kejelasan
                'rank' => $result['rankings'][$index],
                'borda_point' => $bordaPoints[$index],
            ];
        })->toArray();

        // Sort by rank untuk presentasi
        $sortedAlternatives = $result['alternatives'];
        usort($sortedAlternatives, fn($a, $b) => $a['rank'] <=> $b['rank']);
        $result['alternatives_by_rank'] = $sortedAlternatives;

        $result['decision_maker_id'] = $dmId;
        $result['ratings_matrix'] = $ratingsMatrix;
        $result['criteria_types'] = $criteriaTypes;
        $result['anp_weights'] = $anpWeights;

        // Simpan ke database
        \App\Models\CalculationResult::create([
            'method' => 'WP_DM_' . $dmId,
            'data' => $result,
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
        $n = count($rankings);
        $points = [];
        
        foreach ($rankings as $rank) {
            // Poin Borda = N - Rank + 1
            $points[] = $n - $rank + 1;
        }
        
        return $points;
    }
}
