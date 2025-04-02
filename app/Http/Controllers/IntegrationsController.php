<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Integration;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Shop;
use App\Models\Account;
use App\Models\Warehouse;
use App\Models\ProductIntegration;
use App\Models\Payment;
use App\Models\PaymentWithCheque;
use App\Models\PaymentWithCreditCard;
use App\Models\PosSetting;
use App\Models\Currency;
use App\Models\CustomField;
use DB;
use App\Models\GeneralSetting;
use Stripe\Stripe;
use Auth;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\ProductBatch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Traits\TenantInfo;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ShopifyController;
use Signifly\Shopify\Shopify;

class IntegrationsController extends Controller
{
    use TenantInfo;
    protected $shopifyController;

    public function __construct(ShopifyController $shopifyController)
    {
        $this->shopifyController = $shopifyController;
    }

    public function index(Request $request)
    {
      //  $role = Role::find(Auth::user()->role_id);
     //   if($role->hasPermissionTo('integrations-index')) {
            
           // $permissions = Role::findByName($role->name)->permissions;
         /*   foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
           */ 
            $integration_list = Integration::where('is_active', true)->get();
           
            return view('backend.integrations.index', compact( 'integration_list'/*, 'all_permission'*/));
      //  }
     //   else
     //       return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        
        //  return view('backend.integrations.index');
    }

    public function integrationData(Request $request)
    {
        $integration = Integration::all();
      $woo_integration = Integration::join('woocommerce', 'integrations.id', '=', 'woocommerce.integration_id')
    ->where('woocommerce.user_id', Auth::id())
    ->select('woocommerce.id as id','name', 'woocommerce.woocommerce_domain as domain','woocommerce.created_at as created_at');

$shop_integration = Integration::join('shops', 'shops.integration_id', '=', 'integrations.id')
    ->where('shops.user_id', Auth::id())
    ->select('shops.id as id','name', 'shops.shop_domain as domain','shops.created_at as created_at');

// Using unionAll to merge the two queries
$integrations = $woo_integration->unionAll($shop_integration)->get();
 
                        $totalFiltered = $integrations->count();
       $totalData = Integration::count(); // Define the total number of records before filtering

        $data = array();
        if(!empty($integrations))
        {
            foreach ($integrations as $key=>$integration)
            {
                $nestedData['id'] = $integration->id;
                $nestedData['key'] = $key;
                $nestedData['integration_type'] = $integration->name;
                $nestedData['link'] = $integration->domain;
                $nestedData['date'] = date(config('date_format'), strtotime($integration->created_at->toDateString()));
            
                $nestedData['options'] = '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.trans("file.action").'
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i> '.trans('file.View').'</button>
                                </li>';
                    $nestedData['options'] .= '<li>
                        <a href="'.route('integration.data', $integration->id).'" class="btn btn-link"><i class="fa fa-copy"></i> '.trans('file.Duplicate').'</a>
                        </li>';
                        
                          $nestedData['options'] .=
                        '<li>

                            <button type="button" class="get-sync btn btn-link" data-id = "'.$integration->id.'"><i class="fa fa-refresh "></i> '.trans('file.Sync').'</button>
                        </li>';
                        
                    $nestedData['options'] .= '<li>
                        <a href="'.route('integration.data', $integration->id).'" class="btn btn-link"><i class="dripicons-document-edit"></i> '.trans('file.edit').'</a>
                        </li>';
                    $nestedData['options'] .= \Form::open(["route" => ["integration.data", $integration->id], "method" => "DELETE"] ).'
                            <li>
                              <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> '.trans("file.delete").'</button>
                            </li>'.\Form::close().'
                        </ul>
                    </div>';

                // data for integration details by one click
              //  $user = User::find($integration->user_id);
           
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function getsync($id){
        
        $shp=Shop::find($id);
        
        $shopify = new Shopify($shp->access_token,$shp->shop_domain, env('SHOPIFY_API_VERSION'));
        
     //   $shopify = \Signifly\Shopify\Factory::fromConfig();
        
        $count= $this->shopifyController->sync_products($shopify,$id);
      
        $count1= $this->shopifyController->sync_customers($shopify,$id);
        
        $count2= $this->shopifyController->sync_orders($shopify,$id);
        
         return response()->json([
            'redirect_url' => route('integrations.index'),
            'message' => 'Action completed successfully!',
        ]);
    }
    public function create()
    { 
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('integrations-add')){
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_integration_list = Integration::where('is_active', true)->whereNot('id',2)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_product_list_without_variant = $this->productWithoutVariant();
            $lims_product_list_with_variant = $this->productWithVariant();
            $currency_list = Currency::where('is_active', true)->get();
            $custom_fields = CustomField::where('belongs_to', 'integration')->get();
            return view('backend.integrations.create', compact('lims_supplier_list', 'lims_integration_list', 'lims_tax_list', 'lims_product_list_without_variant', 'lims_product_list_with_variant', 'currency_list', 'custom_fields'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function productWithoutVariant()
    {
        return Product::ActiveStandard()->select('id', 'name', 'code')
                ->whereNull('is_variant')->get();
    }

    public function productWithVariant()
    {
        return Product::join('product_variants', 'products.id', 'product_variants.product_id')
            ->ActiveStandard()
            ->whereNotNull('is_variant')
            ->select('products.id', 'products.name', 'product_variants.item_code')
            ->orderBy('position')
            ->get();
    }

    public function newProductWithVariant()
    {
        return Product::ActiveStandard()
                ->whereNotNull('is_variant')
                ->whereNotNull('variant_data')
                ->select('id', 'name', 'variant_data')
                ->get();
    }

    public function limsProductSearch(Request $request)
    {
        $product_code = explode("|", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $lims_product_data = Product::where([
                                ['code', $product_code[0]],
                                ['is_active', true]
                            ])
                            ->whereNull('is_variant')
                            ->first();
        if(!$lims_product_data) {
            $lims_product_data = Product::where([
                                ['name', $product_code[1]],
                                ['is_active', true]
                            ])
                            ->whereNotNull(['is_variant'])
                            ->first();
            $lims_product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->where([
                    ['product_variants.item_code', $product_code[0]],
                    ['products.is_active', true]
                ])
                ->whereNotNull('is_variant')
                ->select('products.*', 'product_variants.item_code', 'product_variants.additional_cost')
                ->first();
            $lims_product_data->cost += $lims_product_data->additional_cost;
        }
        $product[] = $lims_product_data->name;
        if($lims_product_data->is_variant)
            $product[] = $lims_product_data->item_code;
        else
            $product[] = $lims_product_data->code;
        $product[] = $lims_product_data->cost;

        if ($lims_product_data->tax_id) {
            $lims_tax_data = Tax::find($lims_product_data->tax_id);
            $product[] = $lims_tax_data->rate;
            $product[] = $lims_tax_data->name;
        } else {
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $lims_product_data->tax_method;

        $units = Unit::where("base_unit", $lims_product_data->unit_id)
                    ->orWhere('id', $lims_product_data->unit_id)
                    ->get();
        $unit_name = array();
        $unit_operator = array();
        $unit_operation_value = array();
        foreach ($units as $unit) {
            if ($lims_product_data->integration_unit_id == $unit->id) {
                array_unshift($unit_name, $unit->unit_name);
                array_unshift($unit_operator, $unit->operator);
                array_unshift($unit_operation_value, $unit->operation_value);
            } else {
                $unit_name[]  = $unit->unit_name;
                $unit_operator[] = $unit->operator;
                $unit_operation_value[] = $unit->operation_value;
            }
        }

        $product[] = implode(",", $unit_name) . ',';
        $product[] = implode(",", $unit_operator) . ',';
        $product[] = implode(",", $unit_operation_value) . ',';
        $product[] = $lims_product_data->id;
        $product[] = $lims_product_data->is_batch;
        $product[] = $lims_product_data->is_imei;
        return $product;
    }

    public function store(Request $request)
    {
    try {
        $this->validate($request, [
        'domain' => 'required',
        'integration_id' => 'required|exists:integrations,id',
        ]);
        
        $data['user_id'] = Auth::id();
        if(isset($data['created_at']))
            $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        else
            $data['created_at'] = date("Y-m-d H:i:s");
            
        if($request->integration_id ==2)
            return  redirect()->route("shopify.auth",["shop"=>$request->domain,"integration_id"=>$request->integration_id]);
        elseif($request->integration_id==4)
            return redirect()->route('woo.authorize', ['remote_store' => $request->domain,"integration_id"=>$request->integration_id]);
            
            
    } catch (ValidationException $e) {
        // Handle validation exception
        return response()->json(['errors' => $e->errors()], 422);
    }



    }

    public function productIntegrationData($id)
    {
        try {
            $lims_product_integration_data = ProductIntegration::where('integration_id', $id)->get();
            $product_integration = [];
            foreach ($lims_product_integration_data as $key => $product_integration_data) {
                $product = Product::find($product_integration_data->product_id);
                $unit = Unit::find($product_integration_data->integration_unit_id);
                if($product_integration_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::FindExactProduct($product->id, $product_integration_data->variant_id)->select('item_code')->first();
                    $product->code = $lims_product_variant_data->item_code;
                }
                if($product_integration_data->product_batch_id) {
                    $product_batch_data = ProductBatch::select('batch_no')->find($product_integration_data->product_batch_id);
                    $product_integration[7][$key] = $product_batch_data->batch_no;
                }
                else
                    $product_integration[7][$key] = 'N/A';
                $product_integration[0][$key] = $product->name . ' [' . $product->code.']';
                if($product_integration_data->imei_number) {
                    $product_integration[0][$key] .= '<br>IMEI or Serial Number: '. $product_integration_data->imei_number;
                }
                $product_integration[1][$key] = $product_integration_data->qty;
                $product_integration[2][$key] = $unit->unit_code;
                $product_integration[3][$key] = $product_integration_data->tax;
                $product_integration[4][$key] = $product_integration_data->tax_rate;
                $product_integration[5][$key] = $product_integration_data->discount;
                $product_integration[6][$key] = $product_integration_data->total;
            }
            return $product_integration;
        }
        catch (Exception $e) {
            /*return response()->json('errors' => [$e->getMessage());*/
            //return response()->json(['errors' => [$e->getMessage()]], 422);
            return 'Something is wrong!';
        }

    }

    public function integrationByCsv()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('integrations-add')){
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_integration_list = Integration::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();

            return view('backend.integrations.import', compact('lims_supplier_list', 'lims_integration_list', 'lims_tax_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('integrations-edit')){
            $lims_supplier_list = Supplier::where('is_active', true)->get();
            $lims_integration_list = Integration::where('is_active', true)->get();
            $lims_tax_list = Tax::where('is_active', true)->get();
            $lims_product_list_without_variant = $this->productWithoutVariant();
            $lims_product_list_with_variant = $this->productWithVariant();
            $lims_integration_data = Integration::find($id);
            $lims_product_integration_data = ProductIntegration::where('integration_id', $id)->get();
            if($lims_integration_data->exchange_rate)
                $currency_exchange_rate = $lims_integration_data->exchange_rate;
            else
                $currency_exchange_rate = 1;
            $custom_fields = CustomField::where('belongs_to', 'integration')->get();
            return view('backend.integrations.edit', compact('lims_integration_list', 'lims_supplier_list', 'lims_product_list_without_variant', 'lims_product_list_with_variant', 'lims_tax_list', 'lims_integration_data', 'lims_product_integration_data', 'currency_exchange_rate', 'custom_fields'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');

    }

   public function update(Request $request, $id)
    {
        $lims_integration_data = Integration::find($id);
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $this->fileDelete('documents/integration/', $lims_integration_data->document);

            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = date("Ymdhis");
            if(!config('database.connections.saleprosaas_landlord')) {
                $documentName = $documentName . '.' . $ext;
                $document->move('public/documents/integration', $documentName);
            }
            else {
                $documentName = $this->getTenantId() . '_' . $documentName . '.' . $ext;
                $document->move('public/documents/integration', $documentName);
            }
            $data['document'] = $documentName;
        }
        //return dd($data);
        DB::beginTransaction();
        try {
            $balance = $data['grand_total'] - $data['paid_amount'];
            if ($balance < 0 || $balance > 0) {
                $data['payment_status'] = 1;
            } else {
                $data['payment_status'] = 2;
            }
            $lims_product_integration_data = ProductIntegration::where('integration_id', $id)->get();

            $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
            $product_id = $data['product_id'];
            $product_code = $data['product_code'];
            $qty = $data['qty'];
            $recieved = $data['recieved'];
            $batch_no = $data['batch_no'];
            $expired_date = $data['expired_date'];
            $integration_unit = $data['integration_unit'];
            $net_unit_cost = $data['net_unit_cost'];
            $discount = $data['discount'];
            $tax_rate = $data['tax_rate'];
            $tax = $data['tax'];
            $total = $data['subtotal'];
            $imei_number = $new_imei_number = $data['imei_number'];
            $product_integration = [];

            foreach ($lims_product_integration_data as $product_integration_data) {

                $old_recieved_value = $product_integration_data->recieved;
                $lims_integration_unit_data = Unit::find($product_integration_data->integration_unit_id);

                if ($lims_integration_unit_data->operator == '*') {
                    $old_recieved_value = $old_recieved_value * $lims_integration_unit_data->operation_value;
                } else {
                    $old_recieved_value = $old_recieved_value / $lims_integration_unit_data->operation_value;
                }
                $lims_product_data = Product::find($product_integration_data->product_id);
                if($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProduct($lims_product_data->id, $product_integration_data->variant_id)->first();
                    $lims_product_integration_data = Product_Integration::where([
                        ['product_id', $lims_product_data->id],
                        ['variant_id', $product_integration_data->variant_id],
                        ['integration_id', $lims_integration_data->integration_id]
                    ])->first();
                    $lims_product_variant_data->qty -= $old_recieved_value;
                    $lims_product_variant_data->save();
                }
                elseif($product_integration_data->product_batch_id) {
                    $product_batch_data = ProductBatch::find($product_integration_data->product_batch_id);
                    $product_batch_data->qty -= $old_recieved_value;
                    $product_batch_data->save();

                    $lims_product_integration_data = Product_Integration::where([
                        ['product_id', $product_integration_data->product_id],
                        ['product_batch_id', $product_integration_data->product_batch_id],
                        ['integration_id', $lims_integration_data->integration_id],
                    ])->first();
                }
                else {
                    $lims_product_integration_data = Product_Integration::where([
                        ['product_id', $product_integration_data->product_id],
                        ['integration_id', $lims_integration_data->integration_id],
                    ])->first();
                }
                if($product_integration_data->imei_number) {
                    $position = array_search($lims_product_data->id, $product_id);
                    if($imei_number[$position]) {
                        $prev_imei_numbers = explode(",", $product_integration_data->imei_number);
                        $new_imei_numbers = explode(",", $imei_number[$position]);
                        foreach ($prev_imei_numbers as $prev_imei_number) {
                            if(($pos = array_search($prev_imei_number, $new_imei_numbers)) !== false) {
                                unset($new_imei_numbers[$pos]);
                            }
                        }
                        $new_imei_number[$position] = implode(",", $new_imei_numbers);
                    }
                }
                $lims_product_data->qty -= $old_recieved_value;
                if($lims_product_integration_data) {
                    $lims_product_integration_data->qty -= $old_recieved_value;
                    $lims_product_integration_data->save();
                }
                $lims_product_data->save();
                $product_integration_data->delete();
            }

            foreach ($product_id as $key => $pro_id) {
                $lims_integration_unit_data = Unit::where('unit_name', $integration_unit[$key])->first();
                if ($lims_integration_unit_data->operator == '*') {
                    $new_recieved_value = $recieved[$key] * $lims_integration_unit_data->operation_value;
                } else {
                    $new_recieved_value = $recieved[$key] / $lims_integration_unit_data->operation_value;
                }

                $lims_product_data = Product::find($pro_id);
                $price = null;
                //dealing with product barch
                if($batch_no[$key]) {
                    $product_batch_data = ProductBatch::where([
                                            ['product_id', $lims_product_data->id],
                                            ['batch_no', $batch_no[$key]]
                                        ])->first();
                    if($product_batch_data) {
                        $product_batch_data->qty += $new_recieved_value;
                        $product_batch_data->expired_date = $expired_date[$key];
                        $product_batch_data->save();
                    }
                    else {
                        $product_batch_data = ProductBatch::create([
                                                'product_id' => $lims_product_data->id,
                                                'batch_no' => $batch_no[$key],
                                                'expired_date' => $expired_date[$key],
                                                'qty' => $new_recieved_value
                                            ]);
                    }
                    $product_integration['product_batch_id'] = $product_batch_data->id;
                }
                else
                    $product_integration['product_batch_id'] = null;

                if($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                    $lims_product_integration_data = Product_Integration::where([
                        ['product_id', $pro_id],
                        ['variant_id', $lims_product_variant_data->variant_id],
                        ['integration_id', $data['integration_id']]
                    ])->first();
                    $product_integration['variant_id'] = $lims_product_variant_data->variant_id;
                    //add quantity to product variant table
                    $lims_product_variant_data->qty += $new_recieved_value;
                    $lims_product_variant_data->save();
                }
                else {
                    $product_integration['variant_id'] = null;
                    if($product_integration['product_batch_id']) {
                        //checking for price
                        $lims_product_integration_data = Product_Integration::where([
                                                        ['product_id', $pro_id],
                                                        ['integration_id', $data['integration_id'] ],
                                                    ])
                                                    ->whereNotNull('price')
                                                    ->select('price')
                                                    ->first();
                        if($lims_product_integration_data)
                            $price = $lims_product_integration_data->price;
                            
                        $lims_product_integration_data = Product_Integration::where([
                            ['product_id', $pro_id],
                            ['product_batch_id', $product_integration['product_batch_id'] ],
                            ['integration_id', $data['integration_id'] ],
                        ])->first();
                    }
                    else {
                        $lims_product_integration_data = Product_Integration::where([
                            ['product_id', $pro_id],
                            ['integration_id', $data['integration_id'] ],
                        ])->first();
                    }
                }

                $lims_product_data->qty += $new_recieved_value;
                if($lims_product_integration_data){
                    $lims_product_integration_data->qty += $new_recieved_value;
                    $lims_product_integration_data->save();
                }
                else {
                    $lims_product_integration_data = new Product_Integration();
                    $lims_product_integration_data->product_id = $pro_id;
                    $lims_product_integration_data->product_batch_id = $product_integration['product_batch_id'];
                    if($lims_product_data->is_variant)
                        $lims_product_integration_data->variant_id = $lims_product_variant_data->variant_id;
                    $lims_product_integration_data->integration_id = $data['integration_id'];
                    $lims_product_integration_data->qty = $new_recieved_value;
                    if($price)
                        $lims_product_integration_data->price = $price;
                }
                //dealing with imei numbers
                if($imei_number[$key]) {
                    if($lims_product_integration_data->imei_number) {
                        $lims_product_integration_data->imei_number .= ',' . $new_imei_number[$key];
                    }
                    else {
                        $lims_product_integration_data->imei_number = $new_imei_number[$key];
                    }
                }

                $lims_product_data->save();
                $lims_product_integration_data->save();

                $product_integration['integration_id'] = $id ;
                $product_integration['product_id'] = $pro_id;
                $product_integration['qty'] = $qty[$key];
                $product_integration['recieved'] = $recieved[$key];
                $product_integration['integration_unit_id'] = $lims_integration_unit_data->id;
                $product_integration['net_unit_cost'] = $net_unit_cost[$key];
                $product_integration['discount'] = $discount[$key];
                $product_integration['tax_rate'] = $tax_rate[$key];
                $product_integration['tax'] = $tax[$key];
                $product_integration['total'] = $total[$key];
                $product_integration['imei_number'] = $imei_number[$key];
                ProductIntegration::create($product_integration);
            }
            DB::commit();
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        $lims_integration_data->update($data);
        //inserting data for custom fields
        $custom_field_data = [];
        $custom_fields = CustomField::where('belongs_to', 'integration')->select('name', 'type')->get();
        foreach ($custom_fields as $type => $custom_field) {
            $field_name = str_replace(' ', '_', strtolower($custom_field->name));
            if(isset($data[$field_name])) {
                if($custom_field->type == 'checkbox' || $custom_field->type == 'multi_select')
                    $custom_field_data[$field_name] = implode(",", $data[$field_name]);
                else
                    $custom_field_data[$field_name] = $data[$field_name];
            }
        }
        if(count($custom_field_data))
            DB::table('integrations')->where('id', $lims_integration_data->id)->update($custom_field_data);
        return redirect('integrations')->with('message', 'Integration updated successfully');
    }

    public function addPayment(Request $request)
    {
        $data = $request->all();
        $lims_integration_data = Integration::find($data['integration_id']);
        $lims_integration_data->paid_amount += $data['amount'];
        $balance = $lims_integration_data->grand_total - $lims_integration_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $lims_integration_data->payment_status = 1;
        elseif ($balance == 0)
            $lims_integration_data->payment_status = 2;
        $lims_integration_data->save();

        if($data['paid_by_id'] == 1)
            $paying_method = 'Cash';
        elseif ($data['paid_by_id'] == 2)
            $paying_method = 'Gift Card';
        elseif ($data['paid_by_id'] == 3)
            $paying_method = 'Credit Card';
        else
            $paying_method = 'Cheque';

        $lims_payment_data = new Payment();
        $lims_payment_data->user_id = Auth::id();
        $lims_payment_data->integration_id = $lims_integration_data->id;
        $lims_payment_data->account_id = $data['account_id'];
        $lims_payment_data->payment_reference = 'ppr-' . date("Ymd") . '-'. date("his");
        $lims_payment_data->amount = $data['amount'];
        $lims_payment_data->change = $data['paying_amount'] - $data['amount'];
        $lims_payment_data->paying_method = $paying_method;
        $lims_payment_data->payment_note = $data['payment_note'];
        $lims_payment_data->save();

        $lims_payment_data = Payment::latest()->first();
        $data['payment_id'] = $lims_payment_data->id;
        $lims_pos_setting_data = PosSetting::latest()->first();
        if($paying_method == 'Credit Card' && $lims_pos_setting_data->stripe_secret_key){
            Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['amount'];

            // Charge the Customer
            $charge = \Stripe\Charge::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'source' => $token,
            ]);

            $data['charge_id'] = $charge->id;
            PaymentWithCreditCard::create($data);
        }
        elseif ($paying_method == 'Cheque') {
            PaymentWithCheque::create($data);
        }
        return redirect('integrations')->with('message', 'Payment created successfully');
    }

    public function getPayment($id)
    {
        $lims_payment_list = Payment::where('integration_id', $id)->get();
        $date = [];
        $payment_reference = [];
        $paid_amount = [];
        $paying_method = [];
        $payment_id = [];
        $payment_note = [];
        $cheque_no = [];
        $change = [];
        $paying_amount = [];
        $account_name = [];
        $account_id = [];
        foreach ($lims_payment_list as $payment) {
            $date[] = date(config('date_format'), strtotime($payment->created_at->toDateString())) . ' '. $payment->created_at->toTimeString();
            $payment_reference[] = $payment->payment_reference;
            $paid_amount[] = $payment->amount;
            $change[] = $payment->change;
            $paying_method[] = $payment->paying_method;
            $paying_amount[] = $payment->amount + $payment->change;
            if($payment->paying_method == 'Cheque'){
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id',$payment->id)->first();
                $cheque_no[] = $lims_payment_cheque_data->cheque_no;
            }
            else{
                $cheque_no[] = null;
            }
            $payment_id[] = $payment->id;
            $payment_note[] = $payment->payment_note;
            $lims_account_data = Account::find($payment->account_id);
            if($lims_account_data) {
                $account_name[] = $lims_account_data->name;
                $account_id[] = $lims_account_data->id;
            }
            else {
                $account_name[] = 'N/A';
                $account_id[] = 0;
            }
        }
        $payments[] = $date;
        $payments[] = $payment_reference;
        $payments[] = $paid_amount;
        $payments[] = $paying_method;
        $payments[] = $payment_id;
        $payments[] = $payment_note;
        $payments[] = $cheque_no;
        $payments[] = $change;
        $payments[] = $paying_amount;
        $payments[] = $account_name;
        $payments[] = $account_id;

        return $payments;
    }

    public function updatePayment(Request $request)
    {
        $data = $request->all();
        $lims_payment_data = Payment::find($data['payment_id']);
        $lims_integration_data = Integration::find($lims_payment_data->integration_id);
        //updating integration table
        $amount_dif = $lims_payment_data->amount - $data['edit_amount'];
        $lims_integration_data->paid_amount = $lims_integration_data->paid_amount - $amount_dif;
        $balance = $lims_integration_data->grand_total - $lims_integration_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $lims_integration_data->payment_status = 1;
        elseif ($balance == 0)
            $lims_integration_data->payment_status = 2;
        $lims_integration_data->save();

        //updating payment data
        $lims_payment_data->account_id = $data['account_id'];
        $lims_payment_data->amount = $data['edit_amount'];
        $lims_payment_data->change = $data['edit_paying_amount'] - $data['edit_amount'];
        $lims_payment_data->payment_note = $data['edit_payment_note'];
        $lims_pos_setting_data = PosSetting::latest()->first();
        if($data['edit_paid_by_id'] == 1)
            $lims_payment_data->paying_method = 'Cash';
        elseif ($data['edit_paid_by_id'] == 2)
            $lims_payment_data->paying_method = 'Gift Card';
        elseif ($data['edit_paid_by_id'] == 3 && $lims_pos_setting_data->stripe_secret_key) {
            \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['edit_amount'];
            if($lims_payment_data->paying_method == 'Credit Card'){
                $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $lims_payment_data->id)->first();

                \Stripe\Refund::create(array(
                  "charge" => $lims_payment_with_credit_card_data->charge_id,
                ));

                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'source' => $token,
                ]);

                $lims_payment_with_credit_card_data->charge_id = $charge->id;
                $lims_payment_with_credit_card_data->save();
            }
            elseif($lims_pos_setting_data->stripe_secret_key) {
                // Charge the Customer
                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'source' => $token,
                ]);

                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            $lims_payment_data->paying_method = 'Credit Card';
        }
        else{
            if($lims_payment_data->paying_method == 'Cheque'){
                $lims_payment_data->paying_method = 'Cheque';
                $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $data['payment_id'])->first();
                $lims_payment_cheque_data->cheque_no = $data['edit_cheque_no'];
                $lims_payment_cheque_data->save();
            }
            else{
                $lims_payment_data->paying_method = 'Cheque';
                $data['cheque_no'] = $data['edit_cheque_no'];
                PaymentWithCheque::create($data);
            }
        }
        $lims_payment_data->save();
        return redirect('integrations')->with('message', 'Payment updated successfully');
    }

    public function deletePayment(Request $request)
    {
        $lims_payment_data = Payment::find($request['id']);
        $lims_integration_data = Integration::where('id', $lims_payment_data->integration_id)->first();
        $lims_integration_data->paid_amount -= $lims_payment_data->amount;
        $balance = $lims_integration_data->grand_total - $lims_integration_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $lims_integration_data->payment_status = 1;
        elseif ($balance == 0)
            $lims_integration_data->payment_status = 2;
        $lims_integration_data->save();
        $lims_pos_setting_data = PosSetting::latest()->first();

        if($lims_payment_data->paying_method == 'Credit Card' && $lims_pos_setting_data->stripe_secret_key) {
            $lims_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $request['id'])->first();
            \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
            \Stripe\Refund::create(array(
              "charge" => $lims_payment_with_credit_card_data->charge_id,
            ));

            $lims_payment_with_credit_card_data->delete();
        }
        elseif ($lims_payment_data->paying_method == 'Cheque') {
            $lims_payment_cheque_data = PaymentWithCheque::where('payment_id', $request['id'])->first();
            $lims_payment_cheque_data->delete();
        }
        $lims_payment_data->delete();
        return redirect('integrations')->with('not_permitted', 'Payment deleted successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $integration_id = $request['integrationIdArray'];
        foreach ($integration_id as $id) {
            $lims_integration_data = Integration::find($id);
            $this->fileDelete('documents/integration/', $lims_integration_data->document);

            $lims_product_integration_data = ProductIntegration::where('integration_id', $id)->get();
            $lims_payment_data = Payment::where('integration_id', $id)->get();
            foreach ($lims_product_integration_data as $product_integration_data) {
                $lims_integration_unit_data = Unit::find($product_integration_data->integration_unit_id);
                if ($lims_integration_unit_data->operator == '*')
                    $recieved_qty = $product_integration_data->recieved * $lims_integration_unit_data->operation_value;
                else
                    $recieved_qty = $product_integration_data->recieved / $lims_integration_unit_data->operation_value;

                $lims_product_data = Product::find($product_integration_data->product_id);
                if($product_integration_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($lims_product_data->id, $product_integration_data->variant_id)->first();
                    $lims_product_integration_data = Product_Integration::FindProductWithVariant($product_integration_data->product_id, $product_integration_data->variant_id, $lims_integration_data->integration_id)
                        ->first();
                    $lims_product_variant_data->qty -= $recieved_qty;
                    $lims_product_variant_data->save();
                }
                elseif($product_integration_data->product_batch_id) {
                    $lims_product_batch_data = ProductBatch::find($product_integration_data->product_batch_id);
                    $lims_product_integration_data = Product_Integration::where([
                        ['product_batch_id', $product_integration_data->product_batch_id],
                        ['integration_id', $lims_integration_data->integration_id]
                    ])->first();

                    $lims_product_batch_data->qty -= $recieved_qty;
                    $lims_product_batch_data->save();
                }
                else {
                    $lims_product_integration_data = Product_Integration::FindProductWithoutVariant($product_integration_data->product_id, $lims_integration_data->integration_id)
                        ->first();
                }

                $lims_product_data->qty -= $recieved_qty;
                $lims_product_integration_data->qty -= $recieved_qty;

                $lims_product_integration_data->save();
                $lims_product_data->save();
                $product_integration_data->delete();
            }
            $lims_pos_setting_data = PosSetting::latest()->first();
            foreach ($lims_payment_data as $payment_data) {
                if($payment_data->paying_method == "Cheque"){
                    $payment_with_cheque_data = PaymentWithCheque::where('payment_id', $payment_data->id)->first();
                    $payment_with_cheque_data->delete();
                }
                elseif($payment_data->paying_method == "Credit Card" && $lims_pos_setting_data->stripe_secret_key) {
                    $payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment_data->id)->first();
                    \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
                    \Stripe\Refund::create(array(
                      "charge" => $payment_with_credit_card_data->charge_id,
                    ));

                    $payment_with_credit_card_data->delete();
                }
                $payment_data->delete();
            }

            $lims_integration_data->delete();
        }
        return 'Integration deleted successfully!';
    }

    public function destroy($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('integrations-delete')){
            $lims_integration_data = Integration::find($id);
            $lims_product_integration_data = ProductIntegration::where('integration_id', $id)->get();
            $lims_payment_data = Payment::where('integration_id', $id)->get();
            foreach ($lims_product_integration_data as $product_integration_data) {
                $lims_integration_unit_data = Unit::find($product_integration_data->integration_unit_id);
                if ($lims_integration_unit_data->operator == '*')
                    $recieved_qty = $product_integration_data->recieved * $lims_integration_unit_data->operation_value;
                else
                    $recieved_qty = $product_integration_data->recieved / $lims_integration_unit_data->operation_value;

                $lims_product_data = Product::find($product_integration_data->product_id);
                if($product_integration_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($lims_product_data->id, $product_integration_data->variant_id)->first();
                    $lims_product_integration_data = Product_Integration::FindProductWithVariant($product_integration_data->product_id, $product_integration_data->variant_id, $lims_integration_data->integration_id)
                        ->first();
                    $lims_product_variant_data->qty -= $recieved_qty;
                    $lims_product_variant_data->save();
                }
                elseif($product_integration_data->product_batch_id) {
                    $lims_product_batch_data = ProductBatch::find($product_integration_data->product_batch_id);
                    $lims_product_integration_data = Product_Integration::where([
                        ['product_batch_id', $product_integration_data->product_batch_id],
                        ['integration_id', $lims_integration_data->integration_id]
                    ])->first();

                    $lims_product_batch_data->qty -= $recieved_qty;
                    $lims_product_batch_data->save();
                }
                else {
                    $lims_product_integration_data = Product_Integration::FindProductWithoutVariant($product_integration_data->product_id, $lims_integration_data->integration_id)
                        ->first();
                }
                //deduct imei number if available
                if($product_integration_data->imei_number) {
                    $imei_numbers = explode(",", $product_integration_data->imei_number);
                    $all_imei_numbers = explode(",", $lims_product_integration_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $lims_product_integration_data->imei_number = implode(",", $all_imei_numbers);
                }

                $lims_product_data->qty -= $recieved_qty;
                $lims_product_integration_data->qty -= $recieved_qty;

                $lims_product_integration_data->save();
                $lims_product_data->save();
                $product_integration_data->delete();
            }
            $lims_pos_setting_data = PosSetting::latest()->first();
            foreach ($lims_payment_data as $payment_data) {
                if($payment_data->paying_method == "Cheque"){
                    $payment_with_cheque_data = PaymentWithCheque::where('payment_id', $payment_data->id)->first();
                    $payment_with_cheque_data->delete();
                }
                elseif($payment_data->paying_method == "Credit Card" && $lims_pos_setting_data->stripe_secret_key) {
                    $payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment_data->id)->first();
                    \Stripe\Stripe::setApiKey($lims_pos_setting_data->stripe_secret_key);
                    \Stripe\Refund::create(array(
                      "charge" => $payment_with_credit_card_data->charge_id,
                    ));

                    $payment_with_credit_card_data->delete();
                }
                $payment_data->delete();
            }

            $lims_integration_data->delete();
            $this->fileDelete('documents/integration/', $lims_integration_data->document);

            return redirect('integrations')->with('not_permitted', 'Integration deleted successfully');;
        }

    }

    public function updateFromClient(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = date("Ymdhis");
            if(!config('database.connections.saleprosaas_landlord')) {
                $documentName = $documentName . '.' . $ext;
                $document->move('public/documents/integration', $documentName);
            }
            else {
                $documentName = $this->getTenantId() . '_' . $documentName . '.' . $ext;
                $document->move('public/documents/integration', $documentName);
            }
            $data['document'] = $documentName;
        }
        //return dd($data);
        DB::beginTransaction();
        try {
            $balance = $data['grand_total'] - $data['paid_amount'];
            if ($balance < 0 || $balance > 0) {
                $data['payment_status'] = 1;
            } else {
                $data['payment_status'] = 2;
            }
            $lims_integration_data = Integration::find($id);
            $lims_product_integration_data = ProductIntegration::where('integration_id', $id)->get();

            $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
            $product_id = $data['product_id'];
            $product_code = $data['product_code'];
            $qty = $data['qty'];
            $recieved = $data['recieved'];
            $batch_no = $data['batch_no'];
            $expired_date = $data['expired_date'];
            $integration_unit = $data['integration_unit'];
            $net_unit_cost = $data['net_unit_cost'];
            $discount = $data['discount'];
            $tax_rate = $data['tax_rate'];
            $tax = $data['tax'];
            $total = $data['subtotal'];
            $imei_number = $new_imei_number = $data['imei_number'];
            $product_integration = [];
            $lims_product_integration_data = null;

            foreach ($lims_product_integration_data as $product_integration_data) {

                $old_recieved_value = $product_integration_data->recieved;
                $lims_integration_unit_data = Unit::find($product_integration_data->integration_unit_id);

                if ($lims_integration_unit_data->operator == '*') {
                    $old_recieved_value = $old_recieved_value * $lims_integration_unit_data->operation_value;
                } else {
                    $old_recieved_value = $old_recieved_value / $lims_integration_unit_data->operation_value;
                }
                $lims_product_data = Product::find($product_integration_data->product_id);
                if($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProduct($lims_product_data->id, $product_integration_data->variant_id)->first();
                    if($lims_product_variant_data) {
                        $lims_product_integration_data = Product_Integration::where([
                            ['product_id', $lims_product_data->id],
                            ['variant_id', $product_integration_data->variant_id],
                            ['integration_id', $lims_integration_data->integration_id]
                        ])->first();
                        $lims_product_variant_data->qty -= $old_recieved_value;
                        $lims_product_variant_data->save();
                    }
                }
                elseif($product_integration_data->product_batch_id) {
                    $product_batch_data = ProductBatch::find($product_integration_data->product_batch_id);
                    $product_batch_data->qty -= $old_recieved_value;
                    $product_batch_data->save();

                    $lims_product_integration_data = Product_Integration::where([
                        ['product_id', $product_integration_data->product_id],
                        ['product_batch_id', $product_integration_data->product_batch_id],
                        ['integration_id', $lims_integration_data->integration_id],
                    ])->first();
                }
                else {
                    $lims_product_integration_data = Product_Integration::where([
                        ['product_id', $product_integration_data->product_id],
                        ['integration_id', $lims_integration_data->integration_id],
                    ])->first();
                }
                if($product_integration_data->imei_number) {
                    $position = array_search($lims_product_data->id, $product_id);
                    if($imei_number[$position]) {
                        $prev_imei_numbers = explode(",", $product_integration_data->imei_number);
                        $new_imei_numbers = explode(",", $imei_number[$position]);
                        foreach ($prev_imei_numbers as $prev_imei_number) {
                            if(($pos = array_search($prev_imei_number, $new_imei_numbers)) !== false) {
                                unset($new_imei_numbers[$pos]);
                            }
                        }
                        $new_imei_number[$position] = implode(",", $new_imei_numbers);
                    }
                }
                $lims_product_data->qty -= $old_recieved_value;
                if($lims_product_integration_data) {
                    $lims_product_integration_data->qty -= $old_recieved_value;
                    $lims_product_integration_data->save();
                }
                $lims_product_data->save();
                $product_integration_data->delete();
            }

            foreach ($product_id as $key => $pro_id) {
                $price = null;
                $lims_integration_unit_data = Unit::where('unit_name', $integration_unit[$key])->first();
                if ($lims_integration_unit_data->operator == '*') {
                    $new_recieved_value = $recieved[$key] * $lims_integration_unit_data->operation_value;
                } else {
                    $new_recieved_value = $recieved[$key] / $lims_integration_unit_data->operation_value;
                }

                $lims_product_data = Product::find($pro_id);
                //dealing with product barch
                if($batch_no[$key]) {
                    $product_batch_data = ProductBatch::where([
                                            ['product_id', $lims_product_data->id],
                                            ['batch_no', $batch_no[$key]]
                                        ])->first();
                    if($product_batch_data) {
                        $product_batch_data->qty += $new_recieved_value;
                        $product_batch_data->expired_date = $expired_date[$key];
                        $product_batch_data->save();
                    }
                    else {
                        $product_batch_data = ProductBatch::create([
                                                'product_id' => $lims_product_data->id,
                                                'batch_no' => $batch_no[$key],
                                                'expired_date' => $expired_date[$key],
                                                'qty' => $new_recieved_value
                                            ]);
                    }
                    $product_integration['product_batch_id'] = $product_batch_data->id;
                }
                else
                    $product_integration['product_batch_id'] = null;

                if($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                    if($lims_product_variant_data) {
                        $lims_product_integration_data = Product_Integration::where([
                            ['product_id', $pro_id],
                            ['variant_id', $lims_product_variant_data->variant_id],
                            ['integration_id', $data['integration_id']]
                        ])->first();
                        $product_integration['variant_id'] = $lims_product_variant_data->variant_id;
                        //add quantity to product variant table
                        $lims_product_variant_data->qty += $new_recieved_value;
                        $lims_product_variant_data->save();
                    }
                }
                else {
                    $product_integration['variant_id'] = null;
                    if($product_integration['product_batch_id']) {
                        //checking for price
                        $lims_product_integration_data = Product_Integration::where([
                                                        ['product_id', $pro_id],
                                                        ['integration_id', $data['integration_id'] ],
                                                    ])
                                                    ->whereNotNull('price')
                                                    ->select('price')
                                                    ->first();
                        if($lims_product_integration_data)
                            $price = $lims_product_integration_data->price;

                        $lims_product_integration_data = Product_Integration::where([
                            ['product_id', $pro_id],
                            ['product_batch_id', $product_integration['product_batch_id'] ],
                            ['integration_id', $data['integration_id'] ],
                        ])->first();
                    }
                    else {
                        $lims_product_integration_data = Product_Integration::where([
                            ['product_id', $pro_id],
                            ['integration_id', $data['integration_id'] ],
                        ])->first();
                    }
                }

                $lims_product_data->qty += $new_recieved_value;
                if($lims_product_integration_data){
                    $lims_product_integration_data->qty += $new_recieved_value;
                    $lims_product_integration_data->save();
                }
                else {
                    $lims_product_integration_data = new Product_Integration();
                    $lims_product_integration_data->product_id = $pro_id;
                    $lims_product_integration_data->product_batch_id = $product_integration['product_batch_id'];
                    if($lims_product_data->is_variant && $lims_product_variant_data)
                        $lims_product_integration_data->variant_id = $lims_product_variant_data->variant_id;
                    $lims_product_integration_data->integration_id = $data['integration_id'];
                    $lims_product_integration_data->qty = $new_recieved_value;
                    if($price)
                        $lims_product_integration_data->price = $price;
                }
                //dealing with imei numbers
                if($imei_number[$key]) {
                    if($lims_product_integration_data->imei_number) {
                        $lims_product_integration_data->imei_number .= ',' . $new_imei_number[$key];
                    }
                    else {
                        $lims_product_integration_data->imei_number = $new_imei_number[$key];
                    }
                }

                $lims_product_data->save();
                $lims_product_integration_data->save();

                $product_integration['integration_id'] = $id ;
                $product_integration['product_id'] = $pro_id;
                $product_integration['qty'] = $qty[$key];
                $product_integration['recieved'] = $recieved[$key];
                $product_integration['integration_unit_id'] = $lims_integration_unit_data->id;
                $product_integration['net_unit_cost'] = $net_unit_cost[$key];
                $product_integration['discount'] = $discount[$key];
                $product_integration['tax_rate'] = $tax_rate[$key];
                $product_integration['tax'] = $tax[$key];
                $product_integration['total'] = $total[$key];
                $product_integration['imei_number'] = $imei_number[$key];
                ProductIntegration::create($product_integration);
            }
            DB::commit();
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        $lims_integration_data->update($data);
        return redirect('integrations')->with('message', 'Integration updated successfully');
    }
}
