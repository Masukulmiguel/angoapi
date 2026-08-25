<?php

use App\Http\Controllers\Api\V1\Admin\AdminApiKeyController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminPaymentController;
use App\Http\Controllers\Api\V1\Admin\AdminSettingController;
use App\Http\Controllers\Api\V1\Admin\AdminSubscriptionController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\ApiKeyController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\Gateway\EmailController;
use App\Http\Controllers\Api\V1\Gateway\IdentityController;
use App\Http\Controllers\Api\V1\Gateway\LocationController;
use App\Http\Controllers\Api\V1\Gateway\NifController;
use App\Http\Controllers\Api\V1\Gateway\OtpController;
use App\Http\Controllers\Api\V1\Gateway\PhoneController;
use App\Http\Controllers\Api\V1\Gateway\QrController;
use App\Http\Controllers\Api\V1\Gateway\SmsController;
use App\Http\Controllers\Api\V1\BitPayWebhookController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\ProxyPayController;
use App\Http\Controllers\Api\V1\SandboxController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\VerifyApiKey;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('/google/callback', [AuthController::class, 'googleCallback']);

// Sandbox Routes (no auth, no CSRF)
Route::prefix('v1/sandbox')->withoutMiddleware([\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class])->group(function () {
    Route::post('/validate/bi', [SandboxController::class, 'validateBi']);
    Route::post('/validate/nif', [SandboxController::class, 'validateNif']);
    Route::post('/validate/phone', [SandboxController::class, 'validatePhone']);
    Route::post('/otp/generate', [SandboxController::class, 'generateOtp']);
    Route::post('/otp/verify', [SandboxController::class, 'verifyOtp']);
    Route::post('/sms/send', [SandboxController::class, 'sendSms']);
    Route::post('/email/send', [SandboxController::class, 'sendEmail']);
    Route::post('/qrcode/generate', [SandboxController::class, 'generateQr']);
});

// Plans (public read)
Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{plan}', [PlanController::class, 'show']);

// BitPay webhook callback (no auth, no CSRF, no middleware)
Route::post('/payments/callback', [PaymentController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('payment.callback');

// Authenticated User Routes
Route::middleware(['auth:sanctum', EnsureUserIsActive::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password', [AuthController::class, 'changePassword']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/subscriptions/current', [SubscriptionController::class, 'current']);
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show']);

    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);

    // Multicaixa Express Payments (BitPay)
    Route::post('/payments/multicaixa-express', [PaymentController::class, 'createPayment']);
    Route::get('/payments/status/{paymentId}', [PaymentController::class, 'status']);

    Route::get('/api-keys', [ApiKeyController::class, 'index']);
    Route::post('/api-keys', [ApiKeyController::class, 'store']);
    Route::post('/api-keys/{key}/revoke', [ApiKeyController::class, 'revoke']);
    Route::post('/api-keys/{key}/regenerate', [ApiKeyController::class, 'regenerate']);
    Route::post('/api-keys/{key}/toggle', [ApiKeyController::class, 'toggle']);

    Route::post('/chat', [ChatController::class, 'send']);
});

// API Gateway Routes (authenticated via API Key)
Route::prefix('gateway')->group(function () {
    Route::middleware([VerifyApiKey::class])->group(function () {
        Route::post('/nif/validate', [NifController::class, 'validate']);
        Route::post('/identity/verify', [IdentityController::class, 'verify']);

        Route::get('/location/provinces', [LocationController::class, 'province']);
        Route::get('/location/municipalities', [LocationController::class, 'municipality']);

        Route::post('/phone/validate', [PhoneController::class, 'validate']);

        Route::post('/otp/generate', [OtpController::class, 'generate']);
        Route::post('/otp/verify', [OtpController::class, 'verify']);

        Route::post('/sms/send', [SmsController::class, 'send']);
        Route::post('/email/send', [EmailController::class, 'send']);

        Route::post('/qr/generate', [QrController::class, 'generate']);
    });
});

// Admin Routes
Route::middleware(['auth:sanctum', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    Route::get('/payments', [AdminPaymentController::class, 'index']);
    Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve']);
    Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject']);
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show']);

    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{user}', [AdminUserController::class, 'show']);
    Route::put('/users/{user}/status', [AdminUserController::class, 'updateStatus']);

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index']);
    Route::get('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'show']);

    Route::get('/api-keys', [AdminApiKeyController::class, 'index']);
    Route::post('/api-keys/{key}/revoke', [AdminApiKeyController::class, 'revoke']);

    Route::get('/settings', [AdminSettingController::class, 'index']);
    Route::put('/settings', [AdminSettingController::class, 'update']);
});
