<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    // Show all restaurants
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        $perPage = $request->input('perPage', 10);

        $restaurants = $query->paginate($perPage);

        return view('restaurants.index', compact('restaurants'));
    }


    // Store new restaurant
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        $data = $request->only([
            'name',
            'description',
            'location',
            'address',
            'phone'
        ]);
        $data['user_id'] = auth()->id();

        // Handle multiple images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('restaurants', 'public');
            }
            $data['images'] = $images;
        }

        Restaurant::create($data);

        return redirect()->back()->with('success', 'Restaurant created successfully');
    }

    // Update restaurant
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:4048',
        ]);

        $data = $request->only([
            'name',
            'description',
            'location',
            'address',
            'phone'
        ]);

        // Replace old images if new ones uploaded
        if ($request->hasFile('images')) {
            if ($restaurant->images) {
                foreach ($restaurant->images as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
            $images = [];
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('restaurants', 'public');
            }
            $data['images'] = $images;
        }

        $restaurant->update($data);

        return redirect()->back()->with('success', 'Restaurant updated successfully');
    }

    // Delete restaurant
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        if ($restaurant->images) {
            foreach ($restaurant->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $restaurant->delete();

        return redirect()->back()->with('success', 'Restaurant deleted successfully');
    }

    // Show restaurant (JSON response, e.g. for API)
    public function show($id)
    {
        $restaurant = Restaurant::with('user')->findOrFail($id);
        return response()->json($restaurant);
    }
}
