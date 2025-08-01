<?php

namespace App\Http\Controllers;

use App\Models\Post_;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $role = $this->getRole();
        $posts = Post_::with('category', 'customer')->latest()->get();
        return view('posts.index', compact('posts', 'role'));
    }

    public function create()
    {
        $role = $this->getRole();
        $categories = Category::all();
        $customers = Customer::all();
        return view('posts.create', compact('categories', 'customers', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|unique:post_s,slug',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $slug = $request->slug ?: Str::slug($request->title);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
        }

        $post = Post_::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'poster_head' => $request->poster_head,
            'poster_sizes' => json_encode(explode(',', $request->poster_sizes)),
            'status' => $request->status,
            'image' => $imagePath,
            'customer_id' => $request->customer_id,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route($this->getRole() . '.posts.index')->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $role = $this->getRole();
        $post_ = Post_::with('category', 'customer')->findOrFail($id);
        return view('posts.show', compact('post_', 'role'));
    }

    public function edit($id)
    {
        $role = $this->getRole();
        $post_ = Post_::findOrFail($id);
        $categories = Category::all();
        $customers = Customer::all();
        return view('posts.edit', compact('post_', 'categories', 'customers', 'role'));
    }

    public function update(Request $request, $id)
    {
        $post = Post_::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|unique:post_s,slug,' . $post->id,
            'content' => 'required',
            'image' => 'nullable|image|max:102400',
        ]);

        $slug = $request->slug ?: Str::slug($request->title);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
        } else {
            $imagePath = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'poster_head' => $request->poster_head,
            'poster_sizes' => json_encode(explode(',', $request->poster_sizes)),
            'status' => $request->status,
            'image' => $imagePath,
            'customer_id' => $request->customer_id,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route($this->getRole() . '.posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy($id)
    {
        $post = Post_::findOrFail($id);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route($this->getRole() . '.posts.index')->with('success', 'Post deleted successfully!');
    }

    // Helper to detect role from URL
    private function getRole()
    {
        $segment = request()->segment(1);
        return in_array($segment, ['superadmin', 'admin', 'writer']) ? $segment : 'admin';
    }
}

