<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\BordaPoint;
use App\Models\User;

class BordaService
{
    /**
     * Hitung skor Borda untuk agregasi keputusan kelompok
     *
     * @param array $bordaPoints Array 2D: [DM][Alternative] = points
     * @return array ['scores' => [...], 'rankings' => [...]]
     */
    public function calculateBorda(array $bordaPoints): array
    {
        $numAlternatives = count($bordaPoints[0] ?? []);
        $numDM = count($bordaPoints);

        $scores = array_fill(0, $numAlternatives, 0.0);

        // Hitung: V_Borda(A_i) = Σ P_ik (all DMs have equal weight)
        for ($i = 0; $i < $numAlternatives; $i++) {
            for ($k = 0; $k < $numDM; $k++) {
                $scores[$i] += $bordaPoints[$k][$i];
            }
        }

        // Round scores
        $scores = array_map(fn($s) => round($s, 2), $scores);

        // Ranking berdasarkan skor (descending)
        $rankings = $this->getRankings($scores);

        return [
            'scores' => $scores,
            'rankings' => $rankings,
        ];
    }

    /**
     * Konversi skor menjadi ranking
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
            $rankings[$item['index']] = $rank + 1;
        }

        return $rankings;
    }

    /**
     * Build matriks poin Borda dari database
     */
    public function buildBordaPointsMatrix(): array
    {
        $decisionMakers = User::decisionMakers()->orderBy('id')->get();
        $alternatives = Alternative::orderBy('id')->get();

        $matrix = [];
        foreach ($decisionMakers as $dm) {
            $row = [];
            foreach ($alternatives as $alt) {
                $bordaPoint = BordaPoint::where('user_id', $dm->id)
                    ->where('alternative_id', $alt->id)
                    ->first();
                
                $row[] = $bordaPoint ? $bordaPoint->points : 0;
            }
            $matrix[] = $row;
        }

        return $matrix;
    }

    /**
     * Proses BORDA dari database
     */
    public function processBorda(): array
    {
        // Ambil decision makers (users with role decision_maker)
        $decisionMakers = User::decisionMakers()->orderBy('id')->get();

        // Build matriks poin Borda
        $bordaPointsMatrix = $this->buildBordaPointsMatrix();

        // Hitung Borda (no weights - all DMs are equal)
        $result = $this->calculateBorda($bordaPointsMatrix);

        // Ambil WP result untuk mendapatkan vector V
        $wpResults = [];
        foreach ($decisionMakers as $dm) {
            $wpResult = \App\Models\CalculationResult::where('method', 'WP_DM_' . $dm->id)
                ->latest('calculated_at')
                ->first();
            if ($wpResult) {
                $wpResults[$dm->id] = $wpResult->data;
            }
        }

        // Tambahkan mapping alternatif
        $alternatives = Alternative::orderBy('id')->get();
        $result['alternatives'] = $alternatives->map(function($alt, $index) use ($result, $wpResults, $bordaPointsMatrix, $decisionMakers) {
            // Kumpulkan detail per DM
            $dmDetails = [];
            foreach ($decisionMakers as $dmIdx => $dm) {
                $vectorV = null;
                $ranking = null;
                
                if (isset($wpResults[$dm->id]['alternatives'])) {
                    $altData = collect($wpResults[$dm->id]['alternatives'])->firstWhere('id', $alt->id);
                    if ($altData) {
                        $vectorV = $altData['vector_v'] ?? null;
                        $ranking = $altData['rank'] ?? null;
                    }
                }
                
                $dmDetails[] = [
                    'dm_id' => $dm->id,
                    'dm_name' => $dm->name,
                    'vector_v' => $vectorV,
                    'ranking' => $ranking,
                    'borda_points' => $bordaPointsMatrix[$dmIdx][$index] ?? 0,
                ];
            }

            return [
                'id' => $alt->id,
                'code' => $alt->code,
                'name' => $alt->name,
                'borda_score' => $result['scores'][$index],
                'rank' => $result['rankings'][$index],
                'decision_makers_detail' => $dmDetails,
            ];
        })->toArray();

        // Sort by rank untuk presentasi
        usort($result['alternatives'], fn($a, $b) => $a['rank'] <=> $b['rank']);

        $result['decision_makers'] = $decisionMakers->map(function($dm) {
            return [
                'id' => $dm->id,
                'name' => $dm->name,
                'email' => $dm->email,
            ];
        })->toArray();

        $result['borda_points_matrix'] = $bordaPointsMatrix;

        // Simpan ke database
        \App\Models\CalculationResult::create([
            'method' => 'BORDA',
            'data' => $result,
            'calculated_at' => now(),
        ]);

        return $result;
    }

    /**
     * Dapatkan perangkingan akhir (Best alternative)
     */
    public function getFinalRanking(): array
    {
        $result = \App\Models\CalculationResult::where('method', 'BORDA')
            ->latest('calculated_at')
            ->first();

        if (!$result) {
            throw new \Exception('BORDA calculation not found. Please run BORDA first.');
        }

        return $result->data;
    }
}
