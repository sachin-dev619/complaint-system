<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // ✅ Show all students (Admin only)
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $perPage = $request->get('per_page', 10);

        $students = Student::with(['classModel', 'division'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Student list fetched successfully',
            'data' => $students->items(),
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total()
            ]
        ], 200);
    }

    // ✅ Add student (Admin only)
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // ✅ Validator with custom messages
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'roll_no' => 'required',
            'class_models_id' => 'required|exists:class_models,id',
            'division_id' => 'required|exists:divisions,id',
        ], [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Enter valid email',
            'email.unique' => 'Email already exists',
            'roll_no.required' => 'Roll number is required',
            'class_models_id.required' => 'Class is required',
            'division_id.required' => 'Division is required',
        ]);

        // ✅ Validation error response
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // ✅ Create student
        Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'roll_no' => $request->roll_no,
            'class_models_id' => $request->class_models_id,
            'division_id' => $request->division_id,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Student added successfully'
        ], 201);
    }

    // ✅ Student Profile
    public function profile()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $student = Student::find($user->student_id);

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $student->phone ?? null,
            ]
        ], 200);
    }
}