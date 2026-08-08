<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Auth;

/*
|--------------------------------------------------------------------------
| 驗證路由
|--------------------------------------------------------------------------
|
| 端點：/auth
|
*/

// 定義這些路由是為了讓我們能繼續以程式化的方式參照它們。
// 它們全部都指向同一個控制器函式，並交由 React 接手處理。
Route::get('/login', [Auth\LoginController::class, 'index'])->name('auth.login');
Route::get('/password', [Auth\LoginController::class, 'index'])->name('auth.forgot-password');
Route::get('/password/reset/{token}', [Auth\LoginController::class, 'index'])->name('auth.reset');

// 對驗證相關的操作端點套用節流限制，加上 recaptcha 端點，
// 進一步拖慢手動攻擊的洗版者。🤷‍
//
// @see \Pterodactyl\Providers\RouteServiceProvider
Route::middleware(['throttle:authentication'])->group(function () {
    // 登入端點。
    Route::post('/login', [Auth\LoginController::class, 'login'])->middleware('recaptcha');
    Route::post('/login/checkpoint', Auth\LoginCheckpointController::class)->name('auth.login-checkpoint');

    // 忘記密碼路由。對此端點送出 POST 請求會觸發寄送
    // 一封包含重設權杖的電子郵件。
    Route::post('/password', [Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('auth.post.forgot-password')
        ->middleware('recaptcha');
});

// 密碼重設路由。使用者透過忘記密碼流程取得權杖後
//（或帳號建立後），會被導向此端點。
Route::post('/password/reset', Auth\ResetPasswordController::class)->name('auth.reset-password');

// 移除 guest 中介層並套用 authenticated 中介層到此端點，
// 這樣就必須先登入才能使用此端點。
Route::post('/logout', [Auth\LoginController::class, 'logout'])
    ->withoutMiddleware('guest')
    ->middleware('auth')
    ->name('auth.logout');

// 攔截其他任何組合的路由，並交由 React 元件接手處理。
Route::fallback([Auth\LoginController::class, 'index']);
