<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post_;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        // Eager load 'post', 'customer', 'user.roles' for hasRole check
        $comments = Comment::with(['post', 'customer', 'user.roles'])->latest()->get();
        $posts = Post_::all();

        return view('comments.index', compact('comments', 'posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:post_s,id',
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'customer_id' => Auth::user()->customer ? Auth::user()->customer->id : null,
            'post_id' => $request->post_id,
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $comment->update([
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
