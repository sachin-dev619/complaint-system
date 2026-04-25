<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SubcategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =======================
// 🔹 PUBLIC ROUTES
// =======================

Route::get('/test', function () {
    return response()->json([
        'status' => true,
        'message' => 'API is working'
    ]);
});
Route::get('/migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return "Migrated successfully";
});

Route::get('/check-db', function () {
    return [
        'host' => env('DB_HOST'),
        'db' => env('DB_DATABASE'),
        'user' => env('DB_USERNAME'),
    ];
});

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/student-register', [AuthController::class,'studentRegister']);


// =======================
// 🔐 PROTECTED ROUTES
// =======================
Route::middleware('auth:sanctum')->group(function () {

    // 🔓 Common (Admin + Student दोनों use कर सकते हैं)
    Route::post('/logout', [AuthController::class,'logout']);

    // ✅ IMPORTANT: Move outside admin
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/subcategories', [SubcategoryController::class, 'index']);

    // 👨‍🎓 Student notifications
    Route::get('/notifications', [NotificationController::class, 'index']);

    // 👨‍💼 Admin notifications
    Route::get('/admin/notifications', [NotificationController::class, 'adminNotifications']);

    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    

    // =======================
    // 🎓 STUDENT ROUTES
    // =======================
    Route::middleware('role:student')->group(function () {

        Route::post('/complaints', [ComplaintController::class,'store']);
        Route::get('/my-complaints', [ComplaintController::class,'myComplaints']);
        Route::get('/complaints/{id}', [ComplaintController::class,'show']);
        Route::put('/complaints/{id}', [ComplaintController::class,'update']);

        Route::get('/student-profile', [StudentController::class,'profile']);

        // ✅ Correct order
        Route::get('/subcategories/{category_id}', [SubcategoryController::class, 'byCategory']);
        Route::delete('/subcategories/{id}', [SubcategoryController::class, 'destroy']);
    });


    // =======================
    // 👨‍💼 ADMIN ROUTES
    // =======================
    Route::middleware('role:admin')->group(function () {

        // 📌 Complaint Management
        Route::get('/all-complaints', [AdminController::class,'allComplaints']);
        Route::post('/update-status/{id}', [AdminController::class,'updateStatus']);
        Route::get('/admin/complaints/{id}', [AdminController::class,'show']);

        // 📌 Student Management
        Route::post('/students', [StudentController::class, 'store']);
        Route::get('/students', [StudentController::class, 'index']);

        // 📌 Category Management (Admin only)
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // 📌 Subcategory Management (Admin only)
        Route::post('/subcategories', [SubcategoryController::class, 'store']);
        Route::delete('/subcategories/{id}', [SubcategoryController::class, 'destroy']);

        // 📊 Reports
        Route::get('/reports/daily', [ReportController::class, 'daily']);
        Route::get('/reports/monthly', [ReportController::class, 'monthly']);

        // 👤 Admin Profile
        Route::get('/profile', [AdminController::class,'profile']);
        Route::post('/profile-update', [AdminController::class,'updateProfile']);
        Route::post('/change-password', [AdminController::class,'changePassword']);
    });
    

});