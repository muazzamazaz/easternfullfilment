<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Biller;
use App\Models\Product_Sale;
use Illuminate\Http\Request;
use LaravelShipStation;
class ShipStationOrder extends Controller

{

    protected $shipStation;
    
    public function __construct()
    {        
        $this->shipStation = resolve('LaravelShipStation\ShipStation');
       // Log::info('Message: ' . print_r($this->shipstation));

      //  $this->shipstation = $shipstation;
     //   dd($this->shipstation);
    }
    
    public function getCountryCode($countryName){
    $url = "https://restcountries.com/v3.1/name/{$countryName}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (!empty($data) && isset($data[0]['cca2'])) {
        $countryCode = $data[0]['cca2']; // ISO 3166-1 alpha-2 code
    return $countryCode;
    }

    }
    public function createOrder($order_id){
        
        // Assuming you have the order details and product data
    $sale_data = Sale::find($order_id);
   
    if (is_null($sale_data->ship_order)) {
    
    $order = new LaravelShipStation\Models\Order();

    $order->orderNumber = $sale_data->reference_no;
    $order->orderDate = $sale_data->created_at;
    $order->paymentDate = $sale_data->created_at;
    $order->orderStatus = $sale_data->grand_total>0?2: $sale_data->sale_status;
    $order->amountPaid = $sale_data->grand_total;
    $order->taxAmount = $sale_data->total_tax;
    $order->shippingAmount = 0;
    $order->internalNotes = $sale_data->sale_note;

    $address = new LaravelShipStation\Models\Address();
        
    $address->name = $sale_data->biller->name;
    $address->street1 =$sale_data->biller->address;
    $address->city = $sale_data->biller->city;
    $address->state = $sale_data->biller->state ?? '';
    $address->postalCode = $sale_data->biller->postal_code ?? 0;
    $address->country = $this->getCountryCode($sale_data->biller->country);
    $address->phone = $sale_data->biller->phone_number;
    
    $order->billTo = $address;
    $order->shipTo = $address;
    
        foreach ($sale_data->productSales as $productSale) {
            $item = new LaravelShipStation\Models\OrderItem();
            $product=Product::find($productSale->product_id);
       //     Log::info($productSale);
            if($product){
        
            $item->lineItemKey = $productSale->id;
            $item->name = $product->name;
            $item->quantity = $productSale->qty;
            $item->sku = $productSale->imei_number;
            $item->unitPrice = $productSale->net_unit_price;
            
            $order->items[] = $item;  
            }
        }
        
    $the=$this->shipStation->orders->post($order, 'createorder');
    $sale_data->ship_order=$the->orderId;
    $sale_data->save();
            return redirect('/sales')->with('message', 'Order shipment request sent  successfully');
    }
  else
            return redirect('/sales')->with('message', 'Order already shipped.');
 
    

    // or with the helper: $shipStation->orders->create($order); would be the same.
    }
  

}
