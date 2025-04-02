<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Dashboard</title>
</head>
<body>
    <h1>Welcome to your Shopify Dashboard!</h1>

    <h2>Shop Details:</h2>
    <p>Shop Domain: {{ $shopData->shop_domain }}</p>
    <p>Access Token: {{ $shopData->access_token }}</p>
</body>
</html>
