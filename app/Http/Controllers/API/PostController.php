<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post_;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store', 'like', 'share', 'comment');
    }

    public function index()
    {
        $posts = Post_::with(['customer', 'comments.customer', 'likes'])->latest()->get();

        $posts->transform(function ($post) {
            if (is_string($post->images)) {
                $post->images = json_decode($post->images) ?: [];
            }

            $post->customer->profile_image_url = $post->customer && $post->customer->profile_image
                ? asset('storage/' . $post->customer->profile_image)
                : asset('images/profile_image.png');

            $post->likes_count = $post->likes()->count();
            $post->liked_by_user = auth()->check()
                ? $post->likes()->where('customer_id', auth()->id())->exists()
                : false;

            return $post;
        });

        return response()->json($posts);
    }


    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:9048',
        ]);

        $data = $request->only('content', 'category_id');
        $data['customer_id'] = $request->user()->id;

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('posts', 'public');
            }
            $data['images'] = json_encode($paths);
        }

        $post = Post_::create($data);
        return response()->json($post, 201);
    }
    public function show($id)
    {
        $post = Post_::with(['customer', 'comments.customer', 'likes'])->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Decode images if stored as JSON
        if (is_string($post->images)) {
            $post->images = json_decode($post->images) ?: [];
        }

        // Set customer profile image URL
        if ($post->customer && $post->customer->profile_image) {
            $post->customer->profile_image_url = asset('storage/' . $post->customer->profile_image);
        } else {
            $post->customer->profile_image_url = asset('images/profile_image.png');
        }

        return response()->json($post);
    }
}
