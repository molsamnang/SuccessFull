<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post_;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // Toggle like/unlike for a post
    public function toggleLike(Request $request, Post_ $post)
    {
        $customerId = $request->user()->id;

        $like = $post->likes()->where('customer_id', $customerId)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $post->likes()->create(['customer_id' => $customerId]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    // Get all posts with likes_count and liked_by_user
    public function getPosts(Request $request)
    {
        $customerId = $request->user()->id ?? null;

        $posts = Post_::withCount('likes')
            ->with(['likes' => function ($q) use ($customerId) {
                if ($customerId) {
                    $q->where('customer_id', $customerId);
                }
            }])
            ->get();

        // Add liked_by_user attribute
        $posts->map(function ($post) {
            $post->liked_by_user = $post->likes->isNotEmpty();
            unset($post->likes); // remove detailed likes
            return $post;
        });

        return response()->json($posts);
    }
}
