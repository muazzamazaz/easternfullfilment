<?php

namespace App\Http\Controllers;

use App\Services\AmazonSPAPIService;

class AmazonProductController extends Controller
{
    protected $amazonService;

    public function __construct(AmazonSPAPIService $amazonService)
    {
        $this->amazonService = $amazonService;
    }

    public function getAllProductListings($sellerId)
    {
        // Define your marketplace ID, for example, 'ATVPDKIKX0DER' for US.
        $marketplaceIds = ['ATVPDKIKX0DER'];

        $response = $this->amazonService->getAllListings($sellerId, $marketplaceIds);

        return response()->json($response);
    }
}
