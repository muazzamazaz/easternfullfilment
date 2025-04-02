<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyComplianceController extends Controller
{
    // Handle customers/data_request event
    public function handleDataRequest(Request $request)
    {
        $this->verifyShopifyRequest($request);

        $payload = json_decode($request->getContent(), true);
        Log::info('Data Request Webhook Received', $payload);

        // TODO: Implement logic to gather and provide the necessary customer data

        return response()->json(['status' => 'Data request processed']);
    }

    // Handle customers/redact event
    public function handleCustomerRedact(Request $request)
    {
        $this->verifyShopifyRequest($request);

        $payload = json_decode($request->getContent(), true);
        Log::info('Customer Redact Webhook Received', $payload);

        // TODO: Implement logic to redact/delete customer data as specified

        return response()->json(['status' => 'Customer data redacted']);
    }

    // Handle shop/redact event
    public function handleShopRedact(Request $request)
    {
        $this->verifyShopifyRequest($request);

        $payload = json_decode($request->getContent(), true);
        Log::info('Shop Redact Webhook Received', $payload);

        // TODO: Implement logic to delete/store data associated with the shop

        return response()->json(['status' => 'Shop data redacted']);
    }

    // Verify the request signature to ensure it's from Shopify
    private function verifyShopifyRequest(Request $request)
    {
        $hmacHeader = $request->header('X-Shopify-Hmac-SHA256');
        $data = $request->getContent();
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_API_SECRET'), true));

        if (!hash_equals($hmacHeader, $calculatedHmac)) {
            abort(403, 'Unauthorized webhook');
        }
    }
}
