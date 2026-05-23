<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Admin\InformationController as AdminInformationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriMenuController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuSpecialController;
use App\Http\Controllers\MenuSpecialItemController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\MejaController;
use App\Http\Controllers\Owner\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\StokLogController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\LaporanController;

/*
|--------------------------------------------------------------------------
| DUAL LANGUAGES ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch');


/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('frontend.home');

Route::get('/menu', [MenuController::class, 'frontend'])
    ->name('frontend.menu');

Route::get('/testimoni', [TestimoniController::class, 'frontendIndex'])
    ->name('frontend.testimoni.index');

Route::get('/about', function () {
    return redirect()->route('frontend.information.show', 'about');
})->name('frontend.about');


/*
|--------------------------------------------------------------------------
| MIDTRANS WEBHOOK ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook');

Route::get('/midtrans/finish', [MidtransWebhookController::class, 'finish'])
    ->name('midtrans.finish');

Route::get('/midtrans/unfinish', [MidtransWebhookController::class, 'unfinish'])
    ->name('midtrans.unfinish');

Route::get('/midtrans/error', [MidtransWebhookController::class, 'error'])
    ->name('midtrans.error');

Route::post('/midtrans/test', function (\Illuminate\Http\Request $request) {

    \Illuminate\Support\Facades\Log::info('Midtrans Test Endpoint Hit', [
        'method' => $request->method(),
        'ip' => $request->ip(),
        'headers' => $request->headers->all(),
        'payload' => $request->all(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Midtrans webhook endpoint is accessible and responding',
        'timestamp' => now(),
        'test_mode' => true,
    ], 200);

})->name('midtrans.test');


/*
|--------------------------------------------------------------------------
| INFORMATION / STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/information/{information}', [InformationController::class, 'show'])
    ->name('frontend.information.show');

Route::get('/faq', function () {
    return redirect()->route('frontend.information.show', 'faq');
})->name('frontend.faq');

Route::get('/privacy-policy', function () {
    return redirect()->route('frontend.information.show', 'privacy-policy');
})->name('frontend.privacy');

Route::get('/terms-conditions', function () {
    return redirect()->route('frontend.information.show', 'terms-conditions');
})->name('frontend.terms');

Route::get('/support', function () {
    return view('frontend.information.support');
})->name('frontend.support');


/*
|--------------------------------------------------------------------------
| RESERVASI
|--------------------------------------------------------------------------
*/

Route::get('/reservasi', [ReservasiController::class, 'frontend'])
    ->name('frontend.reservasi');

Route::post('/reservasi', [ReservasiController::class, 'store'])
    ->name('reservasi.store');

Route::get('/reservasi/tables', [ReservasiController::class, 'getAvailableTables'])
    ->name('reservasi.tables');


/*
|--------------------------------------------------------------------------
| CART / KERANJANG
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/add/{id}', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/add-special/{id}', [CartController::class, 'addSpecial'])
    ->name('cart.add-special');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])
    ->name('cart.remove');

Route::post('/cart/increment/{id}', [CartController::class, 'increment'])
    ->name('cart.increment');

Route::post('/cart/decrement/{id}', [CartController::class, 'decrement'])
    ->name('cart.decrement');

Route::post('/cart/update/{id}', [CartController::class, 'update'])
    ->name('cart.update');

Route::post('/cart/clear', [CartController::class, 'clear'])
    ->name('cart.clear');

Route::get('/cart/count', [CartController::class, 'count'])
    ->name('cart.count');


/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/checkout/success/{kodeOrder}', [CheckoutController::class, 'success'])
    ->name('checkout.success');


/*
|--------------------------------------------------------------------------
| SNAP PAYMENT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/payment/snap/{kodeOrder}', [\App\Http\Controllers\PaymentController::class, 'showSnap'])
    ->name('payment.snap');

Route::get('/payment/snap/{kodeOrder}/status', [\App\Http\Controllers\PaymentController::class, 'checkStatus'])
    ->name('payment.snap.status');

Route::get('/payment/success/{kodeOrder}', [\App\Http\Controllers\PaymentController::class, 'success'])
    ->name('payment.success');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login-redirect', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [AuthController::class, 'showProfile'])
        ->name('profile');

    Route::put('/profile', [AuthController::class, 'updateProfile'])
        ->name('profile.update');

    Route::put('/profile/password', [AuthController::class, 'updatePassword'])
        ->name('profile.password');
});


/*
|--------------------------------------------------------------------------
| OWNER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/dashboard', [AnalyticsController::class, 'index'])
            ->name('dashboard');
    });


/*
|--------------------------------------------------------------------------
| ADMIN / MANAGER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | ORDER MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/stats', [OrderController::class, 'stats'])
            ->name('orders.stats');

        Route::get('/orders/poll/data', [OrderController::class, 'pollData'])
            ->name('orders.poll');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])
            ->name('orders.updatePaymentStatus');

        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])
            ->name('orders.destroy');


        /*
        |--------------------------------------------------------------------------
        | GALERI CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource('galeri', GaleriController::class);

        /*
        |--------------------------------------------------------------------------
        | VIDEO CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource('video', VideoController::class);

        /*
        |--------------------------------------------------------------------------
        | INFORMATION CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource('information', AdminInformationController::class);


        /*
        |--------------------------------------------------------------------------
        | RESERVASI CRUD
        |--------------------------------------------------------------------------
        */

        Route::get('/reservasi', [ReservasiController::class, 'index'])
            ->name('reservasi.index');

        Route::get('/reservasi/export', [ReservasiController::class, 'export'])
            ->name('reservasi.export');

        Route::patch('/reservasi/{id}/status', [ReservasiController::class, 'updateStatus'])
            ->name('reservasi.updateStatus');

        Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])
            ->name('reservasi.destroy');

        Route::resource('meja', MejaController::class)
            ->only(['index', 'store', 'destroy']);


        /*
        |--------------------------------------------------------------------------
        | MANAGER ONLY
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:manager')->group(function () {

            Route::resource('menu-specials', MenuSpecialController::class)
                ->except(['show']);

            Route::post('/menu-specials/{menu_special}/items', [MenuSpecialItemController::class, 'store'])
                ->name('menu-specials.items.store');

            Route::patch('/menu-specials/{menu_special}/items/{menu_special_item}', [MenuSpecialItemController::class, 'update'])
                ->name('menu-specials.items.update');

            Route::delete('/menu-specials/{menu_special}/items/{menu_special_item}', [MenuSpecialItemController::class, 'destroy'])
                ->name('menu-specials.items.destroy');

            /*
            |--------------------------------------------------------------------------
            | MENU CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource('menu', MenuController::class)
                ->except(['show']);

            Route::get('/menu/{menu}', [MenuController::class, 'show'])
                ->name('menu.show');

            /*
            |--------------------------------------------------------------------------
            | STOK BAHAN CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource('stok', StokController::class)
                ->except(['show']);

            Route::get('stok-log', [StokLogController::class, 'index'])
                ->name('stok-log.index');

            /*
            |--------------------------------------------------------------------------
            | LAPORAN — HALAMAN & EXPORT
            |--------------------------------------------------------------------------
            */

            // Halaman laporan
            Route::get('/laporan/stok',      [LaporanController::class, 'stok'])      ->name('laporan.stok');
            Route::get('/laporan/pesanan',   [LaporanController::class, 'pesanan'])   ->name('laporan.pesanan');
            Route::get('/laporan/reservasi', [LaporanController::class, 'reservasi']) ->name('laporan.reservasi');

            // Export
            Route::get('/laporan/stok/csv',       [LaporanController::class, 'exportStokCsv'])       ->name('laporan.stok.csv');
            Route::get('/laporan/stok/xlsx',      [LaporanController::class, 'exportStokXlsx'])      ->name('laporan.stok.xlsx');
            Route::get('/laporan/reservasi/csv',  [LaporanController::class, 'exportReservasiCsv'])  ->name('laporan.reservasi.csv');
            Route::get('/laporan/reservasi/xlsx', [LaporanController::class, 'exportReservasiXlsx']) ->name('laporan.reservasi.xlsx');
            Route::get('/laporan/orders/xlsx',    [LaporanController::class, 'exportOrdersXlsx'])    ->name('laporan.orders.xlsx');
            Route::get('/laporan/orders/csv',     [LaporanController::class, 'exportOrdersCsv'])     ->name('laporan.orders.csv');

            /*
            |--------------------------------------------------------------------------
            | KATEGORI CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource('kategori', KategoriMenuController::class);

            /*
            |--------------------------------------------------------------------------
            | TESTIMONI CRUD
            |--------------------------------------------------------------------------
            */

            Route::post('/testimoni/sync', [TestimoniController::class, 'syncGoogleMaps'])
                ->name('testimoni.sync');

            Route::resource('testimoni', TestimoniController::class)
                ->except(['show']);

            /*
            |--------------------------------------------------------------------------
            | USER CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource('user', UserController::class);
        });


        /*
        |--------------------------------------------------------------------------
        | AI PREDIKSI PENJUALAN
        |--------------------------------------------------------------------------
        */

        Route::get('/prediksi', [\App\Http\Controllers\Admin\PredictionController::class, 'index'])
            ->name('prediksi.index');

        Route::post('/prediksi/run', [\App\Http\Controllers\Admin\PredictionController::class, 'runPrediction'])
            ->name('prediksi.run');

        Route::get('/prediksi/ai-status', [\App\Http\Controllers\Admin\PredictionController::class, 'checkAiStatus'])
            ->name('prediksi.ai-status');


        /*
        |--------------------------------------------------------------------------
        | GOOGLE REVIEWS
        |--------------------------------------------------------------------------
        */

        Route::prefix('google-reviews')
            ->name('google-reviews.')
            ->group(function () {

                Route::get('/', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'index'])
                    ->name('index');

                Route::post('/scrape-all', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'syncScrapeAll'])
                    ->name('scrape-all');

                Route::post('/update-data', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'syncUpdateData'])
                    ->name('update-data');

                Route::post('/sync-async', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'syncAsync'])
                    ->name('sync-async');

                Route::get('/check-status', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'checkStatus'])
                    ->name('check-status');

                Route::get('/api/reviews', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'apiReviews'])
                    ->name('api.reviews');

                Route::get('/api/stats', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'apiStats'])
                    ->name('api.stats');

                Route::get('/api/recent', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'apiRecentReviews'])
                    ->name('api.recent');

                Route::get('/api/sync-status', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'apiSyncStatus'])
                    ->name('api.sync-status');

                Route::delete('/{id}', [\App\Http\Controllers\Admin\GoogleReviewController::class, 'destroy'])
                    ->name('destroy');
            });
    });


/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [HomeController::class, 'index'])
            ->name('dashboard');
    });