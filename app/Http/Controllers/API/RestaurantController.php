<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if (!empty($request->search)) {
            $term = $request->search;

            $query->where('name', 'like', "%{$term}%")
                ->orWhereHas('category', function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%");
                });
        }

        $restaurants = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $restaurants
        ], 200);
    }

    public function show($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Restaurant not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $restaurant
        ], 200);
    }
}
