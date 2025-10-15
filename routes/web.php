<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Admin\OrderManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman setelah login

Route::name('admin.')->prefix('admin')->middleware('auth', 'role:Super Admin,Admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);

    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [OrderManagementController::class, 'destroy'])->name('orders.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

Route::name('customer.')->prefix('customer')->middleware('auth', 'role:Customer')->group(function () {
    Route::get('/dashboard', function () {
        $categories = \App\Models\Category::all();
        $menus = \App\Models\Menu::paginate(6);

        return view('customer.dashboard', compact('categories', 'menus'));
    })->name('dashboard');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{menu}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.form');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}/detail', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
});
