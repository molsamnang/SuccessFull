<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post_;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Display all comments
    public function index()
    {
        $comments = Comment::with(['post', 'customer'])->latest()->get();
        $posts = Post_::all(); // for dropdown in create modal

        return view('comments.index', compact('comments', 'posts'));
    }

    // Store new comment
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:post_s,id',
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $request->post_id,
            'customer_id' => Auth::user()->id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment added successfully!');
    }

    // Update existing comment
    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update([
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment updated successfully!');
    }

    // Delete comment
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
