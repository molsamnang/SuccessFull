<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'customer' => $this->whenLoaded('customer', [
                'id' => $this->customer->id ?? null,
                'name' => $this->customer->name ?? null,
                'profile_image' => $this->customer && $this->customer->profile_image ? url(Storage::url($this->customer->profile_image)) : null
            ]),
            'created_at' => $this->created_at
        ];
    }
}
