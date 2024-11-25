<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/index', [HomeController::class, 'indexMultiple'])->name('home2');
Route::get('/1', [HomeController::class, 'index1']);
Route::get('/event', [HomeController::class, 'event']);
Route::get('/event2', [HomeController::class, 'event2']);
Route::get('/eventall', [HomeController::class, 'allEvent']);
Route::get('/2', [HomeController::class, 'index2']);

Route::get('/fake-login', [HomeController::class, 'fakeLogin'])->name('fake.login');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

Route::get('/upload-image', [HomeController::class, 'uploadImage']);
Route::post('/upload-image', [HomeController::class, 'doUploadImage']);
Route::get('/upload-file', [HomeController::class, 'uploadFiles']);
Route::post('/upload-file', [HomeController::class, 'doUploadFiles']);
Route::get('/test', [HomeController::class, 'test']);
Route::get('/send-mail', [HomeController::class, 'sendMail']);
Route::post('/send-mail', [HomeController::class, 'doSendMail']);

Route::get('/reset-password', [HomeController::class, 'resetPassword']);
Route::post('/reset-password', [HomeController::class, 'doResetPassword']);

Route::get('/change-status', [HomeController::class, 'changeStatus'])->name('change.status');
Route::get('/change-status2', [HomeController::class, 'changeStatus2'])->name('change.status2');


Route::get('/the-test', [HomeController::class, 'test']);
Route::get('/notification', [HomeController::class, 'notification']);


Route::get('/quote', [HomeController::class, 'quote'])->name('quote');
Route::post('/add-quote', [HomeController::class, 'addQuote'])->name('add.quote');

Route::get('/scroll', [HomeController::class, 'scroll']);
Route::get('/encDes', [HomeController::class, 'encDes']);

Route::get('/history-management', [HomeController::class, 'historyManagement']);
Route::get('/history-management/{name}', [HomeController::class, 'historyManagement']);

Route::get('count/{name}', [HomeController::class, 'count']);
Route::get('dsa', [HomeController::class, 'rotatingArrayFromKPosition']);

Route::get('update-status', [HomeController::class, 'updateStatus']);
Route::post('update-status', [HomeController::class, 'doUpdateStatus']);

Route::get('/shop', [ShopController::class, 'index'])->name('shop.home');
Route::get('/buy-product/{product_id}', [ShopController::class, 'buyProduct'])->name('buy.product');
Route::post('/buy-product', [ShopController::class, 'doBuyProduct'])->name('do.buy.product');
Route::get('/retrive-function/{id}', [ShopController::class, 'retriveFunction']);
Route::get('/retrive-function', [ShopController::class, 'retriveFunction']);
Route::get('/payment-confirm', [ShopController::class, 'paymentConfirm'])->name('payment.confirm');
Route::get('/thank-you/{payment_id}', [ShopController::class, 'thankYou'])->name('thank.you');
Route::post('/confirm_payment', [ShopController::class, 'paymentConfirmed'])->name('payment.confirmed');
Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my.orders');
Route::post('/cancel-order', [OrderController::class, 'cancelOrder'])->name('cancel.order');
Route::get('/more-payment/{payment_id?}', [OrderController::class, 'recurringPayment'])->name('recurring.payment');