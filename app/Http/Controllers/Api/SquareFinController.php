<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SquareFin\SquareFin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SquareFinController extends Controller
{
    protected $squareFin;

    public function __construct(SquareFin $squareFin)
    {
        $this->squareFin = $squareFin;
    }

    /**
     * Generate SquareFin token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(Request $request)
    {
        try {
            // Optional: Allow custom credentials via request
            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string',
                'password' => 'sometimes|string',
                'method' => 'sometimes|in:http,curl'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Use custom credentials if provided, otherwise use config
            if ($request->has('username') && $request->has('password')) {
                // For this case, you might want to create a temporary instance
                // or modify the service to accept credentials as parameters
                return response()->json([
                    'success' => false,
                    'message' => 'Custom credentials not implemented yet. Please use environment configuration.'
                ], 400);
            }

            // Choose method based on request or default to HTTP
            $method = $request->get('method', 'http');
            
            if ($method === 'curl') {
                $result = $this->squareFin->generateTokenWithCurl();
            } else {
                $result = $this->squareFin->generateToken();
            }

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data']
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'error' => $result['error'] ?? null
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate token using cURL method specifically
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateTokenCurl(Request $request)
    {
        try {
            $result = $this->squareFin->generateTokenWithCurl();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data']
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'error' => $result['error'] ?? null
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 