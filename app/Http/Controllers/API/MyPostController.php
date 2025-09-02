<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post_;
use Illuminate\Support\Facades\Auth;

class MyPostController extends Controller
{
    // Ensure all routes require authentication
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Get all posts of the authenticated user
    public function index()
    {
        $userId = auth()->id();

        $posts = Post_::with('customer')
            ->where('customer_id', $userId)
            ->latest()
            ->get();

        // Transform posts
        $posts->transform(function ($post) {
            // Decode JSON images
            $post->images = is_string($post->images) ? json_decode($post->images) ?? [] : $post->images;

            // Full URL for profile image
            $post->customer->profile_image_url = $post->customer->profile_image
                ? asset('storage/' . $post->customer->profile_image)
                : asset('images/profile_image.png');

            return $post;
        });

        return response()->json($posts);
    }

    // Update post content
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = Post_::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $post->content = $request->input('content');
        $post->save();

        return response()->json($post);
    }

    // Delete a post
    public function destroy($id)
    {
        $post = Post_::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
