<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SmsPatternController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('ApiLogMiddleware')->group(function () {
    Route::post('/get-data', [ApiController::class, 'getData']);

    Route::post('/checkCode', [ApiController::class, 'checkCode']);
    
    Route::post('/check-user-key', [ApiController::class, 'checkUserKey']);
    Route::post('/user-db', [ApiController::class, 'connectdb']);
    Route::post('/data-collect', [ApiController::class, 'collectData']);
    Route::post('/data-collect-admin', [ApiController::class, 'collectAdminData']);
    Route::post('/check-pcCode-key', [ApiController::class, 'pcCodeKey']);
    Route::post('/check-license-key', [ApiController::class, 'loginCheck']);
    Route::post('/data-sync', [ApiController::class, 'syncDataTable']);
    Route::post('/data-record-collect', [ApiController::class, 'dataRecordCollect']);
    Route::post('/sync/store', [ApiController::class, 'storeSyncData']);
    Route::post('/update-sync-status', [ApiController::class, 'updateStatus']);
    Route::post('/deactive-license', [ApiController::class, 'deactiveLicense']);
    Route::post('/check-license-activation', [ApiController::class, 'checkLicenseActivaation']);
});



// //User
// Route::get('check-login', [UserController::class, 'checkLogin']);
// Route::get('save-register', [UserController::class, 'saveRegister']);
// Route::get('check-mobile', [UserController::class, 'checkMobile']);
// Route::get('store-user', [UserController::class, 'apiStore']);
// Route::get('new-password', [UserController::class, 'newPassword']);
// Route::get('update-password', [UserController::class, 'apiUpdatePassword']);

// //Update
// Route::get('get-updates', [UserController::class, 'getItems']);

// //Payment
// Route::get('get-payment', [PaymentController::class, 'getItems']);
