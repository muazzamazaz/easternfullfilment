<?php

namespace App\Services;

use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Api\ListingsApi;
use SellingPartnerApi\Enums\Endpoint;
use SellingPartnerApi\Api\CatalogItemsApi;

use Exception;
use DAteTime;
class AmazonSPAPIService
{
    protected $config;

    public function __construct()
    {
        $config = new Configuration([
            'lwaClientId'        => env('AMAZON_SP_API_CLIENT_ID'),
            'lwaClientSecret'    => env('AMAZON_SP_API_CLIENT_SECRET'),
            'lwaRefreshToken'    => env('AMAZON_SP_API_REFRESH_TOKEN'),
            'awsAccessKeyId'     => env('AMAZON_SP_API_ACCESS_KEY_ID'),
            'awsSecretAccessKey' => env('AMAZON_SP_API_SECRET_ACCESS_KEY'),
            'endpoint'           => Endpoint::NA//, // Adjust for your region (EU, NA, FE)
           // 'roleArn'            => env('AWS_SELLING_PARTNER_ROLE')
        ]);


$sellingPartnerApi = new SellingPartnerApi(
    clientId: env('AMAZON_SP_API_CLIENT_ID'),
    clientSecret: env('AMAZON_SP_API_CLIENT_SECRET'),
    refreshToken: env('AMAZON_SP_API_REFRESH_TOKEN'),
    endpoint: Endpoint::NA,
);

$catalogItemsApi = new CatalogItemsApi($config);


$response = $catalogItemsApi->getCatalogItem('A36CB6HV533JX9', [
    // Optionally include other parameters here
]);

// Handle response
if ($response) {
    print_r($response);
} else {
    echo "No catalog items found.";
}
dd($catalogItemsApi);
    }
    /**
     * Get all product listings for a seller
     * @param string $sellerId
     * @param array $marketplaceIds
     * @return array
     */
    public function getAllListings($sellerId, $marketplaceIds)
    {
        try {
            $listings = [];
            $nextToken = null;

            do {
                $params = [
                    'operation' => 'getListingsItem',
                    'path' => [
                        'sellerId' => $sellerId,
                    ],
                    'query' => [
                        'marketplaceIds' => $marketplaceIds,
                        'includedData' => ['summaries'],
                    ]
                ];

                if ($nextToken) {
                    $params['query']['nextToken'] = $nextToken;
                }

                $response = $this->spApi->callApi($params);

                if (isset($response['listings'])) {
                    $listings = array_merge($listings, $response['listings']);
                }

                // Check if there is a next page
                $nextToken = $response['nextToken'] ?? null;

            } while ($nextToken);

            return $listings;
        } catch (Exception $e) {
            // Handle and log errors
            return ['error' => $e->getMessage()];
        }
    }
}
