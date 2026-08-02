<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'roll_no' => 'required',
            'class_models_id' => 'required|exists:class_models,id',
            'division_id' => 'required|exists:divisions,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $student = Student::where('roll_no', $request->roll_no)
            ->where('class_models_id', $request->class_models_id)
            ->where('division_id', $request->division_id)
            ->first();

        if (!$student) {
            return response()->json(['message' => 'Invalid student'], 404);
        }

        if (User::where('student_id', $student->id)->exists()) {
            return response()->json(['message' => 'Already registered'], 400);
        }

        User::create([
            'name' => trim($student->first_name . ' ' . $student->last_name),
            'email' => $request->email,
            'password' => $request->password, // hashed via User cast
            'student_id' => $student->id,
            'role' => 'student',
        ]);

        return response()->json(['message' => 'Registered successfully']);
    }

    public function studentRegister(Request $request)
    {
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $exists = User::where('student_id', $student->id)->first();
        if ($exists) {
            return response()->json(['message' => 'Already registered'], 400);
        }

        User::create([
            'name' => trim($student->first_name . ' ' . $student->last_name),
            'email' => $student->email,
            'password' => $request->password, // hashed via User cast
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        return response()->json(['message' => 'Password set successfully. You can login now']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message'=>'Logged out']);
    }
}
