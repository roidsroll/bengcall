<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AccessController as AdminAccessController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderServiceController as AdminOrderServiceController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController as UserProductController;

Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/products', UserProductController::class)->name('products');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('admin.dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/access', [AdminAccessController::class, 'index'])->name('admin.access.index');
        Route::get('/access/{role}', [AdminAccessController::class, 'edit'])->name('admin.access.edit');
        Route::put('/access/{role}', [AdminAccessController::class, 'update'])->name('admin.access.update');

        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        Route::get('/roles', [AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/create', [AdminRoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy');

        Route::get('/menus', [AdminMenuController::class, 'index'])->name('admin.menus.index');
        Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('admin.menus.create');
        Route::post('/menus', [AdminMenuController::class, 'store'])->name('admin.menus.store');
        Route::get('/menus/{menu}/edit', [AdminMenuController::class, 'edit'])->name('admin.menus.edit');
        Route::put('/menus/{menu}', [AdminMenuController::class, 'update'])->name('admin.menus.update');
        Route::delete('/menus/{menu}', [AdminMenuController::class, 'destroy'])->name('admin.menus.destroy');

        Route::get('/branches', [AdminBranchController::class, 'index'])->name('admin.branches.index');
        Route::get('/branches/create', [AdminBranchController::class, 'create'])->name('admin.branches.create');
        Route::post('/branches', [AdminBranchController::class, 'store'])->name('admin.branches.store');
        Route::get('/branches/{branch}/edit', [AdminBranchController::class, 'edit'])->name('admin.branches.edit');
        Route::put('/branches/{branch}', [AdminBranchController::class, 'update'])->name('admin.branches.update');
        Route::delete('/branches/{branch}', [AdminBranchController::class, 'destroy'])->name('admin.branches.destroy');

        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get('/brands', [AdminBrandController::class, 'index'])->name('admin.brands.index');
        Route::get('/brands/create', [AdminBrandController::class, 'create'])->name('admin.brands.create');
        Route::post('/brands', [AdminBrandController::class, 'store'])->name('admin.brands.store');
        Route::get('/brands/{brand}/edit', [AdminBrandController::class, 'edit'])->name('admin.brands.edit');
        Route::put('/brands/{brand}', [AdminBrandController::class, 'update'])->name('admin.brands.update');
        Route::delete('/brands/{brand}', [AdminBrandController::class, 'destroy'])->name('admin.brands.destroy');

        Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    });

    Route::middleware('checkmenu')->group(function () {
        // Taruh route fitur yang aksesnya diatur via assign menu (menu_roles) di sini.
        // contoh:
        // Route::get('/booking', ...);
        // Route::get('/laporan', ...);

        Route::get('/discounts', [AdminDiscountController::class, 'index'])->name('admin.discounts.index');
        Route::get('/discounts/create', [AdminDiscountController::class, 'create'])->name('admin.discounts.create');
        Route::post('/discounts', [AdminDiscountController::class, 'store'])->name('admin.discounts.store');
        Route::get('/discounts/{discount}/edit', [AdminDiscountController::class, 'edit'])->name('admin.discounts.edit');
        Route::put('/discounts/{discount}', [AdminDiscountController::class, 'update'])->name('admin.discounts.update');
        Route::delete('/discounts/{discount}', [AdminDiscountController::class, 'destroy'])->name('admin.discounts.destroy');

        Route::get('/supplier', [AdminSupplierController::class, 'index'])->name('admin.suppliers.index');
        Route::get('/supplier/create', [AdminSupplierController::class, 'create'])->name('admin.suppliers.create');
        Route::post('/supplier', [AdminSupplierController::class, 'store'])->name('admin.suppliers.store');
        Route::get('/supplier/{supplier}/edit', [AdminSupplierController::class, 'edit'])->name('admin.suppliers.edit');
        Route::put('/supplier/{supplier}', [AdminSupplierController::class, 'update'])->name('admin.suppliers.update');
        Route::delete('/supplier/{supplier}', [AdminSupplierController::class, 'destroy'])->name('admin.suppliers.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/create', [AdminOrderController::class, 'create'])->name('admin.orders.create');
        Route::post('/orders', [AdminOrderController::class, 'store'])->name('admin.orders.store');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

        Route::get('/order-services', [AdminOrderServiceController::class, 'index'])->name('admin.order-services.index');
        Route::get('/order-services/{orderService}', [AdminOrderServiceController::class, 'show'])->name('admin.order-services.show');
    });
});

Route::prefix('user')->middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/', HomeController::class)->name('user.home');
});
