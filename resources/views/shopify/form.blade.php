<!-- resources/views/shopify.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Install Shopify App</title>
</head>
<body>
    <h1>Enter Your Shopify Store URL</h1>
    <form action="{{ route('shopify.auth') }}" method="GET">
        @csrf
        <label for="shop">Shopify Store URL:</label>
        <input type="text" name="shop" id="shop" placeholder="yourstore.myshopify.com" required>
        <button type="submit">Install App</button>
    </form>

    <!-- Display errors -->
    @if($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
