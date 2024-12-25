<?php

use App\Services\SMS;
use App\Services\Autochat;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ChangeLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SmsPatternController;

use App\Http\Controllers\MessageTextController;
use App\Http\Controllers\PackagePriceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SmsPatternCategoryController;
use App\Models\ChangeLog;

// Auth
Route::get('login', [AdminController::class, 'login'])->name('login');
Route::post('login', [AdminController::class, 'loginStore'])->name('login.store');
// Route::get('register', [AdminController::class, 'register'])->name('register');
// Route::post('register', [AdminController::class, 'registerStore'])->name('register.store');
Route::post('logout', [AdminController::class, 'logout'])->name('logout');

// Payment
Route::get('payment/create/{username}/{type}', [PaymentController::class, 'create'])->name('createPayment');

// Payment
Route::post('payment/start', [PaymentController::class, 'start'])->name('startPayment');
Route::get('payment/verify', [PaymentController::class, 'verify'])->name('verifyPayment');

// Package
Route::post('package/change', [PackageController::class, 'changePackage'])->name('package.change');

// Offer
Route::resource('offer', OfferController::class);
Route::post('offer/check', [OfferController::class, 'check'])->name('offer.check');

Route::middleware('auth')->group(function () {
    Route::get('/run-sql', [ChangeLogController::class, 'runSql']);

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('update-magfa-credit', [DashboardController::class, 'updateMagfaCredit'])->name('updateMagfaCredit');

    // Ticket
    Route::get('ticket', [TicketController::class, 'index'])->name('tickets');
    Route::match(['get', 'post'], 'ticket-chat/{ticket}', [TicketController::class, 'openChat'])->name('openChat');

    //change logs
    Route::get('change-log', [ChangeLogController::class, 'index'])->name('changeLogIndex');
    Route::delete('change-log/{changeLog}', [ChangeLogController::class, 'delete'])->name('changeLogDelete');
    Route::get('sql', [ChangeLogController::class, 'index']);


    // Question
    Route::get('question-component', [QuestionController::class, 'componentIndex'])->name('q-c.index');
    Route::post('question-component', [QuestionController::class, 'componentStore'])->name('q-c.store');
    Route::delete('question-component-delete/{item}', [QuestionController::class, 'componentDelete'])->name('q-c.delete');
    Route::resource('question', QuestionController::class);

    // Account
    Route::resource('account', AccountController::class);
    Route::post('account-status/{account}', [AccountController::class, 'changeStatus'])->name('account.changeStatus');
    Route::get('account/{account}/database', [AccountController::class, 'showDb'])->name('account.showDatabase');
    Route::put('account/{account}/database', [AccountController::class, 'storeDb'])->name('account.storeDatabase');
    Route::get('account-make-privilages', [AccountController::class, 'privilages']);
    Route::get('account/{account}/license', [AccountController::class, 'license'])->name('account.license');
    Route::post('account-license-status/{account}', [AccountController::class, 'changeLicenseStatus'])->name('account.changeLicenseStatus');


   

    // User
    Route::resource('user', UserController::class);
    Route::get('change-password/{user_id}', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('login-record', [UserController::class, 'loginRecord'])->name('user.loginRecord');
    Route::get('visit-record', [UserController::class, 'visitRecord'])->name('user.visitRecord');
    Route::get('deactive-users', [UserController::class, 'userActivity'])->name('user.activity');
    Route::get('phonebook', [UserController::class, 'phonebook'])->name('phonebook');

    // Package
    Route::resource('package', PackageController::class);
    Route::get('package/{package}/menu', [PackageController::class, 'menu'])->name('package.menu');
    Route::post('package/{package}/menu', [PackageController::class, 'storeMenu'])->name('package.menu.store');


    // Package Price
    Route::resource('package-price', PackagePriceController::class);

    // Menu
    Route::resource('menu', MenuController::class);

    // // Payment
    // Route::post('payment/start', [PaymentController::class, 'start'])->name('startPayment');
    // Route::get('payment/verify', [PaymentController::class, 'verify'])->name('verifyPayment');
    Route::get('payment', [PaymentController::class, 'index'])->name('payment');

    // Option
    Route::get('option', [OptionController::class, 'index'])->name('option.index');
    Route::post('store-option', [OptionController::class, 'store'])->name('option.store');

    // Message
    Route::resource('message', MessageController::class);

    // Message Text
    Route::resource('message-text', MessageTextController::class);

    // Sms Pattern Category
    Route::resource('sms-pattern-category', SmsPatternCategoryController::class);

    // Sms Pattern
    Route::resource('sms-pattern', SmsPatternController::class);
    
    //Report
    Route::get('/report/payment', [ReportController::class, 'paymentReport'])->name('report.payment');
});

Route::get('/test-chat', function () {
    $accounts= App\Models\Account::all()->first();
    $key = base64_decode(Config::get('app.custom_key'));
    // dd($key);
    $encrypter = new Illuminate\Encryption\Encrypter($key, Config::get('app.cipher'));
    //   dd($encrypter);
    $name = $encrypter->decryptString($accounts->db_name);


});
