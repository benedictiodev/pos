<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CashInController;
use App\Http\Controllers\CashOutController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\Management\CompanyController as ManagementCompanyController;
use App\Http\Controllers\Management\DashboardController as ManagementDashboardController;
use App\Http\Controllers\ManagementUserController;
use App\Http\Controllers\MigrationDataController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RemarksCashFlowController;
use App\Http\Middleware\ManagementAuth;
use App\Http\Middleware\RedirectWeb;
use Illuminate\Support\Facades\Route;


Route::prefix('/migration')->group(function() {
    // Route::get('/', [MigrationDataController::class, 'add_data_discount_for_order_old']);
    // Route::get('/seeder', [MigrationDataController::class, 'running_seeder']);
    // Route::get('/migrate', [MigrationDataController::class, 'running_migration']);
    Route::get('/optimize', [MigrationDataController::class, 'optimize']);
});

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
            Route::get('/', [ProductController::class, 'index'])->name('dashboard.master-data.product')->middleware(['permission:master data-produk-lihat']);
            Route::get('/create', [ProductController::class, 'create'])->name('dashboard.master-data.product.create')->middleware(['permission:master data-produk-tambah']);
            Route::post('/', [ProductController::class, 'store'])->name('dashboard.master-data.product.post')->middleware(['permission:master data-produk-tambah']);
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('dashboard.master-data.product.edit')->middleware(['permission:master data-produk-perbarui']);
            Route::put('/{id}', [ProductController::class, 'update'])->name('dashboard.master-data.product.update')->middleware(['permission:master data-produk-perbarui']);
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('dashboard.master-data.product.delete')->middleware(['permission:master data-produk-hapus']);
        });
        Route::prefix("/category-product")->group(function () {
            Route::get('/', [CategoryProductController::class, 'index'])->name('dashboard.master-data.category-product')->middleware(['permission:master data-produk kategori-lihat']);
            Route::get('/create', [CategoryProductController::class, 'create'])->name('dashboard.master-data.category-product.create')->middleware(['permission:master data-produk kategori-tambah']);
            Route::post('/', [CategoryProductController::class, 'store'])->name('dashboard.master-data.category-product.post')->middleware(['permission:master data-produk kategori-tambah']);
            Route::get('/{id}/edit', [CategoryProductController::class, 'edit'])->name('dashboard.master-data.category-product.edit')->middleware(['permission:master data-produk kategori-perbarui']);
            Route::put('/{id}', [CategoryProductController::class, 'update'])->name('dashboard.master-data.category-product.update')->middleware(['permission:master data-produk kategori-perbarui']);
            Route::delete('/{id}', [CategoryProductController::class, 'destroy'])->name('dashboard.master-data.category-product.delete')->middleware(['permission:master data-produk kategori-hapus']);
        });
        Route::prefix("/funds")->group(function () {
            Route::get('/', [FundsController::class, 'index'])->name('dashboard.master-data.funds')->middleware(['permission:master data-tipe dana-lihat']);
            Route::get('/create', [FundsController::class, 'create'])->name('dashboard.master-data.funds.create')->middleware(['permission:master data-tipe dana-tambah']);
            Route::post('/', [FundsController::class, 'store'])->name('dashboard.master-data.funds.post')->middleware(['permission:master data-tipe dana-tambah']);
            Route::get('/{id}/edit', [FundsController::class, 'edit'])->name('dashboard.master-data.funds.edit')->middleware(['permission:master data-tipe dana-perbarui']);
            Route::put('/{id}', [FundsController::class, 'update'])->name('dashboard.master-data.funds.update')->middleware(['permission:master data-tipe dana-perbarui']);
            Route::delete('/{id}', [FundsController::class, 'destroy'])->name('dashboard.master-data.funds.delete')->middleware(['permission:master data-tipe dana-hapus']);
        });
        Route::prefix("/remarks-cash-flow")->group(function () {
            Route::get('/', [RemarksCashFlowController::class, 'index'])->name('dashboard.master-data.remarks-cash-flow')->middleware(['permission:master data-keterangan arus kas-lihat']);
            Route::get('/create', [RemarksCashFlowController::class, 'create'])->name('dashboard.master-data.remarks-cash-flow.create')->middleware(['permission:master data-keterangan arus kas-tambah']);
            Route::post('/', [RemarksCashFlowController::class, 'store'])->name('dashboard.master-data.remarks-cash-flow.post')->middleware(['permission:master data-keterangan arus kas-tambah']);
            Route::get('/{id}/edit', [RemarksCashFlowController::class, 'edit'])->name('dashboard.master-data.remarks-cash-flow.edit')->middleware(['permission:master data-keterangan arus kas-perbarui']);
            Route::put('/{id}', [RemarksCashFlowController::class, 'update'])->name('dashboard.master-data.remarks-cash-flow.update')->middleware(['permission:master data-keterangan arus kas-perbarui']);
            Route::delete('/{id}', [RemarksCashFlowController::class, 'destroy'])->name('dashboard.master-data.remarks-cash-flow.delete')->middleware(['permission:master data-keterangan arus kas-hapus']);
        });
    });

    // Finance
    Route::prefix("/finance")->group(function () {
        Route::prefix("/cash-in")->group(function () {
            Route::get('/', [CashInController::class, 'index'])->name('dashboard.finance.cash-in');
            Route::get('/create', [CashInController::class, 'create'])->name('dashboard.finance.cash-in.create')->middleware(['permission:keuangan-arus kas harian-tambah pemasukkan dana']);
            Route::post('/', [CashInController::class, 'store'])->name('dashboard.finance.cash-in.post')->middleware(['permission:keuangan-arus kas harian-tambah pemasukkan dana']);
            Route::get('/{id}/edit', [CashInController::class, 'edit'])->name('dashboard.finance.cash-in.edit')->middleware(['permission:keuangan-arus kas harian-perbarui dana']);
            Route::put('/{id}', [CashInController::class, 'update'])->name('dashboard.finance.cash-in.update')->middleware(['permission:keuangan-arus kas harian-perbarui dana']);
            Route::delete('/{id}', [CashInController::class, 'destroy'])->name('dashboard.finance.cash-in.delete')->middleware(['permission:keuangan-arus kas harian-hapus dana']);
        });
        Route::prefix("/cash-out")->group(function () {
            Route::get('/', [CashOutController::class, 'index'])->name('dashboard.finance.cash-out');
            Route::get('/create', [CashOutController::class, 'create'])->name('dashboard.finance.cash-out.create')->middleware(['permission:keuangan-arus kas harian-tambah pengeluaran dana']);
            Route::post('/', [CashOutController::class, 'store'])->name('dashboard.finance.cash-out.post')->middleware(['permission:keuangan-arus kas harian-tambah pengeluaran dana']);
            Route::get('/{id}/edit', [CashOutController::class, 'edit'])->name('dashboard.finance.cash-out.edit')->middleware(['permission:keuangan-arus kas harian-perbarui dana']);
            Route::put('/{id}', [CashOutController::class, 'update'])->name('dashboard.finance.cash-out.update')->middleware(['permission:keuangan-arus kas harian-perbarui dana']);
            Route::delete('/{id}', [CashOutController::class, 'destroy'])->name('dashboard.finance.cash-out.delete')->middleware(['permission:keuangan-arus kas harian-hapus dana']);
        });
        Route::get("/cash-flow-daily", [CashFlowController::class, 'list_daily'])->name('dashboard.finance.cash-flow-daily')->middleware(['permission:keuangan-arus kas harian-lihat']);
        Route::get("/cash-flow-monthly", [CashFlowController::class, 'list_monthly'])->name('dashboard.finance.cash-flow-monthly')->middleware(['permission:keuangan-arus kas bulanan-lihat']);
        Route::prefix("/funds")->group(function () {
            Route::get('/', [FundsController::class, 'funds_finance'])->name('dashboard.finance.funds')->middleware(['permission:keuangan-dana-lihat']);
            Route::get('/create', [FundsController::class, 'funds_finance_create'])->name('dashboard.finance.funds.create')->middleware(['permission:keuangan-dana-tambah pengalihan baru alokasi dana']);
            Route::post('/', [FundsController::class, 'funds_finance_post'])->name('dashboard.finance.funds.post')->middleware(['permission:keuangan-dana-tambah pengalihan baru alokasi dana']);
        });
        Route::prefix("/equite")->group(function () {
            Route::post("/add", [CashFlowController::class, 'add_equite'])->name('dashboard.finance.equite.post')->middleware(['permission:keuangan-arus kas bulanan-tambah modal bulanan']);
            Route::post("/closing", [CashFlowController::class, 'add_closing_cycle'])->name('dashboard.finance.equite.closing')->middleware(['permission:keuangan-arus kas bulanan-tutup buku bulanan']);
        });
    });

    // Order
    Route::prefix("/order")->group(function () {
        Route::get("/", [OrderController::class, 'order_active'])->name('dashboard.order.order_active')->middleware(['permission:order-order aktif-lihat']);
        Route::get("/new-order", [OrderController::class, 'add_new_order'])->name('dashboard.order.order_active.add_new_order')->middleware(['permission:order-order aktif-tambah order baru']);
        Route::post("/post_new_order", [OrderController::class, 'post_new_order'])->name('dashboard.order.order_active.post_new_order')->middleware(['permission:order-order aktif-tambah order baru']);
        Route::get("/{id}/detail", [OrderController::class, 'order_detail'])->name('dashboard.order.order_detail')->middleware(['permission:order-order aktif-lihat', 'permission:order-riwayat order-lihat']);
        Route::get("/update/{id}", [OrderController::class, 'edit_order'])->name('dashboard.order.edit_order')->middleware(['permission:order-order aktif-perbarui order']);
        Route::put("/update/{id}", [OrderController::class, 'update_order'])->name('dashboard.order.update_order')->middleware(['permission:order-order aktif-perbarui order']);
        Route::delete("/{id}", [OrderController::class, 'delete_order'])->name('dashboard.order.delete_order')->middleware(['permission:order-order aktif-hapus order']);
        Route::get("/history", [OrderController::class, 'order_history'])->name('dashboard.order.order_history')->middleware(['permission:order-riwayat order-lihat']);
        Route::get("/{id}/edit", [OrderController::class, 'order_history_edit'])->name('dashboard.order.order_history_edit');
        Route::put("/{id}", [OrderController::class, 'order_history_update'])->name('dashboard.order.order_history_update');
        Route::get("/report", [OrderController::class, 'report'])->name('dashboard.order.report')->middleware(['permission:order-pelaporan-lihat']);
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
            Route::get('/', [CompanyController::class, 'index_company'])->name('dashboard.company.profile')->middleware(['permission:toko-profil-lihat']);
            Route::put('/{id}', [CompanyController::class, 'update_company'])->name('dashboard.company.profile.update')->middleware(['permission:toko-profil-perbarui']);
            Route::get('/settings', [CompanyController::class, 'index_setting'])->name('dashboard.company.setting')->middleware(['permission:toko-pengaturan-lihat']);
            Route::put('/settings/{id}', [CompanyController::class, 'update_setting'])->name('dashboard.company.setting.update')->middleware(['permission:toko-pengaturan-perbarui']);
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
        // User
        Route::prefix('users')->group(function () {
            Route::get("/", [ManagementUserController::class, 'user_index'])->name('dashboard.management-user.user.index')->middleware(['permission:pengelolaan akun-akun pengguna-lihat']);
            Route::get("/create", [ManagementUserController::class, 'user_create'])->name('dashboard.management-user.user.create')->middleware(['permission:pengelolaan akun-akun pengguna-tambah']);
            Route::post("/", [ManagementUserController::class, 'user_store'])->name('dashboard.management-user.user.store')->middleware(['permission:pengelolaan akun-akun pengguna-tambah']);
            Route::get("/{id}/edit", [ManagementUserController::class, 'user_edit'])->name('dashboard.management-user.user.edit')->middleware(['permission:pengelolaan akun-akun pengguna-perbarui']);
            Route::put("/{id}", [ManagementUserController::class, 'user_update'])->name('dashboard.management-user.user.update')->middleware(['permission:pengelolaan akun-akun pengguna-perbarui']);
            Route::delete("/{id}", [ManagementUserController::class, 'user_destroy'])->name('dashboard.management-user.user.destroy')->middleware(['permission:pengelolaan akun-akun pengguna-hapus']);
        });

        // Role
        Route::prefix('roles')->group(function () {
            Route::get("/", [ManagementUserController::class, 'role_index'])->name('dashboard.management-user.role.index')->middleware(['permission:pengelolaan akun-hak akses-lihat']);
            Route::get("/create", [ManagementUserController::class, 'role_create'])->name('dashboard.management-user.role.create')->middleware(['permission:pengelolaan akun-hak akses-tambah']);
            Route::post("/", [ManagementUserController::class, 'role_store'])->name('dashboard.management-user.role.store')->middleware(['permission:pengelolaan akun-hak akses-tambah']);
            Route::get("/{id}/edit", [ManagementUserController::class, 'role_edit'])->name('dashboard.management-user.role.edit')->middleware(['permission:pengelolaan akun-hak akses-perbarui']);
            Route::put("/{id}", [ManagementUserController::class, 'role_update'])->name('dashboard.management-user.role.update')->middleware(['permission:pengelolaan akun-hak akses-perbarui']);
            Route::delete("/{id}", [ManagementUserController::class, 'role_destroy'])->name('dashboard.management-user.role.destroy')->middleware(['permission:pengelolaan akun-hak akses-hapus']);
        });
    });
});

Route::prefix('/management')->middleware([
    'auth',
    ManagementAuth::class
])->group(function() {
    Route::get('/', [ManagementDashboardController::class, 'index'])->name('management.dashboard');

    Route::prefix("/company")->group(function() {
        Route::get("/", [ManagementCompanyController::class, 'index'])->name('management.company.index');
        Route::get("/create", [ManagementCompanyController::class, 'create'])->name('management.company.create');
        Route::post("/store", [ManagementCompanyController::class, 'store'])->name('management.company.store');
    });
});
