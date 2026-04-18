<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationCreated;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'complaint_text' => 'required',
        ]);

        $complaint = Complaint::create([
            'complaint_no' => 'CMP-' . date('Y') . '-' . rand(1000,9999),
            'user_id' => auth()->id(),
            'title' => $request->title,
            'category_id' => $request->category_id,
            'complaint_text' => $request->complaint_text,
            'status' => 'Pending',
        ]);

        // ✅ ADMIN NOTIFICATION (NO NULL ISSUE)
        $adminNotification = NotificationService::create(
            null,
            'New Complaint Received',
            'New complaint submitted by student',
            'admin'
        );

        event(new NotificationCreated($adminNotification));

        // ✅ STUDENT NOTIFICATION
        $studentNotification = NotificationService::create(
            $complaint->user_id,
            'Complaint Submitted',
            'Your complaint has been submitted successfully',
            'student'
        );

        event(new NotificationCreated($studentNotification));

        return response()->json([
            'message' => 'Complaint submitted successfully',
            'data' => $complaint
        ]);
    }

    // ✅ Student Complaints (with category)
    public function myComplaints()
    {
        $complaints = Complaint::with('category') // 🔥 important
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($complaints);
    }

    public function show($id)
    {
        $complaint = \App\Models\Complaint::where('id', $id)
            ->where('user_id', auth()->id()) // 🔐 IMPORTANT
            ->firstOrFail();

        return response()->json($complaint);
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // ✅ VALIDATION
        $request->validate([
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required',
            'priority' => 'required',
            'complaint_text' => 'required',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        // ✅ FILE UPLOAD
        if ($request->hasFile('file')) {

            // delete old file (optional)
            if ($complaint->file && file_exists(storage_path('app/public/' . $complaint->file))) {
                unlink(storage_path('app/public/' . $complaint->file));
            }

            $filePath = $request->file('file')->store('complaints', 'public');
        } else {
            $filePath = $complaint->file;
        }

        // ✅ UPDATE ALL FIELDS
        $complaint->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'priority' => $request->priority,
            'complaint_text' => $request->complaint_text,
            'file' => $filePath
        ]);

        return response()->json([
            'message' => 'Complaint updated successfully',
            'data' => $complaint
        ]);
    }
}
