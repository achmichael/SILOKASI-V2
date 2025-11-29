<?php

namespace App\Services;

use App\Models\PairwiseComparison;
use App\Models\Criteria;

class AhpService
{
    /**
     * Random Index untuk uji konsistensi
     */
    private const RANDOM_INDEX = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
        11 => 1.51,
        12 => 1.48,
        13 => 1.56,
        14 => 1.57,
        15 => 1.59,
    ];

    /**
     * Hitung bobot AHP dari matriks perbandingan berpasangan
     *
     * @param array $pairwiseMatrix Matrix perbandingan berpasangan N x N
     * @return array ['weights' => [...], 'lambda_max' => ..., 'ci' => ..., 'cr' => ..., 'is_consistent' => ...]
     */
    public function calculateAHP(array $pairwiseMatrix): array
    {
        $n = count($pairwiseMatrix);
        
        // Step 1: Hitung perkalian setiap baris
        $rowProducts = [];
        for ($i = 0; $i < $n; $i++) {
            $product = 1.0;
            for ($j = 0; $j < $n; $j++) {
                $product *= $pairwiseMatrix[$i][$j];
            }
            $rowProducts[] = $product;
        }

        // Step 2: Hitung akar pangkat N
        $nthRoots = [];
        for ($i = 0; $i < $n; $i++) {
            $nthRoots[] = pow($rowProducts[$i], 1.0 / $n);
        }

        // Step 3: Normalisasi untuk mendapatkan Eigen Vector (W)
        $sumRoots = array_sum($nthRoots);
        
        // Prevent division by zero
        if ($sumRoots == 0) {
            throw new \Exception('Sum of nth roots is zero. Please check pairwise comparison matrix.');
        }
        
        $eigenVector = [];
        for ($i = 0; $i < $n; $i++) {
            $eigenVector[] = round($nthRoots[$i] / $sumRoots, 4);
        }

        // Step 4: Hitung λmax (lambda maksimum)
        $lambdaMax = $this->calculateLambdaMax($pairwiseMatrix, $eigenVector);

        // Step 5: Hitung Consistency Index (CI)
        $ci = $n > 1 ? ($lambdaMax - $n) / ($n - 1) : 0;

        // Step 6: Hitung Consistency Ratio (CR)
        $ri = self::RANDOM_INDEX[$n] ?? 1.41;
        $cr = $ri > 0 ? $ci / $ri : 0;

        // Konsisten jika CR < 0.1
        $isConsistent = $cr < 0.1;

        return [
            'weights' => $eigenVector,
            'lambda_max' => round($lambdaMax, 4),
            'ci' => round($ci, 4),
            'cr' => round($cr, 4),
            'ri' => $ri,
            'is_consistent' => $isConsistent,
            'n' => $n,
        ];
    }

    /**
     * Hitung Lambda Max
     */
    private function calculateLambdaMax(array $matrix, array $weights): float
    {
        $n = count($matrix);
        $weightedSum = array_fill(0, $n, 0);

        // Kalikan matriks dengan vektor bobot
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }

        // Bagi dengan bobot masing-masing dan rata-rata
        $lambdaValues = [];
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambdaValues[] = $weightedSum[$i] / $weights[$i];
            }
        }

        if (count($lambdaValues) == 0) {
            throw new \Exception('No valid lambda values calculated. All weights are zero.');
        }

        return array_sum($lambdaValues) / count($lambdaValues);
    }

    /**
     * Build matriks perbandingan berpasangan dari database
     */
    public function buildPairwiseMatrix($userId = null): array
    {
        $criteria = Criteria::orderBy('id')->get();
        $n = $criteria->count();
        $matrix = array_fill(0, $n, array_fill(0, $n, 1.0));

        $query = PairwiseComparison::query();
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $comparisons = $query->get();
        
        foreach ($comparisons as $comparison) {
            $i = $criteria->search(fn($c) => $c->id == $comparison->criteria_i);
            $j = $criteria->search(fn($c) => $c->id == $comparison->criteria_j);
            
            if ($i !== false && $j !== false) {
                $matrix[$i][$j] = (float) $comparison->value;
                // Reciprocal untuk posisi terbalik
                if ($comparison->value != 0) {
                    $matrix[$j][$i] = 1.0 / (float) $comparison->value;
                }
            }
        }

        return $matrix;
    }

    /**
     * Hitung AHP dari database dan simpan hasilnya
     */
    public function processAHP($userId = null): array
    {
        $matrix = $this->buildPairwiseMatrix($userId);
        $result = $this->calculateAHP($matrix);
        
        // Tambahkan mapping kriteria
        $criteria = Criteria::orderBy('id')->get();
        $result['criteria'] = $criteria->map(function($c, $index) use ($result) {
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'weight' => $result['weights'][$index],
            ];
        })->toArray();

        // Simpan ke database
        $method = $userId ? 'AHP_DM_' . $userId : 'AHP';
        \App\Models\CalculationResult::create([
            'method' => $method,
            'data' => $result,
            'calculated_at' => now(),
        ]);

        return $result;
    }
}
