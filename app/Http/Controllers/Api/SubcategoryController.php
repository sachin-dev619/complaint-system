<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
     // ✅ List
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $data = Subcategory::with('category')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total()
            ]
        ]);
    }

    // ✅ Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Subcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subcategory added successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $sub = \App\Models\Subcategory::find($id);

        if (!$sub) {
            return response()->json([
                'status' => false,
                'message' => 'Subcategory not found'
            ], 404);
        }

        $sub->delete();

        return response()->json([
            'status' => true,
            'message' => 'Subcategory deleted successfully'
        ], 200);
    }

    public function byCategory($category_id)
    {
        $data = Subcategory::where('category_id', $category_id)->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
