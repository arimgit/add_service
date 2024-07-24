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

/*Route::get('/', function () {
    return view('welcome');
});*/
// Route to show the preview popup
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::middleware('auth')->group(function () {
    Route::get('/popup-content/create', [PopupContentController::class, 'create'])->name('popup-content.create');
    Route::post('/popup-content/store', [PopupContentController::class, 'store'])->name('popup-content.store');
    Route::get('/popup-content/preview/{id}', [PopupContentController::class, 'show'])->name('popup.preview');
});
/*Route::get('/api/popup/{id}', [PopupContentController::class, 'show']);*/
Route::get('/popup-data/{popid}', [PopupContentController::class, 'getPopupData']);





