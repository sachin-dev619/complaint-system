<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\NotificationService;
use App\Events\NotificationCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // =========================
    // 📋 GET ALL COMPLAINTS
    // =========================
    public function allComplaints(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $query = Complaint::with(['user', 'category', 'subcategory'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('complaint_text', 'like', "%{$search}%")
                    ->orWhere('complaint_no', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $complaints = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $complaints->items(),
            'pagination' => [
                'current_page' => $complaints->currentPage(),
                'last_page' => $complaints->lastPage(),
                'per_page' => $complaints->perPage(),
                'total' => $complaints->total()
            ]
        ], 200);
    }

    // =========================
    // 📊 DASHBOARD STATS
    // =========================
    public function dashboardStats()
    {
        $statusCounts = [
            'Pending' => Complaint::where('status', 'Pending')->count(),
            'In Progress' => Complaint::where('status', 'In Progress')->count(),
            'Resolved' => Complaint::where('status', 'Resolved')->count(),
        ];

        $monthly = Complaint::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyCounts[] = (int) ($monthly[$i] ?? 0);
        }

        $recent = Complaint::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'total' => array_sum($statusCounts),
                'by_status' => $statusCounts,
                'monthly' => $monthlyCounts,
                'recent' => $recent,
            ]
        ]);
    }

    // =========================
    // 📄 GET SINGLE COMPLAINT
    // =========================
    public function show($id)
    {
        $complaint = Complaint::with(['user', 'category', 'subcategory'])->find($id);

        if (!$complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $complaint
        ], 200);
    }

    // =========================
    // 🔄 UPDATE STATUS + REMARK + NOTIFICATION
    // =========================
    public function updateStatus(Request $request, $id)
    {
        $complaint = Complaint::with('user')->find($id);

        if (!$complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Resolved',
            'admin_remark' => 'nullable|string'
        ]);

        // =========================
        // UPDATE COMPLAINT
        // =========================
        $complaint->status = $request->status;

        $complaint->admin_remark = $request->filled('admin_remark')
            ? $request->admin_remark
            : null;

        if ($request->status === 'Resolved') {
            $complaint->resolved_at = now();
        } else {
            $complaint->resolved_at = null;
        }

        $complaint->save();

        // =========================
        // 👨‍🎓 NOTIFY STUDENT
        // =========================
        $studentNotification = NotificationService::create(
            $complaint->user_id,
            'Complaint Status Updated',
            'Your complaint is now ' . $complaint->status,
            'student'
        );

        event(new NotificationCreated($studentNotification));

        // =========================
        // 👨‍💼 NOTIFY ADMIN (optional)
        // =========================
        $adminNotification = NotificationService::create(
            null,
            'Complaint Updated',
            'Complaint #' . $complaint->id . ' updated to ' . $complaint->status,
            'admin'
        );

        event(new NotificationCreated($adminNotification));

        return response()->json([
            'status' => true,
            'message' => 'Complaint updated successfully',
            'data' => $complaint
        ], 200);
    }

    // =========================
    // 👤 GET PROFILE
    // =========================
    public function profile()
    {
        return response()->json([
            'status' => true,
            'data' => auth()->user()
        ]);
    }

    // =========================
    // ✏️ UPDATE PROFILE
    // =========================
    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->id());

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    // =========================
    // 🔒 CHANGE PASSWORD
    // =========================
    public function changePassword(Request $request)
    {
        $user = User::find(auth()->id());

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Old password is incorrect'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}