<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PopupContentController;
use App\Http\Controllers\AuthController;

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

// AUTH ROUTER
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::middleware('auth')->group(function () {
    Route::get('/manage/popup/{popupId?}', [PopupContentController::class, 'managePopup'])->name('ad_web_manage_popup');
    Route::post('/popup/create', [PopupContentController::class, 'create'])->name('ad_web_popup_create');
});

// WEB ROUTER
Route::get('/list/popup', [PopupContentController::class, 'listPopup'])->name('ad_web_list_popup');
Route::get('/popup/data/{popId}', [PopupContentController::class, 'getPopupData'])->name('ad_web_popup_data');

Route::post('/toggle/status/{id}', [PopupContentController::class, 'toggleStatus'])->name('ad_web_toggle_status');


// API ROUTER
Route::post('api/manage/popup/formdata', [PopupContentController::class, 'managePopupFormData'])->name('api_manage_popup_formdata');
Route::get('/build/popup/js', [PopupContentController::class, 'buildPopupJs'])->name('build_popup_js');
