<?php

use App\Http\Controllers\Api\AffliateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\SendMoneyController;
use App\Http\Controllers\Api\KycDetails;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UpinController;
use App\Http\Controllers\Api\RechargeController;
use App\Http\Controllers\Api\DthRechargeController;
use App\Http\Controllers\Api\AppBanner;
use App\Http\Controllers\Api\AppPopupController;
use App\Http\Controllers\Api\UnimartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\UserOrderController;
//use App\Http\Controllers\Api\Deals\DealProductController;
use App\Http\Controllers\Api\CuelinksController;
use App\Http\Controllers\Api\KycDetailController;
use App\Http\Controllers\Api\ShippingBillingController;
use App\Http\Controllers\Api\DeleteUserAcController;
use App\Http\Controllers\Api\EducationCoursesController;
use App\Http\Controllers\Api\InrController;
use App\Http\Controllers\Api\UniPostController;
use App\Http\Controllers\Api\CardServiceController;
use App\Http\Controllers\Api\CategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('download-invoice/{id}',[UserOrderController::class, 'downloadInvoice']);

Route::controller(AuthController::class)->group(function () {
    // Route::post('login-with-email', 'emailLogin');
    // Route::post('login-with-uid', 'loginWithUserId');
    // Route::post('verify-login-otp', 'verifyOtp');
    Route::post('forget-password-otp', 'forgetPassword');
    Route::post('verify-forget-otp', 'verifyFrogetOtp');
    Route::post('change-password', 'changePassword');
    // Route::post('resend-login-otp', 'resendOtp');
    Route::post('media-link', 'social_media');

    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);
});


// Route::controller(RegisterController::class)->group(function () {
//     Route::post('register', 'store');
//     Route::post('user-availability', 'userAvailability');
//     Route::post('verify-otp', 'verifyOtp');
//     Route::post('resend-otp', 'resendOtp');
//     Route::post('test-1', 'test1');
// });

Route::get('appPopup', [AppPopupController::class, 'getList']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('profile', [AuthController::class, 'getProfile']);

    //category
    Route::get('category-list', [CategoryController::class, 'index']);
    Route::get('category/{id}', [CategoryController::class, 'show']);

    //wallet
    Route::get('wallet', [WalletController::class, 'index']);
    Route::get('passbook-amount/{type}', [WalletController::class, 'passbookAmount'])->where('type', 'both|credit|debit');
    Route::get('passbook-earning/{type}', [WalletController::class, 'earningPassbook'])->where('type', 'both|credit|debit');
    Route::get('passbook-unicash/{type}', [WalletController::class, 'unicashPassbook'])->where('type', 'both|credit|debit');
    Route::get('all-passbook', [WalletController::class, 'allPassbook']);

    Route::post('kyc-personal-detail', [KycDetailController::class, 'personalDetails']);
    Route::post('kyc-docs', [KycDetailController::class, 'keyDocs']);
    Route::post('kyc-bank-detail', [KycDetailController::class, 'bankDetails']);
    Route::get('pincode-detail', [KycDetailController::class, 'pincodeDetail']);
    Route::get('ifsc-detail', [KycDetailController::class, 'ifscDetail']);

    //epin
    Route::post('get-epin-otp', [UpinController::class, 'get_epin_otp']);
    Route::post('verify-epin-otp', [UpinController::class, 'verify_epin_otp']);
    Route::post('generate-epin', [UpinController::class, 'generate_epin']);
    Route::post('reset-epin-plan', [UpinController::class, 'reset_epin_plan']);
    Route::post('get-reset-epin-otp', [UpinController::class, 'get_reset_epin_otp']);
    Route::post('verify-reset-epin-otp', [UpinController::class, 'verify_reset_epin_otp']);


    Route::post('send-money', [SendMoneyController::class, 'sendMoney']);

    //recharge
    Route::get('find-operator', [RechargeController::class, 'findOperator']);
    Route::get('find-plane-title', [RechargeController::class, 'allPlansTitle']);
    Route::get('single-plan', [RechargeController::class, 'singlePlan']);
    Route::get('all-plans', [RechargeController::class, 'allPlanOffer']);
    Route::post('recharge', [RechargeController::class, 'recharge']);
    Route::get('recharge-history', [RechargeController::class, 'rechargeHistory']);

    Route::get('address-list', [ShippingBillingController::class, 'index']);
    Route::post('address-save', [ShippingBillingController::class, 'store']);
    Route::post('address-update/{id}', [ShippingBillingController::class, 'update']);
    Route::get('remove-address/{id}', [ShippingBillingController::class, 'remove']);

    Route::get('product-list', [ProductController::class, 'productList']);

    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::post('update-quantity', [CartController::class, 'updateQuantity']);
    Route::get('remove-cart-item/{cart_id}', [CartController::class, 'removeCart']);
    Route::get('cart-items', [CartController::class, 'getCartList']);

    Route::get('order-list', [UserOrderController::class, 'index']);
    Route::post('save-order', [UserOrderController::class, 'save']);

    //deleteAc
    Route::get('delete-account', [DeleteUserAcController::class, 'index']);

    //education
    Route::get('course-list', [EducationCoursesController::class, 'index']);
    Route::post('purchase-course', [EducationCoursesController::class, 'purchaseCourse']);

    //inr
    Route::get('get-affliate-list', [AffliateController::class, 'index']);
    Route::post('save-affliate', [AffliateController::class, 'save']);
    Route::get('get-click-list', [AffliateController::class, 'getClickList']);

    //unimart
    Route::get('uni-mart-list', [UnimartController::class, 'index']);
    //PostList
    Route::get('get-post-list', [UniPostController::class, 'getPostList']);
    Route::get('uni-post-plan', [UniPostController::class, 'uniPostPlan']);
    Route::post('purchase-plan', [UniPostController::class, 'planPo']);

    //slider
    Route::get('banner', [AppBanner::class, 'getSlider']);


   

    
});

 //card service
 Route::get('get-card-list', [CardServiceController::class, 'getCardList']);
 Route::post('save-lead', [CardServiceController::class, 'saveLead']);
 Route::post('get-lead-status', [CardServiceController::class, 'getLeadStatus']);

Route::post('digital-kyc', [KycDetailController::class, 'digitalKyc']);
// addhar verification
Route::get('digilocker-redirect/{unique_id}', [KycDetailController::class, 'digilockerRedirect']);

// Route::post('dthRecharge', [DthRechargeController::class, 'dthRecharge']);

// Route::get('make-uid', [RegisterController::class, 'generate_user_nm']);
// Route::post('user-registeration3', [RegisterController::class, 'saveUserSt3']);
// Route::post('user-registeration4', [RegisterController::class, 'saveUserSt4']);
// Route::post('test', [RegisterController::class, 'test']);



//send money

// Route::post('rechargeEventes', [RechargeController::class, 'rechargeEventes']);
// Route::post('userLvl', [RechargeController::class, 'userLvl']);
Route::get('qr-code', [AuthController::class, 'generateQRCode']);

//merchant
Route::post('merchantSignUp1', [MerchantController::class, 'save_merchant_st1']);
Route::post('merchantSignUp2', [MerchantController::class, 'save_merchant_st2']);
Route::post('merchantSignUp3', [MerchantController::class, 'save_merchant_st3']);
Route::post('merchat-scan', [MerchantController::class, 'merchantScan']);
Route::post('payToMerchant', [MerchantController::class, 'payToMerchant']);


//product
// Route::post('product-section', [ProductController::class, 'product_section']);


Route::get('campaigns', [CuelinksController::class, 'Campaigns']);
Route::get('campaignsCount', [CuelinksController::class, 'CampaignsCount']);

///Deals
// Route::post('deal-products', [DealProductController::class, 'productList']);
// Route::post('deal-products-single', [DealProductController::class, 'productListSingle']);




