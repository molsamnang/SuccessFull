<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\Customer;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    // List all users except logged in (with friendship status)
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $friends = Customer::where('id', '!=', $userId)
            ->get(['id', 'name', 'profile_image']);

        // Attach friendship status
        $friends->transform(function ($friend) use ($userId) {
            $friendship = Friendship::where(function ($q) use ($userId, $friend) {
                $q->where('user_id', $userId)
                  ->where('friend_id', $friend->id);
            })->orWhere(function ($q) use ($userId, $friend) {
                $q->where('user_id', $friend->id)
                  ->where('friend_id', $userId);
            })->first();

            $friend->status = $friendship->status ?? null;
            $friend->friendship_id = $friendship->id ?? null;

            return $friend;
        });

        return response()->json($friends);
    }

    // Send request
    public function sendRequest(Request $request, $friendId)
    {
        $userId = $request->user()->id;

        if ($userId == $friendId) {
            return response()->json(['message' => 'You cannot add yourself'], 400);
        }

        $friendship = Friendship::firstOrCreate(
            [
                'user_id' => $userId,
                'friend_id' => $friendId
            ],
            [
                'status' => 'pending'
            ]
        );

        return response()->json(['message' => 'Friend request sent', 'data' => $friendship]);
    }

    // Incoming requests
    public function requests(Request $request)
    {
        $requests = Friendship::where('friend_id', $request->user()->id)
            ->where('status', 'pending')
            ->with(['user:id,name,profile_image'])
            ->get();

        return response()->json($requests);
    }

    // Accept / Reject request
    public function respond(Request $request, $id)
    {
        $friendship = Friendship::findOrFail($id);
        $action = $request->input('action'); // accept or reject

        if ($action === 'accept') {
            $friendship->update(['status' => 'accepted']);
        } elseif ($action === 'reject') {
            $friendship->update(['status' => 'rejected']);
        }

        return response()->json(['message' => 'Friend request updated']);
    }

    // Unfriend / Cancel request
    public function destroy(Request $request, $id)
    {
        $friendship = Friendship::findOrFail($id);

        if ($friendship->user_id == $request->user()->id || $friendship->friend_id == $request->user()->id) {
            $friendship->delete();
            return response()->json(['message' => 'Friendship removed']);
        }

        return response()->json(['message' => 'Not allowed'], 403);
    }
}
