<?php

namespace App\Http\Controllers;

use App\Services\AhpService;
use App\Services\AnpService;
use App\Services\WeightedProductService;
use App\Services\BordaService;
use Illuminate\Http\Request;

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
            $result = $this->ahpService->processAHP();

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
            $result = $this->anpService->processANP();

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
    public function calculateWP(Request $request)
    {
        try {
            $dmId = $request->query('dm_id');
            
            if ($dmId) {
                // Hitung WP untuk Decision Maker tertentu
                $result = $this->wpService->processWPForDM($dmId);
                $message = 'Weighted Product calculation completed for DM ' . $dmId;
            } else {
                // Hitung WP default (tanpa DM)
                $result = $this->wpService->processWP();
                $message = 'Weighted Product calculation completed';
            }

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
            $ahpResult = $this->ahpService->processAHP();
            $anpResult = $this->anpService->processANP();
            $wpResult = $this->wpService->processWP();
            $bordaResult = $this->bordaService->processBorda();

            return response()->json([
                'success' => true,
                'message' => 'All calculations completed successfully',
                'data' => [
                    'ahp' => $ahpResult,
                    'anp' => $anpResult,
                    'wp' => $wpResult,
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
}
