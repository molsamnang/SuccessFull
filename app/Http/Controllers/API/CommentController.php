<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post_;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
   public function index(Post_ $post)
    {
        return $post->comments()->with('customer')->latest()->get();
    }

    public function store(Request $request, Post_ $post)
    {
        $request->validate(['body' => 'required|string']);

        $comment = $post->comments()->create([
            'body' => $request->body,
            'customer_id' => $request->user()->id,
        ]);

        return response()->json($comment, 201);
    }
}
