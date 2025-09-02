<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Share;

class ShareController extends Controller
{
    public function share(Request $request, $postId)
    {
        $share = Share::create(['customer_id' => $request->user()->id, 'post_id' => $postId]);
        $sharesCount = Share::where('post_id', $postId)->count();
        return response()->json(['shared' => true, 'shares_count' => $sharesCount]);
    }
}
