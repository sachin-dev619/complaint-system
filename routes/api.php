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

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/student-register', [AuthController::class,'studentRegister']);


// =======================
// 🔐 PROTECTED ROUTES
// =======================
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class,'logout']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/subcategories', [SubcategoryController::class, 'index']);

    Route::get('/notifications', [NotificationController::class, 'index']);
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
        Route::get('/subcategories/{category_id}', [SubcategoryController::class, 'byCategory']);
    });


    // =======================
    // 👨‍💼 ADMIN ROUTES
    // =======================
    Route::middleware('role:admin')->group(function () {

        Route::get('/all-complaints', [AdminController::class,'allComplaints']);
        Route::get('/admin/dashboard-stats', [AdminController::class,'dashboardStats']);
        Route::post('/update-status/{id}', [AdminController::class,'updateStatus']);
        Route::get('/admin/complaints/{id}', [AdminController::class,'show']);

        Route::post('/students', [StudentController::class, 'store']);
        Route::get('/students', [StudentController::class, 'index']);
        Route::get('/students/import-template', [StudentController::class, 'downloadTemplate']);
        Route::post('/students/import', [StudentController::class, 'import']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::post('/subcategories', [SubcategoryController::class, 'store']);
        Route::delete('/subcategories/{id}', [SubcategoryController::class, 'destroy']);

        Route::get('/reports/daily', [ReportController::class, 'daily']);
        Route::get('/reports/monthly', [ReportController::class, 'monthly']);

        Route::get('/profile', [AdminController::class,'profile']);
        Route::post('/profile-update', [AdminController::class,'updateProfile']);
        Route::post('/change-password', [AdminController::class,'changePassword']);
    });
});
