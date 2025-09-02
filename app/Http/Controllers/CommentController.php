<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post_;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index()
    {
        // Paginate comments for better performance
        $comments = Comment::with(['post', 'user', 'customer'])
            ->latest()
            ->paginate(10);

        // Pass posts for the create modal
        $posts = Post_::all();

        return view('comments.index', compact('comments', 'posts'));
    }


    /**
     * Store a newly created comment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:post_s,id',
            'body'    => 'required|string|max:500',
        ]);

        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->body = $request->body;

        // if logged in as customer
        if (Auth::guard('customer')->check()) {
            $comment->customer_id = Auth::guard('customer')->id();
        }
        // if logged in as normal user (admin, writer, etc.)
        elseif (Auth::check()) {
            $comment->user_id = Auth::id();
        }

        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Display the specified comment.
     */
    public function show(Comment $comment)
    {
        return response()->json($comment->load(['post', 'customer', 'user']));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required|string|max:500',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
