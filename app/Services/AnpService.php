<?php

namespace App\Services;

use App\Models\AnpInterdependency;
use App\Models\Criteria;
use Illuminate\Support\Facades\Log;

class AnpService
{
    /**
     * Hitung bobot ANP dari matriks interdependensi dan bobot AHP
     *
     * @param array $interdependencyMatrix Matriks normalisasi interdependensi N x N
     * @param array $ahpWeights Bobot dari AHP
     * @return array ['weights' => [...], 'criteria' => [...]]
     */
    public function calculateANP(array $interdependencyMatrix, array $ahpWeights): array
    {
        $n = count($interdependencyMatrix);
        $anpWeights = array_fill(0, $n, 0.0);

        // Perkalian Matriks: ANP = Interdependency Matrix × AHP Weights
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $anpWeights[$i] += $interdependencyMatrix[$i][$j] * $ahpWeights[$j];
            }
        }

        // Round untuk presisi
        $anpWeights = array_map(fn($w) => round($w, 4), $anpWeights);

        return [
            'weights' => $anpWeights,
            'total_weight' => round(array_sum($anpWeights), 4),
        ];
    }

    /**
     * Build matriks interdependensi dari database
     */
    public function buildInterdependencyMatrix($userId = null): array
    {
        $criteria = Criteria::orderBy('id')->get();
        $n = $criteria->count();
        
        // Inisialisasi matriks dengan 0
        $matrix = array_fill(0, $n, array_fill(0, $n, 0.0));

        $query = AnpInterdependency::query();
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $interdependencies = $query->get();
        
        foreach ($interdependencies as $interdependency) {
            $i = $criteria->search(fn($c) => $c->id == $interdependency->criteria_i);
            $j = $criteria->search(fn($c) => $c->id == $interdependency->criteria_j);
            
            if ($i !== false && $j !== false) {
                $matrix[$i][$j] = (float) $interdependency->value;
            }
        }

        return $matrix;
    }

    /**
     * Proses ANP: ambil bobot AHP, kalikan dengan matriks interdependensi
     */
    public function processANP($userId = null): array
    {
        // Ambil hasil AHP terakhir
        $method = $userId ? 'AHP_DM_' . $userId : 'AHP';
        // Ambil hasil kalkulasi AHP DM yang melakukan perhitungan ANP
        $ahpResult = \App\Models\CalculationResult::where('method', $method)
            ->where('user_id', $userId)
            ->latest('calculated_at')
            ->first();
        // return error jika tidak ditemukan hasil AHP, dikarenakan ANP bergantung pada AHP
        if (!$ahpResult) {
            throw new \Exception("AHP calculation not found for method {$method}. Please run AHP first.");
        }
        // ambil data hasil perhitungan AHP
        $ahpData = $ahpResult->data;
        // ambil bobot AHP
        $ahpWeights = $ahpData['weights'];
        // bangun matriks interdependensi ANP
        $interdependencyMatrix = $this->buildInterdependencyMatrix($userId);
        // hitung bobot ANP
        $result = $this->calculateANP($interdependencyMatrix, $ahpWeights);
        // ambil data kriteria untuk hasil
        $criteria = Criteria::orderBy('id')->get();
        // susun hasil dengan kriteria
        $result['criteria'] = $criteria->map(function($c, $index) use ($result, $ahpWeights) {
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'ahp_weight' => $ahpWeights[$index],
                'anp_weight' => $result['weights'][$index],
            ];
        })->toArray();
        // ambil matriks interdependensi untuk hasil
        $result['interdependency_matrix'] = $interdependencyMatrix;
        // Simpan ke database 
        $saveMethod = $userId ? 'ANP_DM_' . $userId : 'ANP';
        \App\Models\CalculationResult::create([
            'method' => $saveMethod,
            'data' => $result,
            'user_id' => $userId,
            'calculated_at' => now(),
        ]);
        return $result;
    }
}
