<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RedemptionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Api\ArduinoController;
use App\Http\Controllers\ArduinoConnectionController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // Rewards
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/{reward}', [RewardController::class, 'show'])->name('rewards.show');
    
    // Admin-only reward management
    Route::middleware(['admin'])->group(function () {
        Route::get('/rewards/create', [RewardController::class, 'create'])->name('rewards.create');
        Route::post('/rewards', [RewardController::class, 'store'])->name('rewards.store');
        Route::get('/rewards/{reward}/edit', [RewardController::class, 'edit'])->name('rewards.edit');
        Route::put('/rewards/{reward}', [RewardController::class, 'update'])->name('rewards.update');
        Route::delete('/rewards/{reward}', [RewardController::class, 'destroy'])->name('rewards.destroy');
        // Admin settings
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
        
        // Admin detailed views
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    });
    
    
    

    // User Settings
    Route::get('/user/settings', [UserSettingsController::class, 'index'])->name('user.settings');

    // Redemptions
    Route::get('/redemptions', [RedemptionController::class, 'index'])->name('redemptions.index');
    Route::get('/redemptions/create', [RedemptionController::class, 'create'])->name('redemptions.create');
    Route::post('/redemptions', [RedemptionController::class, 'store'])->name('redemptions.store');
    Route::get('/redemptions/{redemption}', [RedemptionController::class, 'show'])->name('redemptions.show');
    Route::get('/redemptions/{redemption}/receipt', [RedemptionController::class, 'receipt'])->name('redemptions.receipt');
    Route::get('/redemptions/{redemption}/download-receipt', [RedemptionController::class, 'downloadReceipt'])->name('redemptions.download-receipt');

    // Arduino Connection
    Route::get('/arduino/connection', [ArduinoConnectionController::class, 'index'])->name('arduino.connection');
    Route::post('/arduino/connect', [ArduinoConnectionController::class, 'connect'])->name('arduino.connect');
    Route::post('/arduino/disconnect', [ArduinoConnectionController::class, 'disconnect'])->name('arduino.disconnect');
});

// API Routes for Arduino/ESP8266
Route::prefix('api/arduino')->group(function () {
    Route::post('/authenticate', [ArduinoController::class, 'authenticate']);
    Route::post('/detect-bottle', [ArduinoController::class, 'detectBottle']);
    Route::post('/session-status', [ArduinoController::class, 'getSessionStatus']);
    Route::post('/logout', [ArduinoController::class, 'logout']);
});

