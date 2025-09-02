<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with('user')->latest();

        // Search by name or location
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        // Per-page
        $perPage = $request->input('perPage', 10);
        $hospitals = $query->paginate($perPage);

        return view('hospitals.index', compact('hospitals'));
    }


    public function create()
    {
        return view('hospitals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:8048',
        ]);

        // handle multiple image uploads
        if ($request->hasFile('images')) {
            $data['images'] = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('hospitals', 'public');
                $data['images'][] = $path;
            }
        }

        $data['user_id'] = auth()->id();
        Hospital::create($data);

        return redirect()->route('hospitals.index')->with('success', 'Hospital created successfully.');
    }

    public function show(Hospital $hospital)
    {
        return view('hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $data['images'] = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('hospitals', 'public');
                $data['images'][] = $path;
            }
        }

        $hospital->update($data);

        return redirect()->route('hospitals.index')->with('success', 'Hospital created successfully.');
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return redirect()->route('hospitals.index')->with('success', 'Hospital deleted successfully.');
    }
}
