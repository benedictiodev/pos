<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CashInController;
use App\Http\Controllers\CashOutController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\ManagementUserController;
use App\Http\Controllers\MigrationDataController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RemarksCashFlowController;
use App\Http\Middleware\RedirectWeb;
use Illuminate\Support\Facades\Route;


// Route::prefix('/migration')->group(function() {
//     Route::get('/', [MigrationDataController::class, 'add_data_discount_for_order_old']);
// });

Route::get('/redirect', function () {
    return view('auth.redirect');
})->name('redirect');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'post_login'])->name('post_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix("/dashboard")->middleware([
    // RedirectWeb::class, 
    'auth'
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::prefix("/master-data")->group(function () {
        Route::prefix("/product")->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('dashboard.master-data.product');
            Route::get('/create', [ProductController::class, 'create'])->name('dashboard.master-data.product.create');
            Route::post('/', [ProductController::class, 'store'])->name('dashboard.master-data.product.post');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('dashboard.master-data.product.edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('dashboard.master-data.product.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('dashboard.master-data.product.delete');
        });
        Route::prefix("/category-product")->group(function () {
            Route::get('/', [CategoryProductController::class, 'index'])->name('dashboard.master-data.category-product');
            Route::get('/create', [CategoryProductController::class, 'create'])->name('dashboard.master-data.category-product.create');
            Route::post('/', [CategoryProductController::class, 'store'])->name('dashboard.master-data.category-product.post');
            Route::get('/{id}/edit', [CategoryProductController::class, 'edit'])->name('dashboard.master-data.category-product.edit');
            Route::put('/{id}', [CategoryProductController::class, 'update'])->name('dashboard.master-data.category-product.update');
            Route::delete('/{id}', [CategoryProductController::class, 'destroy'])->name('dashboard.master-data.category-product.delete');
        });
        Route::prefix("/funds")->group(function () {
            Route::get('/', [FundsController::class, 'index'])->name('dashboard.master-data.funds');
            Route::get('/create', [FundsController::class, 'create'])->name('dashboard.master-data.funds.create');
            Route::post('/', [FundsController::class, 'store'])->name('dashboard.master-data.funds.post');
            Route::get('/{id}/edit', [FundsController::class, 'edit'])->name('dashboard.master-data.funds.edit');
            Route::put('/{id}', [FundsController::class, 'update'])->name('dashboard.master-data.funds.update');
            Route::delete('/{id}', [FundsController::class, 'destroy'])->name('dashboard.master-data.funds.delete');
        });
        Route::prefix("/remarks-cash-flow")->group(function () {
            Route::get('/', [RemarksCashFlowController::class, 'index'])->name('dashboard.master-data.remarks-cash-flow');
            Route::get('/create', [RemarksCashFlowController::class, 'create'])->name('dashboard.master-data.remarks-cash-flow.create');
            Route::post('/', [RemarksCashFlowController::class, 'store'])->name('dashboard.master-data.remarks-cash-flow.post');
            Route::get('/{id}/edit', [RemarksCashFlowController::class, 'edit'])->name('dashboard.master-data.remarks-cash-flow.edit');
            Route::put('/{id}', [RemarksCashFlowController::class, 'update'])->name('dashboard.master-data.remarks-cash-flow.update');
            Route::delete('/{id}', [RemarksCashFlowController::class, 'destroy'])->name('dashboard.master-data.remarks-cash-flow.delete');
        });
    });

    // Finance
    Route::prefix("/finance")->group(function () {
        Route::prefix("/cash-in")->group(function () {
            Route::get('/', [CashInController::class, 'index'])->name('dashboard.finance.cash-in');
            Route::get('/create', [CashInController::class, 'create'])->name('dashboard.finance.cash-in.create');
            Route::post('/', [CashInController::class, 'store'])->name('dashboard.finance.cash-in.post');
            Route::get('/{id}/edit', [CashInController::class, 'edit'])->name('dashboard.finance.cash-in.edit');
            Route::put('/{id}', [CashInController::class, 'update'])->name('dashboard.finance.cash-in.update');
            Route::delete('/{id}', [CashInController::class, 'destroy'])->name('dashboard.finance.cash-in.delete');
        });
        Route::prefix("/cash-out")->group(function () {
            Route::get('/', [CashOutController::class, 'index'])->name('dashboard.finance.cash-out');
            Route::get('/create', [CashOutController::class, 'create'])->name('dashboard.finance.cash-out.create');
            Route::post('/', [CashOutController::class, 'store'])->name('dashboard.finance.cash-out.post');
            Route::get('/{id}/edit', [CashOutController::class, 'edit'])->name('dashboard.finance.cash-out.edit');
            Route::put('/{id}', [CashOutController::class, 'update'])->name('dashboard.finance.cash-out.update');
            Route::delete('/{id}', [CashOutController::class, 'destroy'])->name('dashboard.finance.cash-out.delete');
        });
        Route::get("/cash-flow-daily", [CashFlowController::class, 'list_daily'])->name('dashboard.finance.cash-flow-daily');
        Route::get("/cash-flow-monthly", [CashFlowController::class, 'list_monthly'])->name('dashboard.finance.cash-flow-monthly');
        Route::prefix("/funds")->group(function () {
            Route::get('/', [FundsController::class, 'funds_finance'])->name('dashboard.finance.funds');
            Route::get('/create', [FundsController::class, 'funds_finance_create'])->name('dashboard.finance.funds.create');
            Route::post('/', [FundsController::class, 'funds_finance_post'])->name('dashboard.finance.funds.post');
        });
        Route::prefix("/equite")->group(function () {
            Route::post("/add", [CashFlowController::class, 'add_equite'])->name('dashboard.finance.equite.post');
            Route::post("/closing", [CashFlowController::class, 'add_closing_cycle'])->name('dashboard.finance.equite.closing');
        });
    });

    // Order
    Route::prefix("/order")->group(function () {
        Route::get("/", [OrderController::class, 'order_active'])->name('dashboard.order.order_active');
        Route::get("/new-order", [OrderController::class, 'add_new_order'])->name('dashboard.order.order_active.add_new_order');
        Route::post("/post_new_order", [OrderController::class, 'post_new_order'])->name('dashboard.order.order_active.post_new_order');
        Route::get("/history", [OrderController::class, 'order_history'])->name('dashboard.order.order_history');
        Route::get("/{id}/edit", [OrderController::class, 'order_history_edit'])->name('dashboard.order.order_history_edit');
        Route::put("/{id}", [OrderController::class, 'order_history_update'])->name('dashboard.order.order_history_update');
        Route::get("/{id}/detail", [OrderController::class, 'order_detail'])->name('dashboard.order.order_detail');
        Route::delete("/{id}", [OrderController::class, 'delete_order'])->name('dashboard.order.delete_order');
        Route::get("/update/{id}", [OrderController::class, 'edit_order'])->name('dashboard.order.edit_order');
        Route::put("/update/{id}", [OrderController::class, 'update_order'])->name('dashboard.order.update_order');
        Route::get("/report", [OrderController::class, 'report'])->name('dashboard.order.report');
    });

    // Profile
    Route::prefix("/profile")->group(function () {
        Route::get("/", [AuthController::class, 'profile'])->name('dashboard.profile');
        Route::post("/", [AuthController::class, 'post_profile'])->name('dashboard.profile.post');
    });
    Route::prefix("/change_password")->group(function () {
        Route::get("/", [AuthController::class, 'change_password'])->name('dashboard.change_password');
        Route::post("/", [AuthController::class, 'post_change_password'])->name('dashboard.change_password.post');
    });

    // Company
    Route::prefix("/company")->group(function () {
        Route::prefix("/profile")->group(function () {
            Route::get('/', [CompanyController::class, 'index_company'])->name('dashboard.company.profile');
            Route::put('/{id}', [CompanyController::class, 'update_company'])->name('dashboard.company.profile.update');
            Route::get('/settings', [CompanyController::class, 'index_setting'])->name('dashboard.company.setting');
            Route::put('/settings/{id}', [CompanyController::class, 'update_setting'])->name('dashboard.company.setting.update');
        });
    });

    // Presence
    Route::prefix("/presence")->group(function () {
        Route::get("/", [PresenceController::class, 'index'])->name('dashboard.presence.index');
        Route::post("/", [PresenceController::class, 'store'])->name('dashboard.presence.store');
        Route::get("/presence_history", [PresenceController::class, 'history'])->name('dashboard.presence.presence_history');
        Route::get("/presence_user", [PresenceController::class, 'presence_user'])->name('dashboard.presence.presence_user');
        Route::post("/presence_user", [PresenceController::class, 'presence_user_store'])->name('dashboard.presence.presence_user_store');
    });

    // Management User
    Route::prefix("/management-user")->group(function () {
        Route::get("/users", [ManagementUserController::class, 'user_index'])->name('dashboard.management-user.user.index');
        Route::get("/users/create", [ManagementUserController::class, 'user_create'])->name('dashboard.management-user.user.create');
        Route::get("/users/{id}/edit", [ManagementUserController::class, 'user_edit'])->name('dashboard.management-user.user.edit');
        Route::put("/users/{id}", [ManagementUserController::class, 'user_update'])->name('dashboard.management-user.user.update');
        Route::delete("/users/{id}", [ManagementUserController::class, 'user_destroy'])->name('dashboard.management-user.user.destroy');
        Route::post("/users", [ManagementUserController::class, 'store'])->name('dashboard.management-user.user.store');
        Route::get("/roles", [ManagementUserController::class, 'history'])->name('dashboard.management-user.role.index');
    });
});
