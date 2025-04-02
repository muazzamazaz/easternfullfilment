<?php

namespace App\Http\Controllers;

use App\Services\AmazonSPApiService;
use Illuminate\Http\Request;

class AmazonSPApiController extends Controller
{
    protected $amazonSPApiService;

    public function __construct(AmazonSPApiService $amazonSPApiService)
    {
        $this->amazonSPApiService = $amazonSPApiService;
    }

    public function getProducts()
    {
        $products = $this->amazonSPApiService->getProducts();

        if ($products) {
            return response()->json(['data' => $products]);
        } else {
            return response()->json(['error' => 'Unable to fetch products'], 500);
        }
    }

    // Add more methods for other API interactions
}
