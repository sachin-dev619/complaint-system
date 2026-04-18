<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ✅ Daily Report
    public function daily(Request $request)
    {
        $date = $request->date;
        $perPage = $request->get('per_page', 10);

        $data = Complaint::with(['user', 'category', 'subcategory'])
            ->whereDate('created_at', $date)
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

public function monthly(Request $request)
{
    $month = $request->month;
    $perPage = $request->get('per_page', 10);

    $data = Complaint::with(['user', 'category', 'subcategory'])
        ->whereYear('created_at', substr($month, 0, 4))
        ->whereMonth('created_at', substr($month, 5, 2))
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
}