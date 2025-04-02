<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Signifly\Shopify\Shopify;
use App\Models\Biller;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Variant;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Product_Sale;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyController extends Controller
{
    public function auth(Request $request)
    {
        // Retrieve the shop domain from the query string
        $shopDomain = $request->query('shop'); // e.g., "mystore.myshopify.com"
        $integration_id = $request->query('integration_id'); // e.g., "mystore.myshopify.com"
        $userId = auth()->id();
 // Store the shop information in the database
              Shop::updateOrCreate(
                   ['shop_domain' => $shopDomain,'integration_id' => $integration_id,'user_id' => $userId],
                ['user_id' => $userId]
            );
        // Redirect the user to Shopify for authentication
        return redirect()->to("https://{$shopDomain}/admin/oauth/authorize?client_id=" . env('SHOPIFY_API_KEY') . "&scope=read_products,read_orders,read_customers&redirect_uri=" . route('shopify.callback'));
    }

    public function callback(Request $request)
    {
        $shop = $request->input('shop');
        $code = $request->input('code');
       $userId = auth()->id();
       $integration_id=2;
            Shop::updateOrCreate(
                   ['shop_domain' => $shop,'integration_id' => $integration_id,'user_id' => $userId],
                ['user_id' => $userId]
            );
       
        $userId = auth()->id();
        if (!$shop || !$code) {
            return redirect('/integrations')->with('error', 'Shop or code not provided');
        }

        // Get access token from Shopify
        $response = Http::post("https://{$shop}/admin/oauth/access_token", [
            'client_id' => env('SHOPIFY_API_KEY'),
            'client_secret' => env('SHOPIFY_API_SECRET'),
            'code' => $code,
        ]);

        if ($response->successful()) {
            $accessToken = $response->json('access_token');

           
        $shp = Shop::where('user_id',$userId)->orderBy('id','desc')->first();
        
       
        if($shp){
        $shp->update([
            'access_token' => $accessToken
        ]);
            $integration_id = $shp->id;
        }
   
    $shopify = new Shopify(
    $accessToken,
    env('SHOPIFY_DOMAIN'),
    env('SHOPIFY_API_VERSION')
    );

 try {
    $shopify = \Signifly\Shopify\Factory::fromConfig();
    

    $val=Product::where('integration_id',$integration_id)->count();
    $val2=Sale::where('integration_id',$integration_id)->count();

      //  if($val==0){  
       $count2=0;
      $count= $this->sync_products($shopify,$integration_id);
      $count1= $this->sync_customers($shopify,$integration_id);
   //   $count2= $this->sync_orders($shopify,$integration_id);
     //   }
       // else
        //    return redirect('/integrations')->with('message', 'Please use sync options');

        
        }
   catch (Response $e) {
       $f=$e->getMessage();
   }
         //   return redirect('/integrations')->with('message', $count.' products addess successfully');
         if($val>0 or $val2>0)
          return redirect('/integrations')->with('message','Shopify store synced successfully');
        //    return redirect('/integrations')->with('message', $count-$val.' products and '. $count2-$val2.' orders synced successfully');
        //    else
        //    return redirect('/integrations')->with('message', $count.' products and '. $count2.' orders synced successfully');
        }

        return redirect('/integrations')->with('error', 'Failed to get access token');
    }

    public function sync_products($shopify,$integration_id){
        $i=0;
        $products = $shopify->getProducts();
        foreach($products as $r){
            $d=Product::updateOrcreate([
            'name' => $r->title,
    'code' => $r->id,
    'type' => $r->product_type,
    'barcode_symbology' => 'test',
    'category_id' => 1, // Assuming tags are mapped to category_id
    'unit_id' => 1,
    'is_active' => 1,
    'tax_method' => 1,
    'integration_id' => $integration_id,
    'purchase_unit_id' => 1,
    'sale_unit_id' => 1,
    'price' => isset($r->variants[0]) ? $r->variants[0]['price'] : null, // Use the price of the first variant
    'cost' => isset($r->variants[0]) ? $r->variants[0]['price'] : null,
    'image' => $r->image['src'] ?? null],['code'=>$r->id]);// Default to null if image is not set
          ;$i++;
                
        } //end for
        return $i;
    }

    public function sync_customers($shopify,$integration_id){
        $i=0;
        $customers = $shopify->getCustomers();
  
        foreach($customers as $c){ //dd($r->addresses[0]);

  $data['email'] = $c->email;
  $addresses = json_decode(json_encode($c->addresses));
  
  foreach (collect($addresses) as $r) {
      $data = [
          'name' => $r->first_name . ' ' . $r->last_name,
          'company_name' => $r->company,
          'phone_number' => $r->phone,
          'customer_group_id' => 1,
          'address' => $r->address1 . ' ' . $r->address2,
          'city' => $r->city,
          'country' => $r->country_name,
          'state' => $r->province,
          'postal_code' => $r->zip,
          'is_active' => 1,
          'integration_id' => $integration_id,
          'woo_customer_id' => $r->customer_id,
      ];
  
      // Update or create based on unique fields such as email and woo_customer_id
      Customer::updateOrCreate(
          ['email' => $c->email, 'woo_customer_id' => $r->customer_id],
          $data
      );
  
      $i++;
  }
  
        
             
        } //end for
    }

    public function sync_orders($shopify,$integration_id){
        $i=0;
        $orders = $shopify->getOrders();
     
        $userId = auth()->id();
        $product_sale = [];

        foreach($orders as $r){
      
            /*
                $customer_id=Customer::updateOrCreate(['woo_customer_id'=>$r->customer['default_address']['id']],
                ['email'=>$r->customer['email'], 'name' => $r->customer['default_address']['first_name'] . ' ' . $r->customer['default_address']['last_name'],
            
                'phone_number' => $r->customer['default_address']['phone'],
                'customer_group_id' => 1,
                'address' => $r->customer['default_address']['address1'] . ' ' . $r->customer['default_address']['address2'],
                'city' => $r->customer['default_address']['city'],
                'country' => $r->customer['default_address']['country_name'],
                'state' => $r->customer['default_address']['province'],
                'postal_code' =>$r->customer['default_address']['zip'],
                'is_active' => 1,
                'integration_id' =>$integration_id
            
            
            ]);*/
            
            
            $defaultAddress = $r->customer['default_address'] ?? null;

            $customer_id = Customer::updateOrCreate(
                ['woo_customer_id' => $defaultAddress['id'] ?? null],
                [
                    'email' => $r->customer['email'] ?? null,
                    'name' => ($defaultAddress['first_name'] ?? '') . ' ' . ($defaultAddress['last_name'] ?? ''),
                    'phone_number' => $defaultAddress['phone'] ?? null,
                    'customer_group_id' => 1,
                    'address' => ($defaultAddress['address1'] ?? '') . ' ' . ($defaultAddress['address2'] ?? ''),
                    'city' => $defaultAddress['city'] ?? null,
                    'country' => $defaultAddress['country_name'] ?? null,
                    'state' => $defaultAddress['province'] ?? null,
                    'postal_code' => $defaultAddress['zip'] ?? null,
                    'is_active' => 1,
                    'integration_id' => $integration_id
                ]
            );

/*
            if(is_null($r->billing_address['company']))
            $company='No Company';
            else
            $company=$r->billing_address['company'];
*/

            $company = isset($r->billing_address) ? ($r->billing_address['company'] ?? 'No Company') : 'No Company';


            $billingAddress = $r->billing_address ?? null;
          


            $biller=Biller::updateOrCreate(['woo_customer_id'=> $defaultAddress['id'] ?? null],
            [
                 'email' => $r->customer['email'] ?? null,
                    'name' => ($defaultAddress['first_name'] ?? '') . ' ' . ($defaultAddress['last_name'] ?? ''),
                    'company_name' => $company,
                    'phone_number' => $defaultAddress['phone'] ?? null,
                    'address' => ($defaultAddress['address1'] ?? '') . ' ' . ($defaultAddress['address2'] ?? ''),
                    'city' => $defaultAddress['city'] ?? null,
                    'country' => $defaultAddress['country_name'] ?? null,
                    'state' => $defaultAddress['province'] ?? null,
                    'postal_code' => $defaultAddress['zip'] ?? null,
                    'is_active' => 1,
                    'integration_id' => $integration_id    
        
        ]);
        
        
    $status=1;
           
            switch($r->financial_status){
                case 'pending':
                    $status=1;
                    break;
             
                case 'due':
                    $status=2;
                    break;
                case 'confirmed':
                    $status=4;
                    break;
            }
        
            /*
	@if($sale_data['payment_status']==1){{'Pending'}}
	@elseif($sale_data['payment_status']==2){{'Due'}}
	@elseif($sale_data['payment_status']==3){{'Partial'}}
	@else{{'Paid'}}@endif
*/

$d=Sale::updateOrCreate(
    [
        'woocommerce_order_id' => $r->id // Unique condition to find or create the record
    ],
    [
        'user_id' => $userId,
        'reference_no' => $r->order_number,
       // 'woocommerce_order_id' => $r->id,
        'item' => count($r->line_items), // Update as needed
        'total_qty' => count($r->line_items),
        'total_tax' => $r->total_tax,
        'customer_id' => $customer_id->id,
        'biller_id' => $biller->id,
        'total_discount' => $r->total_discounts,
        'total_price' => $r->total_price,
        'grand_total' => $r->total_price - $r->total_discounts,
        'payment_status' => $status,
        'sale_status' => $status,
        'prices_include_tax' => $r->total_price,
        'sale_note' => $r->confirmed,
        'created_at' => $r->created_at,
        'integration_id' => $integration_id
    ]
);
   
            $product_sale['variant_id'] = null;
            $product_sale['product_batch_id'] = null;
            $product_sale['variant_id'] =null;
$j=0;
            foreach($r->line_items as $li){ 
                $item=json_decode(json_encode($li));
 

                $product = Product::select('id')->where('code', $item->product_id)->first();
          
                if ($product) {
                $firstTaxLine = (array) $item->tax_lines;
                    if($item->variant_title)
                        $v=Variant::updateOrCreate(['woo_variant_id'=>$item->variant_id],['name'=>$item->variant_title,'integration_id' => $integration_id]);
                    Product_Sale::updateOrCreate(
                        [
                            'product_id' => $product->id, // Unique condition to find or create the record
                            'sale_id' => $d->id
                        ],
                        [
                            'imei_number' => $item->sku,
                            'qty' => $item->quantity,
                            'sale_unit_id' => $j++,  // Assuming this is being incremented as intended
                            'variant_id' => $v->id ?? null,
                            'net_unit_price' => $item->price,
                            'discount' => $item->total_discount,
                            'tax_rate' => 1,
                            'tax' => 0, // or $item->tax_lines[0]->amount if you want to use the actual tax
                            'total' => $item->quantity * $item->price
                        ]
                    );
                    
               }
            }
               /*
               foreach($r->billing_address as $ba){

               }
               foreach($r->shipping_address as $sa){
                
               }
               */
               $i++;
            
        } //end for
    }

    public function dashboard($shop)
    {
        // Fetch shop data and display it
        $shopData = Shop::where('shop_domain', $shop)->first();

        return view('shopify.dashboard', compact('shopData'));
    }
}
