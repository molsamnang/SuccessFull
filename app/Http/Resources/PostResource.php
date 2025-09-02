<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $images = array_filter(array_merge(
            $this->image ? [$this->image] : [],
            (array) $this->images
        ));

        $imageUrls = array_map(fn($p) => $p ? url(Storage::url($p)) : null, $images);

        $liked = false;
        if ($request->user()) {
            $liked = $this->likes()->where('customer_id', $request->user()->id)->exists();
        }

        return [
            'id' => $this->id,
            'content' => $this->content,
            'status' => $this->status,
            'customer' => $this->whenLoaded('customer', [
                'id' => $this->customer->id ?? null,
                'name' => $this->customer->name ?? null,
                'profile_image' => $this->customer && $this->customer->profile_image ? url(Storage::url($this->customer->profile_image)) : null,
            ]),
            'category' => $this->whenLoaded('category', $this->category ? ['id' => $this->category->id, 'name' => $this->category->name] : null),
            'images' => $imageUrls,
            'likes_count' => $this->likes_count ?? $this->likes()->count(),
            'shares_count' => $this->shares_count ?? $this->shares()->count(),
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
            'liked_by_user' => (bool) $liked,
            'created_at' => $this->created_at,
        ];
    }
}
