<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CircularResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'category'     => $this->category,
            'summary'      => $this->summary,
            'content'      => $this->when($request->routeIs('*.show'), $this->content),
            'is_important' => $this->is_important,
            'published_at' => $this->published_at?->toISOString(),
            'created_at'   => $this->created_at?->toISOString(),
            'author'       => $this->whenLoaded('author', fn() => [
                'name' => $this->author->name,
            ]),
            'attachments'  => $this->whenLoaded('media', function () {
                if ($this->external_url) {
                    return [[
                        'name'      => 'External Link',
                        'url'       => $this->external_url,
                        'size'      => null,
                        'mime_type' => null,
                    ]];
                }
                return $this->getMedia('attachments')->map(fn($m) => [
                    'name'       => $m->file_name,
                    'url'        => $m->getUrl(),
                    'size'       => $m->size,
                    'mime_type'  => $m->mime_type,
                ]);
            }),
        ];
    }
}
