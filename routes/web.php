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
    Route::post('/popup-content/store', [PopupContentController::class, 'store'])->name('popup-content.store');
});

// WEB ROUTER
Route::get('/list/popup', [PopupContentController::class, 'listPopup'])->name('ad_web_list_popup');
Route::get('/popup-data/{popid}', [PopupContentController::class, 'getPopupData']);
Route::get('/popup-content/{id}', [PopupContentController::class, 'showPopupContent']);

// API ROUTER
Route::post('api/save-popup-data', [PopupContentController::class, 'savePopupData']);
