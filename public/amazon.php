<?php

function sign($key, $msg) {
    return hash_hmac('sha256', $msg, $key, true);
}

function getSignatureKey($key, $dateStamp, $regionName, $serviceName) {
    $kDate = sign("AWS4" . $key, $dateStamp);
    $kRegion = sign($kDate, $regionName);
    $kService = sign($kRegion, $serviceName);
    $kSigning = sign($kService, "aws4_request");
    return $kSigning;
}

$accessKey = 'amzn1.application-oa2-client.c7c5b66675e641af942d47bd63e6749d'; // Your AWS Access Key
$secretKey = 'amzn1.oa2-cs.v1.2aae2ac28d406f949844c2206ccaf0bc578f208e1e110ea45835302d16fc1d5c'; // Your AWS Secret Key
$region = 'us-east-1';              // AWS Region
$service = 'execute-api';           // Service name for SP-API
$host = 'sellingpartnerapi-fe.amazon.com'; // SP-API endpoint
$canonicalUri = '/reports/2021-06-30/reports'; // API path for SP-API

// Request Payload (if applicable)
$payload = json_encode([
    'reportType' => 'GET_FLAT_FILE_ACTIONABLE_ORDER_DATA_SHIPPING',
    'marketplaceIds' => ['A1VC38T7YXB528'], // Example: Amazon JP marketplace
    'dataStartTime' => '2023-06-30T00:00:00Z',
    'dataEndTime' => '2023-06-30T23:59:59Z'
]);

// Step 1: Generate timestamp and date format
$amzDate = gmdate('Ymd\THis\Z');  // Format: YYYYMMDD'T'HHMMSS'Z'
$dateStamp = gmdate('Ymd');       // Format: YYYYMMDD

// Step 2: Create Canonical Request
$canonicalHeaders = "content-type:application/json\nhost:$host\nx-amz-date:$amzDate\n";
$signedHeaders = 'content-type;host;x-amz-date';
$payloadHash = hash('sha256', $payload); // Hash the payload
$canonicalRequest = "POST\n$canonicalUri\n\n$canonicalHeaders\n$signedHeaders\n$payloadHash";

// Step 3: Create the String to Sign
$algorithm = 'AWS4-HMAC-SHA256';
$credentialScope = "$dateStamp/$region/$service/aws4_request";
$hashedCanonicalRequest = hash('sha256', $canonicalRequest);
$stringToSign = "$algorithm\n$amzDate\n$credentialScope\n$hashedCanonicalRequest";

// Step 4: Calculate the Signature
$signingKey = getSignatureKey($secretKey, $dateStamp, $region, $service);
$signature = hash_hmac('sha256', $stringToSign, $signingKey);

// Step 5: Create the Authorization Header
$authorizationHeader = "$algorithm Credential=$accessKey/$credentialScope, SignedHeaders=$signedHeaders, Signature=$signature";

// Set up headers for the cURL request
$headers = [
    'Content-Type: application/json',
    'x-amz-date: ' . $amzDate,
    'Authorization: ' . $authorizationHeader
];
echo $authorizationHeader;
/*
// cURL request setup
$endpoint = "https://$host$canonicalUri";
$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Output the response
echo $response;
*/