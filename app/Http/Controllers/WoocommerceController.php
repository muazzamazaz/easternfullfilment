<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Signifly\Woocommerce\Woocommerce;
use App\Models\Woo;
use App\Models\Sale;
use App\Models\Biller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\product_sales;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Variant;
use Automattic\WooCommerce\Client;
use App\Models\Product_Sale;

class WoocommerceController extends Controller
{
        protected static $remote_store;
        protected $val;
        
    public function auth(Request $request)
    {
        $data = $request->all();
        $userId = auth()->id();
        
        $local_store = env('local_store');
        $remote_server=$request->remote_store;
          Woo::updateOrCreate(
                ['woocommerce_domain' => $remote_server,'integration_id'=>$data['integration_id']],['user_id' => $userId]
            );
        $endpoint = 'wc-auth/v1/authorize';
        $params = [
        'app_name' => 'Eastern Fullfilment',
        'scope' => 'read',
        'user_id' => $userId,
        'return_url' => env('local_store').'/woo/connect/response/',
        'callback_url' => env('local_store').'/woo/connect/callback/'
        ];
        $api = $request->remote_store.$endpoint.'?'.http_build_query( $params );
    
        return Redirect::away($api);
   }

    public function callback(Request $request)
    {
        $userId = $request->user_id;
        $data=$request->all();
        $woo = Woo::where('user_id',$userId)->orderBy('id','desc')->first();
        
       
        if($woo){
        $woo->update([
            'consumer_key' => $request->input('consumer_key'),
            'consumer_secret' => $request->input('consumer_secret'),
        ]);
                $integration_id = $woo->id;
       
        
        try {       
          

       // if($val==0){    
            $woocommerce = new Client(
                $woo->woocommerce_domain,
                $data['consumer_key'],
                $data['consumer_secret'],
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                ]
                );

           $count= $this->sync_products($woocommerce,$data,$integration_id);
            $this->sync_customers($woocommerce,$data,$integration_id,$userId);
            $this->sync_orders($woocommerce,$data,$integration_id,$userId);
       //     }
       // else{
        //            return redirect('/integrations')->with('error', "Please use sync option.");

     //   }
        
        }
        catch (HttpClientException $e) {
            $f=$e->getMessage();
        return redirect('/integrations')->with('error', $f);
        }       
        
    }//en dif woo
    }

    private function sync_products($woocommerce,$data,$integration_id){
        $array=$woocommerce->get('products');
            $i=0;
            foreach($array as $r){

            Product::updateOrCreate([
                'name' => $r->name,
                'code' => $r->id,
                'type' => $r->type,
                'barcode_symbology' => 'test',
                'category_id' => 1,
                'tax_method' => 1,
                'unit_id' => 1,
                'purchase_unit_id' => 1,
                'sale_unit_id' => $i,
                'is_active' => 1,
                'integration_id' => $integration_id,
                'price' => $r->price,
                'cost' => $r->price,
                'image' => $r->image['src'] ?? null],
            ['code' => $r->id]);
           
            $i++;
            }
            return $i;
    }

    private function sync_customers($woocommerce,$data,$integration_id,$userId){
        $array=$woocommerce->get('customers');
            $i=0;
          

            foreach($array as $r){
                
                Customer::updateOrCreate([
                    'name' => $r->first_name.' '.$r->last_name,
                    'address' => json_encode($r->billing),
                    'integration_id'=>$integration_id,
                    'email' => $r->email,
                    'customer_group_id'=>1,
                    'is_active'=>1
                ],['user_id' => $userId,'woo_customer_id'=>$r->id]
                );
                /*
            $data['name']=$r->first_name.' '.$r->last_name;
          //  $data['company_name']=$r->company;
            $data['email']=$r->email;
            $data['customer_group_id']=1;

         //   $data['phone_number']=$r->phone;
            $data['address']=$r->address_1.' '.$r->address_2;
            $data['user_id']=$userId;
      //      $data['city']=$r->city;
     //       $data['country']=$r->country;
   //         $data['state']=$r->state;
   //         $data['postal_code']=$r->postcode;
            $data['is_active']=1;
            $data['integration_id']=$integration_id;
            $data['woo_customer_id']=$r->id;
            $d=Customer::create($data);
            */$i++;
            }
    }

    private function sync_orders($woocommerce,$data,$integration_id,$userId){
        $array=$woocommerce->get('orders');
        Log::info($array);
        $sale_unit_id=1;
            $i=0;

            foreach($array as $r){
             /*   
             "cash_register_id", "table_id", "queue", "", "warehouse_id", "biller_id", "item", "",
                 "total_discount", "", "", "order_tax_rate", "order_tax", "order_discount_type", "order_discount_value", "", 
                 "coupon_id", "coupon_discount", "", "", "currency_id", "exchange_rate", "", "", "sale_type",
                  "paid_amount", "document", "", "staff_note", "", ""
        
*/
//pending, processing, on-hold, completed, cancelled, refunded, failed and trash. Default is pending
switch($r->status){
    case 'pending':
        $status=1;
        break;
        case 'cancelled':
            $status=3;
            break;
            case 'refunded':
                $status=5;
                break;
                case 'failed':
                    $status=6;
                    break;

                    case 'trash':
                        $status=7;
                        break;
    case 'on-hold':
        $status=2;
        break;
    case 'completed':
        $status=4;
        break;
        default:
            $status='1';
}

        $customer = Customer::select('id')->where('woo_customer_id', $r->customer_id)->first();
        
        if(!empty($r->customer_id) && $r->customer_id>0){
        if(empty($customer)){
            $customer1 = $woocommerce->get("customers/{$r->customer_id}");
        foreach($customer1 as $b){ 
            $customer = Customer::updateOrCreate([
            'name' => $b->first_name.' '.$b->last_name,
            'address' => $b->address_1.' '.$b->address_2,
            'integration_id'=>$integration_id,
            'email' => $b->email,
            'customer_group_id'=>1,
            'is_active'=>1
        ],['user_id' => $userId,'woo_customer_id'=>$r->customer_id]
        );
        }
        }

            $biller=Biller::updateOrCreate(['woo_customer_id'=>$r->customer_id],
            ['email'=>$r->billing['email'], 'name' => $r->billing['first_name'] . ' ' . $r->billing['last_name'],
            'company_name' => $r->billing['company'],
            'phone_number' => $r->billing['phone'],
            'address' => $r->billing['address_1'] . ' ' . $r->billing['address_2'],
            'city' => $r->billing['city'],
            'country' => $r->billing['country'],
            'state' => $r->billing['sate'],
            'postal_code' =>$r->billing['postcode'],
            'is_active' => 1,
            'integration_id' =>$integration_id      
        
        ]);
    

         $d = Sale::updateOrCreate(['reference_no' => $r->number],[
            'user_id' => $userId,
          //  'reference_no' => $r->number,
            'customer_id' =>$customer->id,
            'woocommerce_order_id' => $r->id,
            'biller_id' => $customer->id,
            'total_price' => $r->total,
            'total_qty' => count($r->line_items),
            'total_tax' => $r->total_tax,
            'total_discount' => $r->discount_total,
            'grand_total' => $r->total - $r->discount_total,
            'item' => count($r->line_items), // Update this logic if needed
            'prices_include_tax' => $r->prices_include_tax,
            'sale_status' => $status,
            'payment_status' => $status,
            'sale_note' => $r->customer_note,
            'created_at' => $r->date_created,
            'integration_id' => $integration_id
            ]);
       
            
         
            $i++;


            
            $product_sale['product_batch_id'] = null;
            $product_sale['variant_id'] =null;
/*
id	integer	Item ID.read-only
name	string	Product name.
product_id	integer	Product ID.
variation_id	integer	Variation ID, if applicable.
quantity	integer	Quantity ordered.
tax_class	string	Slug of the tax class of product.
subtotal	string	Line subtotal (before discounts).
subtotal_tax	string	Line subtotal tax (before discounts).read-only
total	string	Line total (after discounts).
total_tax	string	Line total tax (after discounts).read-only
taxes	array	Line taxes. See Order - Tax lines propertiesread-only
meta_data	array	Meta data. See Order - Meta data properties
sku	string	Product SKU.read-only
price */
            foreach($r->line_items as $item){ 
                
                $product = Product::select('id')->where('code', $item->product_id)->first();

                $v=Variant::updateOrCreate(['woo_variant_id'=>$item->variant_id],['name'=>$item->variant_title,'integration_id' => $integration_id]);

                if ($product) {
                    Product_Sale::UpdateOrCreate([
                    'product_id' => $product->id,
                    'imei_number' => $item->sku,
                    'qty' => $item->quantity,
                    'variant_id' => $v->id,
                    'sale_unit_id' => $item->id,
                    'net_unit_price' => $item->price,
                    'discount' => $r->discount_total,
                    // 'tax_rate' => $item->id, // Uncomment if needed
                    'tax' => $item->total_tax,
                    'total' => $item->total],['sale_id' => $d->id]);
        
                }
            }// line item for loop
            }
            }// end orders loop
    }

    public function response(Request $request)
    {
 
        try {
        
        $data = $request->all();
        $userId = $request->user_id;
        $integration_id = Woo::where('user_id', $userId)
                     ->orderBy('id', 'desc')
                     ->value('id');
                
        $val=Product::where('integration_id',$integration_id)->count();
        $val2=Sale::where('integration_id',$integration_id)->count();
      
        return redirect('/integrations')->with('message', $val.' products and '. $val2.' orders synced successfully');

        } catch (\Exception $e) {
            
                dd($e->getMessage());
            }
    }
}
