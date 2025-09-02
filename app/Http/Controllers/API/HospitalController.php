<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;


class HospitalController extends Controller
{ public function index(Request $request)
    {
        $query = Hospital::with('user')->latest();

        if (!empty($request->search)) {
            $term = $request->search;
            $query->where('name', 'like', "%{$term}%")
                  ->orWhere('location', 'like', "%{$term}%")
                  ->orWhere('address', 'like', "%{$term}%");
        }

        $hospitals = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $hospitals
        ], 200);
    }

    // Show single hospital details
    public function show(Hospital $hospital)
    {
        $hospital->load('user'); // eager load user relationship

        return response()->json([
            'status' => 'success',
            'data' => $hospital
        ], 200);
    }
}   