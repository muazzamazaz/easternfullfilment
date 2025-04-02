<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Jasara\AmznSPA\AmznSPA;
use Jasara\AmznSPA\AmznSPAConfig;

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AddonInstallController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BillerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientAutoUpdateController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeveloperSectionController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DiscountPlanController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LanguageSettingController;
use App\Http\Controllers\MoneyTransferController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReturnPurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;
use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Enums\Endpoint;
use Illuminate\Support\Facades\Http;

Route::get('migrate', function() {
	Artisan::call('migrate');
	dd('migrated');
});

Route::get('oauth/amazon/redirect', function (Request $request) {
dd($request->all());
});

Route::get('/amazon2/authorize', function (Request $request) {

$config = [
   'endpoint' => 'https://sellingpartnerapi-na.amazon.com',
   'region'   => 'us-west-2',
   'version'  => '2021-06-30',
   'accessKeyId' => 'amzn1.application-oa2-client.c7c5b66675e641af942d47bd63e6749d',
   'secretAccessKey' => 'amzn1.oa2-cs.v1.33d906b37ea6dbc7b682cdb68a25450ab133ed7190cf60abd5c5e7ed3521b25f',
];



$config1 = new AmznSPAConfig(
  
    lwa_client_id:'amzn1.application-oa2-client.c7c5b66675e641af942d47bd63e6749d', // Client ID from Amazon
    lwa_client_secret: 'amzn1.oa2-cs.v1.33d906b37ea6dbc7b682cdb68a25450ab133ed7190cf60abd5c5e7ed3521b25f', // Client Secret from Amazon
   
    marketplace_id: 'ATVPDKIKX0DER',
    application_id: 'amzn1.sp.solution.0087b739-d0b7-42cb-9503-96c00d357ed1',
);
$appid= 'amzn1.sp.solution.0087b739-d0b7-42cb-9503-96c00d357ed1';
$redirect_uri = 'https://dev.easternfulfillment.com/oauth/amazon/redirect';
$state = 'ca';// Pass this state into the redirect handler to verify the redirect request.

$spa = new AmznSPA($config1);

$oauth_url = $spa->lwa->getAuthUrl($redirect_uri, $state);
$oauth_url="https://sellercentral.amazon.com/apps/authorize/consent?application_id=$appid&state=$state&version=beta";
return redirect($oauth_url);

});

Route::get('/woo/authorize', function (Request $request) {

//$data = $request->all();

$local_store = "https://dev.easternfulfillment.com/";//$data['local_store'];
$remote_store = "https://easywaretech.com";//$data['remote_store'];
/*
$store = \App\Store::where(['local_store'=>$local_store])->first();
if(!$store){
$store = new \App\Store();
}

$store->data = json_encode($data);
$store->local_store = $local_store;
$store->remote_store = $remote_store;
$store->status = '100';
//$store->synced_at = now();
$store->save();

\Illuminate\Support\Facades\Log::debug('Store::redirect',[$store->toArray()]);
*/
$endpoint = '/wc-auth/v1/authorize';
$params = [
'app_name' => 'pasion',
'scope' => 'read',
'user_id' => $local_store,
'return_url' => 'https://dev.easternfulfillment.com/woo/connect/response/'.$local_store,
'callback_url' => 'https://dev.easternfulfillment.com/woo/connect/callback/'.$local_store
];
$api = $remote_store.$endpoint.'?'.http_build_query( $params );

\Illuminate\Support\Facades\Log::debug('Store::redirect',[$api]);

return Redirect::away($api);

});



Route::any('/woo/connect/response/{local_store}', function ($local_store, Request $request) {

$data = $request->all();
/*
\Illuminate\Support\Facades\Log::debug('/woo/connect/response ',$data);
$store = \App\Store::where(['local_store'=>$local_store])->first();

return view(resolveTenant().'/woo_connect_response'
,['store'=>$store,'data'=>$data]);
*/
});


Route::any('/woo/connect/callback/{local_store}', function ($local_store, Request $request) {

$data = $request->all();
/*
\Illuminate\Support\Facades\Log::debug('/woo/connect/callback ',[$data]);
$store = \App\Store::where(['local_store'=>$data['user_id']])->first();
if($store){
$store->data = json_encode($data);
$store->local_store = $data['user_id'];
$store->status = '200';
$store->synced_at = now();
$store->save();

\Illuminate\Support\Facades\Log::debug('Store::connect.callback ',

[$store->toArray()]);

}
*/
return ['s'=>200];
});


Route::get('/amazon1/authorize', function (Request $request) {
    $provider = new GenericProvider([
        'clientId'                => 'amzn1.application-oa2-client.c7c5b66675e641af942d47bd63e6749d', // Client ID from Amazon
        'clientSecret'            => 'amzn1.oa2-cs.v1.33d906b37ea6dbc7b682cdb68a25450ab133ed7190cf60abd5c5e7ed3521b25f', // Client Secret from Amazon
        'redirectUri'             => 'http://localhost:8003/woo/connect/callback/',
        'urlAuthorize'            => 'https://www.amazon.com/ap/oa',
        'urlAccessToken'          => 'https://api.amazon.com/auth/o2/token',
        'urlResourceOwnerDetails' => 'https://www.amazon.com/resource'
    ]);
    
    try {
        // Use your refresh token to get a new access token
        $accessToken = $provider->getAccessToken('refresh_token', [
            'refresh_token' =>   'Atzr|IwEBIGVrZg5q_S4NFrQilc8_Ur1duUpZeT_8R2FjN7_63_4SmZLPh7VW4f3X4-VleCTislsRnEUCEihTiEpsZwRAEUC96rde-zJMDO0z_uAu0s6-JRQJ7Ri4Hvovbhm3pS9RKuEJjwGMz5z6p5LpVZTWcdoVEr4_lxO6iCqayQN3SR4XLB78UsxkGM17LVPvmSCE3dKwY6lUxu_P5tQD7aP2DcyDdrOHL6ZPLAqnn5W4rtpSj0BXY899batyxewe4uhWTr54uCbhsZCAPGgMF78F3cdfJF9j53AUs_CdVXVb2LlbD0tlCbzU07ISFKeNEgvJlZ8uSF373PGl-OyT8z-qACmE',

        ]);
    
        $tokenValue = $accessToken->getToken(); // This is your access token
        
        $marketplaceId = 'ATVPDKIKX0DERy'; // The Marketplace ID you're operating in

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $tokenValue,
          //  'x-amz-access-token' => $accessToken,
            'x-amz-date' => now()->format(\DateTime::ATOM), // ISO 8601 formatted date
        ])
        ->get('https://sellingpartnerapi-na.amazon.com/catalog/v0/items', [
            'MarketplaceId' => $marketplaceId
          //  'SellerSKU' => 'example-sku', // Example SKU for catalog retrieval
        ]);
dd($response);
        if ($response->successful()) {
            $catalogData = $response->json(); // Process the JSON data
        } else {
            // Handle error cases
            $error = $response->body();
            dd("Request failed with status " . $response->status() . ": " . $error);
        }

    } catch (\Exception $e) {
        // Handle exceptions
        dd($e->getMessage());
    }
});

Route::get('/amazon/authorize', function (Request $request) {
   
    $sellerConnector = SellingPartnerApi::make(
        clientId: 'amzn1.application-oa2-client.c7c5b66675e641af942d47bd63e6749d',
        clientSecret: 'amzn1.oa2-cs.v1.33d906b37ea6dbc7b682cdb68a25450ab133ed7190cf60abd5c5e7ed3521b25f',
        refreshToken: 'Atzr|IwEBIGVrZg5q_S4NFrQilc8_Ur1duUpZeT_8R2FjN7_63_4SmZLPh7VW4f3X4-VleCTislsRnEUCEihTiEpsZwRAEUC96rde-zJMDO0z_uAu0s6-JRQJ7Ri4Hvovbhm3pS9RKuEJjwGMz5z6p5LpVZTWcdoVEr4_lxO6iCqayQN3SR4XLB78UsxkGM17LVPvmSCE3dKwY6lUxu_P5tQD7aP2DcyDdrOHL6ZPLAqnn5W4rtpSj0BXY899batyxewe4uhWTr54uCbhsZCAPGgMF78F3cdfJF9j53AUs_CdVXVb2LlbD0tlCbzU07ISFKeNEgvJlZ8uSF373PGl-OyT8z-qACmE',
        endpoint: Endpoint::NA,  // Or Endpoint::EU, Endpoint::FE, Endpoint::NA_SANDBOX, etc.
    )->seller();

    $response = $sellerConnector->catalogItemsV0();
 
    dd($response);
  
    });

Route::get('clear',function() {
    Artisan::call('optimize:clear');
    cache()->forget('biller_list');
    cache()->forget('brand_list');
    cache()->forget('category_list');
    cache()->forget('coupon_list');
    cache()->forget('customer_list');
    cache()->forget('customer_group_list');
    cache()->forget('product_list');
    cache()->forget('product_list_with_variant');
    cache()->forget('warehouse_list');
    cache()->forget('table_list');
    cache()->forget('tax_list');
    cache()->forget('currency');
    cache()->forget('general_setting');
    cache()->forget('pos_setting');
    cache()->forget('user_role');
    cache()->forget('permissions');
    cache()->forget('role_has_permissions');
    cache()->forget('role_has_permissions_list');
    dd('cleared');
});

Route::get('update-coupon', [CouponController::class, 'updateCoupon']);

Route::get('auto-update-dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Auto Update
Route::group(['prefix' => 'developer-section'], function () {
    Route::controller(DeveloperSectionController::class)->group(function () {
        Route::get('/', 'index')->name('developer-section.index');
        Route::post('/', 'submit')->name('developer-section.submit');
        Route::post('/bug-update-setting', 'bugUpdateSetting')->name('bug-update-setting.submit');
        Route::post('/version-upgrade-setting', 'versionUpgradeSetting')->name('version-upgrade-setting.submit');
     });
});

Route::controller(ClientAutoUpdateController::class)->group(function () {
    Route::get('/new-release', 'newVersionReleasePage')->name('new-release');
    Route::get('/bugs', 'bugUpdatePage')->name('bug-update-page');
    // Action on Client server
    Route::post('version-upgrade', 'versionUpgrade')->name('version-upgrade');
    Route::post('bug-update', 'bugUpdate')->name('bug-update');
});


Auth::routes();
Route::get('/documentation', [HomeController::class, 'documentation']);
Route::get('/ecommerce-documentation', [HomeController::class, 'ecomDocumentation']);

Route::post('/session-renew', [HomeController::class, 'sessionRenew'])->name('session');

Route::group(['middleware' => 'auth'], function() {
    Route::controller(HomeController::class)->group(function () {
        Route::get('home', 'home');
    });
});

Route::group(['middleware' => ['common', 'auth', 'active']], function() {

    // Languages Section
    Route::prefix('languages')->group(function () {
        Route::get('/', [LanguageSettingController::class, 'languages'])->name('languages.index');
        Route::get('/{language}/translations', [LanguageSettingController::class, 'index'])->name('languages.translations.index');
        Route::post('/update', [LanguageSettingController::class, 'update'])->name('language.translations.update');

        Route::get('/create', [LanguageSettingController::class, 'create'])->name('languages.create');
        Route::post('/store', [LanguageSettingController::class, 'store'])->name('languages.store');
        Route::get('/switch/{lang}', [LanguageSettingController::class, 'languageSwitch'])->name('language.switch');
        Route::get('/delete', [LanguageSettingController::class, 'languageDelete'])->name('language.delete');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/dashboard', 'dashboard');
        Route::get('/yearly-best-selling-price', 'yearlyBestSellingPrice');
        Route::get('/yearly-best-selling-qty', 'yearlyBestSellingQty');
        Route::get('/monthly-best-selling-qty', 'monthlyBestSellingQty');
        Route::get('/recent-sale', 'recentSale');
        Route::get('/recent-purchase', 'recentPurchase');
        Route::get('/recent-quotation', 'recentQuotation');
        Route::get('/recent-payment', 'recentPayment');
        Route::get('switch-theme/{theme}', 'switchTheme')->name('switchTheme');
        Route::get('/dashboard-filter/{start_date}/{end_date}', 'dashboardFilter');
        Route::get('addon-list', 'addonList');
        Route::get('my-transactions/{year}/{month}', 'myTransaction');
    });


    // Need to check again
    Route::resource('products',ProductController::class)->except([ 'show']);
    Route::controller(ProductController::class)->group(function () {
        Route::post('products/product-data', 'productData');
        Route::get('products/gencode', 'generateCode');
        Route::get('products/search', 'search');
        Route::get('products/saleunit/{id}', 'saleUnit');
        Route::get('products/getdata/{id}/{variant_id}', 'getData');
        Route::get('products/product_warehouse/{id}', 'productWarehouseData');
        Route::get('products/print_barcode','printBarcode')->name('product.printBarcode');
        Route::get('products/lims_product_search', 'limsProductSearch')->name('product.search');
        Route::post('products/deletebyselection', 'deleteBySelection');
        Route::post('products/update', 'updateProduct');
        Route::get('products/variant-data/{id}','variantData');
        Route::get('products/history', 'history')->name('products.history');
        Route::post('products/sale-history-data', 'saleHistoryData');
        Route::post('products/purchase-history-data', 'purchaseHistoryData');
        Route::post('products/sale-return-history-data', 'saleReturnHistoryData');
        Route::post('products/purchase-return-history-data', 'purchaseReturnHistoryData');

        Route::post('importproduct', 'importProduct')->name('product.import');
        Route::post('exportproduct', 'exportProduct')->name('product.export');
        Route::get('products/all-product-in-stock', 'allProductInStock')->name('product.allProductInStock');
        Route::get('products/show-all-product-online', 'showAllProductOnline')->name('product.showAllProductOnline');
        Route::get('check-batch-availability/{product_id}/{batch_no}/{warehouse_id}', 'checkBatchAvailability');
     });


    Route::get('language_switch/{locale}', [LanguageController::class, 'switchLanguage']);

    Route::resource('role',RoleController::class);
    Route::controller(RoleController::class)->group(function () {
        Route::get('role/permission/{id}', 'permission')->name('role.permission');
        Route::post('role/set_permission', 'setPermission')->name('role.setPermission');
    });


    Route::resource('unit', UnitController::class);
    Route::controller(UnitController::class)->group(function () {
        Route::post('importunit', 'importUnit')->name('unit.import');
        Route::post('unit/deletebyselection', 'deleteBySelection');
        Route::get('unit/lims_unit_search', 'limsUnitSearch')->name('unit.search');
     });


    Route::controller(CategoryController::class)->group(function () {
        Route::post('category/import', 'import')->name('category.import');
        Route::post('category/deletebyselection', 'deleteBySelection');
        Route::post('category/category-data', 'categoryData');
    });
	Route::resource('category', CategoryController::class);


    Route::controller(BrandController::class)->group(function () {
        Route::post('importbrand', 'importBrand')->name('brand.import');
        Route::post('brand/deletebyselection', 'deleteBySelection');
        Route::get('brand/lims_brand_search', 'limsBrandSearch')->name('brand.search');
    });
    Route::resource('brand', BrandController::class);


    Route::controller(SupplierController::class)->group(function () {
        Route::post('importsupplier', 'importSupplier')->name('supplier.import');
        Route::post('supplier/deletebyselection', 'deleteBySelection');
        Route::post('suppliers/clear-due', 'clearDue')->name('supplier.clearDue');
        Route::get('suppliers/all', 'suppliersAll')->name('supplier.all');
    });
    Route::resource('supplier', SupplierController::class)->except('show');


    Route::controller(WarehouseController::class)->group(function () {
        Route::post('importwarehouse', 'importWarehouse')->name('warehouse.import');
        Route::post('warehouse/deletebyselection', 'deleteBySelection');
        Route::get('warehouse/lims_warehouse_search', 'limsWarehouseSearch')->name('warehouse.search');
        Route::get('warehouse/all', 'warehouseAll')->name('warehouse.all');
    });
    Route::resource('warehouse', WarehouseController::class);


    Route::resource('tables', TableController::class);


    Route::controller(TaxController::class)->group(function () {
        Route::post('importtax', 'importTax')->name('tax.import');
        Route::post('tax/deletebyselection', 'deleteBySelection');
        Route::get('tax/lims_tax_search', 'limsTaxSearch')->name('tax.search');
    });
    Route::resource('tax', TaxController::class);


    Route::controller(CustomerGroupController::class)->group(function () {
        Route::post('importcustomer_group', 'importCustomerGroup')->name('customer_group.import');
        Route::post('customer_group/deletebyselection', 'deleteBySelection');
        Route::get('customer_group/lims_customer_group_search', 'limsCustomerGroupSearch')->name('customer_group.search');
        Route::get('customer_group/all', 'customerGroupAll')->name('customer_group.all');
    });
    Route::resource('customer_group', CustomerGroupController::class);


    Route::resource('discount-plans', DiscountPlanController::class);
    Route::resource('discounts', DiscountController::class);
    Route::get('discounts/product-search/{code}', [DiscountController::class,'productSearch']);


    Route::controller(CustomerController::class)->group(function () {
        Route::post('importcustomer', 'importCustomer')->name('customer.import');
        Route::get('customer/getDeposit/{id}', 'getDeposit');
        Route::post('customer/add_deposit', 'addDeposit')->name('customer.addDeposit');
        Route::post('customer/update_deposit', 'updateDeposit')->name('customer.updateDeposit');
        Route::post('customer/deleteDeposit', 'deleteDeposit')->name('customer.deleteDeposit');
        Route::post('customer/deletebyselection', 'deleteBySelection');
        Route::get('customer/lims_customer_search', 'limsCustomerSearch')->name('customer.search');
        Route::post('customers/clear-due', 'clearDue')->name('customer.clearDue');
        Route::get('customers/all', 'customersAll')->name('customer.all');
    });
    Route::resource('customer', CustomerController::class)->except('show');


    Route::controller(BillerController::class)->group(function () {
        Route::post('importbiller', 'importBiller')->name('biller.import');
        Route::post('biller/deletebyselection', 'deleteBySelection');
        Route::get('biller/lims_biller_search', 'limsBillerSearch')->name('biller.search');
    });
    Route::resource('biller', BillerController::class);


    Route::controller(SaleController::class)->group(function () {
        Route::post('sales/sale-data', 'saleData');
        Route::post('sales/sendmail', 'sendMail')->name('sale.sendmail');
        Route::get('sales/sale_by_csv', 'saleByCsv');
        Route::get('sales/product_sale/{id}', 'productSaleData');
        Route::post('importsale', 'importSale')->name('sale.import');
        Route::get('pos', 'posSale')->name('sale.pos');
        Route::get('sales/lims_sale_search', 'limsSaleSearch')->name('sale.search');
        Route::get('sales/lims_product_search', 'limsProductSearch')->name('product_sale.search');
        Route::get('sales/getcustomergroup/{id}', 'getCustomerGroup')->name('sale.getcustomergroup');
        Route::get('sales/getproduct/{id}', 'getProduct')->name('sale.getproduct');
        Route::get('sales/getproduct/{category_id}/{brand_id}', 'getProductByFilter');
        Route::get('sales/getfeatured', 'getFeatured');
        Route::get('sales/get_gift_card', 'getGiftCard');
        Route::get('sales/paypalSuccess', 'paypalSuccess');
        Route::get('sales/paypalPaymentSuccess/{id}', 'paypalPaymentSuccess');
        Route::get('sales/gen_invoice/{id}', 'genInvoice')->name('sale.invoice');
        Route::post('sales/add_payment', 'addPayment')->name('sale.add-payment');
        Route::get('sales/getpayment/{id}', 'getPayment')->name('sale.get-payment');
        Route::post('sales/updatepayment', 'updatePayment')->name('sale.update-payment');
        Route::post('sales/deletepayment', 'deletePayment')->name('sale.delete-payment');
        Route::get('sales/{id}/create', 'createSale')->name('sale.draft');
        Route::post('sales/deletebyselection', 'deleteBySelection');
        Route::get('sales/print-last-reciept', 'printLastReciept')->name('sales.printLastReciept');
        Route::get('sales/today-sale', 'todaySale');
        Route::get('sales/today-profit/{warehouse_id}', 'todayProfit');
        Route::get('sales/check-discount', 'checkDiscount');
    });
    Route::resource('sales', SaleController::class);


    Route::controller(DeliveryController::class)->group(function () {
        Route::prefix('delivery')->group(function () {
            Route::get('/', 'index')->name('delivery.index');
            Route::get('product_delivery/{id}','productDeliveryData');
            Route::get('create/{id}', 'create');
            Route::post('store', 'store')->name('delivery.store');
            Route::post('sendmail', 'sendMail')->name('delivery.sendMail');
            Route::get('{id}/edit', 'edit');
            Route::post('update', 'update')->name('delivery.update');
            Route::post('deletebyselection', 'deleteBySelection');
            Route::post('delete/{id}', 'delete')->name('delivery.delete');
        });
     });


    Route::controller(QuotationController::class)->group(function () {
        Route::prefix('quotations')->group(function () {
            Route::post('quotation-data', 'quotationData')->name('quotations.data');
            Route::get('product_quotation/{id}','productQuotationData');
            Route::get('lims_product_search', 'limsProductSearch')->name('product_quotation.search');
            Route::get('getcustomergroup/{id}', 'getCustomerGroup')->name('quotation.getcustomergroup');
            Route::get('getproduct/{id}', 'getProduct')->name('quotation.getproduct');
            Route::get('{id}/create_sale', 'createSale')->name('quotation.create_sale');
            Route::get('{id}/create_purchase', 'createPurchase')->name('quotation.create_purchase');
            Route::post('sendmail', 'sendMail')->name('quotation.sendmail');
            Route::post('deletebyselection', 'deleteBySelection');
        });
     });
    Route::resource('quotations', QuotationController::class);


    Route::controller(PurchaseController::class)->group(function () {
        Route::prefix('purchases')->group(function () {
            Route::post('purchase-data', 'purchaseData')->name('purchases.data');
            Route::get('product_purchase/{id}', 'productPurchaseData');
            Route::get('lims_product_search', 'limsProductSearch')->name('product_purchase.search');
            Route::post('add_payment', 'addPayment')->name('purchase.add-payment');
            Route::get('getpayment/{id}', 'getPayment')->name('purchase.get-payment');
            Route::post('updatepayment', 'updatePayment')->name('purchase.update-payment');
            Route::post('deletepayment', 'deletePayment')->name('purchase.delete-payment');
            Route::get('purchase_by_csv', 'purchaseByCsv');
            Route::get('duplicate/{id}', 'duplicate')->name('purchase.duplicate');
            Route::post('deletebyselection', 'deleteBySelection');
        });
        Route::post('importpurchase', 'importPurchase')->name('purchase.import');
    });
    Route::resource('purchases', PurchaseController::class);



    Route::controller(TransferController::class)->group(function () {
        Route::prefix('transfers')->group(function () {
            Route::post('transfer-data', 'transferData')->name('transfers.data');
            Route::get('product_transfer/{id}', 'productTransferData');
            Route::get('transfer_by_csv', 'transferByCsv');
            Route::get('getproduct/{id}', 'getProduct')->name('transfer.getproduct');
            Route::get('lims_product_search', 'limsProductSearch')->name('product_transfer.search');
            Route::post('deletebyselection', 'deleteBySelection');
         });
        Route::post('importtransfer', 'importTransfer')->name('transfer.import');
    });
    Route::resource('transfers', TransferController::class);



    Route::controller(AdjustmentController::class)->group(function () {
        Route::get('qty_adjustment/getproduct/{id}', 'getProduct')->name('adjustment.getproduct');
        Route::get('qty_adjustment/lims_product_search', 'limsProductSearch')->name('product_adjustment.search');
        Route::post('qty_adjustment/deletebyselection', 'deleteBySelection');
    });
    Route::resource('qty_adjustment', AdjustmentController::class);


    Route::controller(ReturnController::class)->group(function () {
        Route::prefix('return-sale')->group(function () {
            Route::post('return-data', 'returnData');
            Route::get('getcustomergroup/{id}', 'getCustomerGroup')->name('return-sale.getcustomergroup');
            Route::post('sendmail', 'sendMail')->name('return-sale.sendmail');
            Route::get('getproduct/{id}', 'getProduct')->name('return-sale.getproduct');
            Route::get('lims_product_search', 'limsProductSearch')->name('product_return-sale.search');
            Route::get('product_return/{id}', 'productReturnData');
            Route::post('deletebyselection', 'deleteBySelection');
         });
    });
    Route::resource('return-sale', ReturnController::class);


    Route::controller(ReturnPurchaseController::class)->group(function () {
        Route::prefix('return-purchase')->group(function () {
            Route::post('return-data', 'returnData');
            Route::get('getcustomergroup/{id}', 'getCustomerGroup')->name('return-purchase.getcustomergroup');
            Route::post('sendmail', 'sendMail')->name('return-purchase.sendmail');
            Route::get('getproduct/{id}', 'getProduct')->name('return-purchase.getproduct');
            Route::get('lims_product_search', 'limsProductSearch')->name('product_return-purchase.search');
            Route::get('product_return/{id}', 'productReturnData');
            Route::post('deletebyselection', 'deleteBySelection');
         });
    });
    Route::resource('return-purchase', ReturnPurchaseController::class);


    Route::controller(ReportController::class)->group(function () {
        Route::prefix('report')->group(function () {
            Route::get('product_quantity_alert', 'productQuantityAlert')->name('report.qtyAlert');
            Route::get('daily-sale-objective', 'dailySaleObjective')->name('report.dailySaleObjective');
            Route::post('daily-sale-objective-data', 'dailySaleObjectiveData');
            Route::get('product-expiry', 'productExpiry')->name('report.productExpiry');
            Route::get('warehouse_stock', 'warehouseStock')->name('report.warehouseStock');
            Route::get('daily_sale/{year}/{month}', 'dailySale');
            Route::post('daily_sale/{year}/{month}', 'dailySaleByWarehouse')->name('report.dailySaleByWarehouse');
            Route::get('monthly_sale/{year}', 'monthlySale');
            Route::post('monthly_sale/{year}', 'monthlySaleByWarehouse')->name('report.monthlySaleByWarehouse');
            Route::get('daily_purchase/{year}/{month}', 'dailyPurchase');
            Route::post('daily_purchase/{year}/{month}', 'dailyPurchaseByWarehouse')->name('report.dailyPurchaseByWarehouse');
            Route::get('monthly_purchase/{year}', 'monthlyPurchase');
            Route::post('monthly_purchase/{year}', 'monthlyPurchaseByWarehouse')->name('report.monthlyPurchaseByWarehouse');
            Route::get('best_seller', 'bestSeller');
            Route::post('best_seller', 'bestSellerByWarehouse')->name('report.bestSellerByWarehouse');
            Route::post('profit_loss', 'profitLoss')->name('report.profitLoss');
            Route::get('product_report', 'productReport')->name('report.product');
            Route::post('product_report_data', 'productReportData');
            Route::post('purchase', 'purchaseReport')->name('report.purchase');
            Route::post('sale_report', 'saleReport')->name('report.sale');
            Route::post('sale-report-chart', 'saleReportChart')->name('report.saleChart');
            Route::post('payment_report_by_date', 'paymentReportByDate')->name('report.paymentByDate');
            Route::post('warehouse_report', 'warehouseReport')->name('report.warehouse');
            Route::post('warehouse-sale-data', 'warehouseSaleData');
            Route::post('warehouse-purchase-data', 'warehousePurchaseData');
            Route::post('warehouse-expense-data', 'warehouseExpenseData');
            Route::post('warehouse-quotation-data', 'warehouseQuotationData');
            Route::post('warehouse-return-data', 'warehouseReturnData');
            Route::post('user_report', 'userReport')->name('report.user');
            Route::post('user-sale-data', 'userSaleData');
            Route::post('user-purchase-data', 'userPurchaseData');
            Route::post('user-expense-data', 'userExpenseData');
            Route::post('user-quotation-data', 'userQuotationData');
            Route::post('user-payment-data', 'userPaymentData');
            Route::post('user-transfer-data', 'userTransferData');
            Route::post('user-payroll-data', 'userPayrollData');
            Route::post('customer_report', 'customerReport')->name('report.customer');
            Route::post('customer-sale-data', 'customerSaleData');
            Route::post('customer-payment-data', 'customerPaymentData');
            Route::post('customer-quotation-data', 'customerQuotationData');
            Route::post('customer-return-data', 'customerReturnData');
            Route::post('customer-group', 'customerGroupReport')->name('report.customer_group');
            Route::post('customer-group-sale-data', 'customerGroupSaleData');
            Route::post('customer-group-payment-data', 'customerGroupPaymentData');
            Route::post('customer-group-quotation-data', 'customerGroupQuotationData');
            Route::post('customer-group-return-data', 'customerGroupReturnData');
            Route::post('supplier', 'supplierReport')->name('report.supplier');
            Route::post('supplier-purchase-data', 'supplierPurchaseData');
            Route::post('supplier-payment-data', 'supplierPaymentData');
            Route::post('supplier-return-data', 'supplierReturnData');
            Route::post('supplier-quotation-data', 'supplierQuotationData');
            Route::post('customer-due-report', 'customerDueReportByDate')->name('report.customerDueByDate');
            Route::post('customer-due-report-data', 'customerDueReportData');
            Route::post('supplier-due-report', 'supplierDueReportByDate')->name('report.supplierDueByDate');
            Route::post('supplier-due-report-data', 'supplierDueReportData');
        });
    });


    Route::controller(UserController::class)->group(function () {
        Route::get('user/profile/{id}', 'profile')->name('user.profile');
        Route::put('user/update_profile/{id}', 'profileUpdate')->name('user.profileUpdate');
        Route::put('user/changepass/{id}', 'changePassword')->name('user.password');
        Route::get('user/genpass', 'generatePassword');
        Route::post('user/deletebyselection', 'deleteBySelection');
        Route::get('user/notification', 'notificationUsers')->name('user.notification');
        Route::get('user/all', 'allUsers')->name('user.all');
    });
    Route::resource('user', UserController::class);


    Route::controller(SettingController::class)->group(function () {
        Route::prefix('setting')->group(function () {
            Route::get('general_setting', 'generalSetting')->name('setting.general');
            Route::post('general_setting_store', 'generalSettingStore')->name('setting.generalStore');

            Route::get('reward-point-setting', 'rewardPointSetting')->name('setting.rewardPoint');
            Route::post('reward-point-setting_store', 'rewardPointSettingStore')->name('setting.rewardPointStore');

            Route::get('general_setting/change-theme/{theme}', 'changeTheme');
            Route::get('mail_setting', 'mailSetting')->name('setting.mail');
            Route::get('sms_setting', 'smsSetting')->name('setting.sms');
            Route::get('createsms', 'createSms')->name('setting.createSms');
            Route::post('sendsms', 'sendSms')->name('setting.sendSms');
            Route::get('hrm_setting', 'hrmSetting')->name('setting.hrm');
            Route::post('hrm_setting_store', 'hrmSettingStore')->name('setting.hrmStore');
            Route::post('mail_setting_store', 'mailSettingStore')->name('setting.mailStore');
            Route::post('sms_setting_store', 'smsSettingStore')->name('setting.smsStore');
            Route::get('pos_setting', 'posSetting')->name('setting.pos');
            Route::post('pos_setting_store', 'posSettingStore')->name('setting.posStore');
            Route::get('empty-database', 'emptyDatabase')->name('setting.emptyDatabase');
         });
        Route::get('backup', 'backup')->name('setting.backup');
    });


    Route::controller(ExpenseCategoryController::class)->group(function () {
        Route::get('expense_categories/gencode', 'generateCode');
        Route::post('expense_categories/import', 'import')->name('expense_category.import');
        Route::post('expense_categories/deletebyselection', 'deleteBySelection');
        Route::get('expense_categories/all', 'expenseCategoriesAll')->name('expense_category.all');;
    });
    Route::resource('expense_categories', ExpenseCategoryController::class);


    Route::controller(ExpenseController::class)->group(function () {
        Route::post('expenses/expense-data', 'expenseData')->name('expenses.data');
        Route::post('expenses/deletebyselection', 'deleteBySelection');
    });
    Route::resource('expenses', ExpenseController::class);


    Route::controller(GiftCardController::class)->group(function () {
        Route::get('gift_cards/gencode', 'generateCode');
        Route::post('gift_cards/recharge/{id}', 'recharge')->name('gift_cards.recharge');
        Route::post('gift_cards/deletebyselection', 'deleteBySelection');
    });
    Route::resource('gift_cards', GiftCardController::class);

    Route::resource('couriers', CourierController::class);

    Route::controller(CouponController::class)->group(function () {
        Route::get('coupons/gencode', 'generateCode');
        Route::post('coupons/deletebyselection', 'deleteBySelection');
    });
    Route::resource('coupons', CouponController::class);




	//accounting routes
    Route::controller(AccountsController::class)->group(function () {
        Route::get('make-default/{id}', 'makeDefault');
        Route::get('balancesheet', 'balanceSheet')->name('accounts.balancesheet');
        Route::post('account-statement', 'accountStatement')->name('accounts.statement');
        Route::get('accounts/all', 'accountsAll')->name('account.all');
    });
    Route::resource('accounts', AccountsController::class);


    Route::resource('money-transfers', MoneyTransferController::class);


	//HRM routes
	Route::post('departments/deletebyselection', [DepartmentController::class,'deleteBySelection']);
	Route::resource('departments', DepartmentController::class);


	Route::post('employees/deletebyselection', [EmployeeController::class, 'deleteBySelection']);
	Route::resource('employees', EmployeeController::class);


	Route::post('payroll/deletebyselection', [PayrollController::class, 'deleteBySelection']);
	Route::resource('payroll', PayrollController::class);


    Route::post('attendance/delete/{date}/{employee_id}', [AttendanceController::class, 'delete'])->name('attendances.delete');
	Route::post('attendance/deletebyselection', [AttendanceController::class, 'deleteBySelection']);
    Route::post('attendance/importDeviceCsv', [AttendanceController::class, 'importDeviceCsv'])->name('attendances.importDeviceCsv');
	Route::resource('attendance', AttendanceController::class);


    Route::controller(StockCountController::class)->group(function () {
        Route::post('stock-count/finalize', 'finalize')->name('stock-count.finalize');
        Route::get('stock-count/stockdif/{id}', 'stockDif');
        Route::get('stock-count/{id}/qty_adjustment', 'qtyAdjustment')->name('stock-count.adjustment');
    });
    Route::resource('stock-count', StockCountController::class);


    Route::controller(HolidayController::class)->group(function () {
        Route::post('holidays/deletebyselection', 'deleteBySelection');
        Route::get('approve-holiday/{id}', 'approveHoliday')->name('approveHoliday');
        Route::get('holidays/my-holiday/{year}/{month}', 'myHoliday')->name('myHoliday');
    });
    Route::resource('holidays', HolidayController::class);


    Route::controller(CashRegisterController::class)->group(function () {
        Route::prefix('cash-register')->group(function () {
            Route::get('/', 'index')->name('cashRegister.index');
            Route::get('check-availability/{warehouse_id}', 'checkAvailability')->name('cashRegister.checkAvailability');
            Route::post('store', 'store')->name('cashRegister.store');
            Route::get('getDetails/{id}', 'getDetails');
            Route::get('showDetails/{warehouse_id}', 'showDetails');
            Route::post('close', 'close')->name('cashRegister.close');
        });
    });


    Route::controller(NotificationController::class)->group(function () {
        Route::prefix('notifications')->group(function () {
            Route::get('/', 'index')->name('notifications.index');
            Route::post('store', 'store')->name('notifications.store');
            Route::get('mark-as-read', 'markAsRead');
        });
    });


	Route::resource('currency', CurrencyController::class);

	Route::resource('custom-fields', CustomFieldController::class);

	Route::post('woocommerce-install', [AddonInstallController::class,'woocommerceInstall'])->name('woocommerce.install');

    Route::post('ecommerce-install', [AddonInstallController::class,'ecommerceInstall'])->name('ecommerce.install');

});

