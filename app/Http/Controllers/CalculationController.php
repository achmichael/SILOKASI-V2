<?php

namespace App\Http\Controllers;

use App\Services\AhpService;
use App\Services\AnpService;
use App\Services\WeightedProductService;
use App\Services\BordaService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CalculationController extends Controller
{
    protected $ahpService;
    protected $anpService;
    protected $wpService;
    protected $bordaService;

    public function __construct(
        AhpService $ahpService,
        AnpService $anpService,
        WeightedProductService $wpService,
        BordaService $bordaService
    ) {
        $this->ahpService = $ahpService;
        $this->anpService = $anpService;
        $this->wpService = $wpService;
        $this->bordaService = $bordaService;
    }

    /**
     * Hitung AHP
     */
    public function calculateAHP()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $result = $this->ahpService->processAHP($userId);

            return response()->json([
                'success' => true,
                'message' => 'AHP calculation completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'AHP calculation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hitung ANP
     */
    public function calculateANP()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $result = $this->anpService->processANP($userId);

            return response()->json([
                'success' => true,
                'message' => 'ANP calculation completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ANP calculation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hitung Weighted Product
     */
    public function calculateWP()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $dmId = $user->id;
            
            // Hitung WP untuk Decision Maker tertentu
            $result = $this->wpService->processWPForDM($dmId);
            $message = 'Weighted Product calculation completed for DM ' . $dmId;

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Weighted Product calculation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hitung BORDA
     */
    public function calculateBorda()
    {
        try {
            $result = $this->bordaService->processBorda();

            return response()->json([
                'success' => true,
                'message' => 'BORDA calculation completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'BORDA calculation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hitung semua (AHP -> ANP -> WP -> BORDA)
     */
    public function calculateAll()
    {
        try {
            // Validasi prerequisite data
            $this->validatePrerequisiteData();
            
            $decisionMakers = \App\Models\User::decisionMakers()->orderBy('id')->get();
            $ahpResults = [];
            $anpResults = [];
            $wpResults = [];
            
            foreach ($decisionMakers as $dm) {
                try {
                    // 1. Calculate AHP for DM
                    $ahpResult = $this->ahpService->processAHP($dm->id);
                    $ahpResults[$dm->id] = $ahpResult;

                    // 2. Calculate ANP for DM
                    $anpResult = $this->anpService->processANP($dm->id);
                    $anpResults[$dm->id] = $anpResult;

                    // 3. Calculate WP for DM
                    $wpResult = $this->wpService->processWPForDM($dm->id);
                    $wpResults[$dm->id] = $wpResult;
                    
                    // Simpan Borda Points dari WP result
                    $this->saveBordaPointsFromWP($dm->id, $wpResult);
                } catch (\Exception $e) {
                    // Log error but continue for other DMs? Or fail?
                    // For now, let's throw to ensure data integrity
                    throw new \Exception(
                        "Failed to calculate for Decision Maker '{$dm->name}': " . $e->getMessage()
                    );
                }
            }
            
            $bordaResult = $this->bordaService->processBorda();

            return response()->json([
                'success' => true,
                'message' => 'All calculations completed successfully',
                'data' => [
                    'ahp_per_dm' => $ahpResults,
                    'anp_per_dm' => $anpResults,
                    'wp_per_dm' => $wpResults,
                    'borda' => $bordaResult,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Calculation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validasi prerequisite data sebelum kalkulasi
     */
    private function validatePrerequisiteData()
    {
        // Check Criteria
        $criteriaCount = \App\Models\Criteria::count();
        if ($criteriaCount === 0) {
            throw new \Exception('No criteria found. Please add criteria first.');
        }

        // Check Alternatives
        $alternativesCount = \App\Models\Alternative::count();
        if ($alternativesCount === 0) {
            throw new \Exception('No alternatives found. Please add alternatives first.');
        }

        // Check Decision Makers
        $dmCount = \App\Models\User::decisionMakers()->count();
        if ($dmCount === 0) {
            throw new \Exception('No decision makers found. Please add decision makers first.');
        }

        // Validate each DM has data
        $decisionMakers = \App\Models\User::decisionMakers()->get();
        foreach ($decisionMakers as $dm) {
            // Check Pairwise Comparisons
            $pairwiseCount = \App\Models\PairwiseComparison::where('user_id', $dm->id)->count();
            if ($pairwiseCount === 0) {
                throw new \Exception(
                    "Decision Maker '{$dm->name}' has not submitted AHP pairwise comparisons."
                );
            }

            // Check ANP Interdependencies
            $anpCount = \App\Models\AnpInterdependency::where('user_id', $dm->id)->count();
            if ($anpCount === 0) {
                throw new \Exception(
                    "Decision Maker '{$dm->name}' has not submitted ANP interdependencies."
                );
            }

            // Check Alternative Ratings
            $dmRatingsCount = \App\Models\AlternativeRating::where('user_id', $dm->id)->count();
            if ($dmRatingsCount === 0) {
                throw new \Exception(
                    "Decision Maker '{$dm->name}' has not submitted any ratings."
                );
            }
        }
    }

    /**
     * Simpan Borda Points dari hasil WP
     */
    private function saveBordaPointsFromWP($dmId, $wpResult)
    {
        // Hapus data lama
        \App\Models\BordaPoint::where('user_id', $dmId)->delete();

        // Simpan borda points baru
        foreach ($wpResult['alternatives'] as $alt) {
            \App\Models\BordaPoint::create([
                'user_id' => $dmId,
                'alternative_id' => $alt['id'],
                'points' => $alt['borda_point'],
            ]);
        }
    }

    /**
     * Dapatkan hasil perhitungan terakhir
     */
    public function getResults(Request $request)
    {
        $method = $request->query('method'); // AHP, ANP, WP, BORDA

        $query = \App\Models\CalculationResult::latest('calculated_at');

        if ($method) {
            $query->where('method', strtoupper($method));
        }

        $results = $query->get();

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * Dapatkan perangkingan akhir
     */
    public function getFinalRanking()
    {
        try {
            $result = $this->bordaService->getFinalRanking();

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Dapatkan hasil Borda untuk decision maker
     */
    public function getBordaResults()
    {
        try {
            // Ambil semua BordaPoint dengan relasi alternative
            $bordaPoints = \App\Models\BordaPoint::with('alternative')
                ->select('alternative_id', \DB::raw('SUM(points) as total_points'))
                ->groupBy('alternative_id')
                ->orderBy('total_points', 'desc')
                ->get();

            if ($bordaPoints->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Borda points have not been calculated yet',
                ], 404);
            }

            // Format hasil
            $results = $bordaPoints->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'alternative_id' => $item->alternative_id,
                    'alternative_name' => $item->alternative->name,
                    'alternative_code' => $item->alternative->code,
                    'total_points' => $item->total_points,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
