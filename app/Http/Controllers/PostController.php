<?php

namespace App\Http\Controllers;

use App\Models\Post_;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post_::with(['customer', 'category', 'comments.customer', 'likes']);

        if ($search = $request->input('search')) {
            $query->where('content', 'like', "%{$search}%");
        }
        $perPage = $request->input('perPage', 10);
        $posts = $query->latest()->paginate($perPage);
        $customers = Customer::all();
        $categories = Category::all();
        $role = $this->getRole();
        $posts->getCollection()->transform(function ($post) {
            if (is_string($post->images)) {
                $post->images = json_decode($post->images) ?: [];
            }
            if ($post->customer && $post->customer->profile_image) {
                $post->customer->profile_image_url = asset('storage/' . $post->customer->profile_image);
            } else {
                $post->customer->profile_image_url = asset('images/profile_image.png');
            }

            return $post;
        });

        return view('posts.index', compact('posts', 'customers', 'categories', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'customer_id' => 'required|exists:customers,id',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|max:4048', 
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('posts', 'public');
            }
        }

        Post_::create([
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'customer_id' => $request->input('customer_id'),
            'category_id' => $request->input('category_id'),
            'images' => $imagePaths,
        ]);

        return redirect()->route($this->getRole() . '.posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post.
     */
    public function show($id)
    {
        $post = Post_::with('customer', 'category')->findOrFail($id);
        $role = $this->getRole();
        return view('posts.show', compact('post', 'role'));
    }

    /**
     * Show the form for editing the specified post.
     * (Not needed if you use modal edit on index page)
     */
    public function edit($id)
    {
        $post = Post_::findOrFail($id);
        $customers = Customer::all();
        $categories = Category::all();
        $role = $this->getRole();

        return view('posts.edit', compact('post', 'customers', 'categories', 'role'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post_ $post)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'status' => 'required|in:draft,published,archived',
                'customer_id' => 'required|exists:customers,id',
                'category_id' => 'required|exists:categories,id',
                'images.*' => 'nullable|image|max:10048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirect back with errors, old input and the ID to reopen modal
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('edit_post_id', $post->id);
        }

        // Fill the post with validated data
        $post->fill($validated);

        // Handle images if any uploaded
        if ($request->hasFile('images')) {
            // Delete old images if exist
            if ($post->images && is_array($post->images)) {
                foreach ($post->images as $img) {
                    Storage::disk('public')->delete($img);
                }
            }

            // Store new images
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('posts', 'public');
            }
            $post->images = $images;
        }

        $post->save();

        // Redirect back to index with success message
        return redirect()->route($this->getRole() . '.posts.index')->with('success', 'Post updated successfully!');
    }


    /**
     * Remove the specified post from storage.
     */
    public function destroy($id)
    {
        $post = Post_::findOrFail($id);

        // Delete images from storage
        if ($post->images && is_array($post->images)) {
            foreach ($post->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $post->delete();

        return redirect()->route($this->getRole() . '.posts.index')->with('success', 'Post deleted successfully!');
    }

    /**
     * Helper to detect role from the URL segment for routing.
     */
    private function getRole()
    {
        $segment = request()->segment(1);
        return in_array($segment, ['superadmin', 'admin', 'writer']) ? $segment : 'admin';
    }
}
