<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationCreated;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'priority' => 'required|in:Low,Medium,High',
            'complaint_text' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'complaint_no' => $this->generateComplaintNo(),
            'user_id' => auth()->id(),
            'title' => $request->title,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'priority' => $request->priority,
            'complaint_text' => $request->complaint_text,
            'file' => $filePath,
            'status' => 'Pending',
        ]);

        $adminNotification = NotificationService::create(
            null,
            'New Complaint Received',
            'New complaint submitted by student',
            'admin'
        );

        event(new NotificationCreated($adminNotification));

        $studentNotification = NotificationService::create(
            $complaint->user_id,
            'Complaint Submitted',
            'Your complaint has been submitted successfully',
            'student'
        );

        event(new NotificationCreated($studentNotification));

        return response()->json([
            'message' => 'Complaint submitted successfully',
            'data' => $complaint->load(['category', 'subcategory'])
        ], 201);
    }

    public function myComplaints()
    {
        $complaints = Complaint::with(['category', 'subcategory'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($complaints);
    }

    public function show($id)
    {
        $complaint = Complaint::with(['category', 'subcategory'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json($complaint);
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($complaint->status !== 'Pending') {
            return response()->json([
                'message' => 'Only pending complaints can be edited'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'priority' => 'required|in:Low,Medium,High',
            'complaint_text' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        if ($request->hasFile('file')) {
            if ($complaint->file && file_exists(storage_path('app/public/' . $complaint->file))) {
                unlink(storage_path('app/public/' . $complaint->file));
            }

            $filePath = $request->file('file')->store('complaints', 'public');
        } else {
            $filePath = $complaint->file;
        }

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
            'data' => $complaint->fresh(['category', 'subcategory'])
        ]);
    }

    private function generateComplaintNo(): string
    {
        do {
            $complaintNo = 'CMP-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Complaint::where('complaint_no', $complaintNo)->exists());

        return $complaintNo;
    }
}
