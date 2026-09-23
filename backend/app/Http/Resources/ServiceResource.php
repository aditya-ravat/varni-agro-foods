<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'image' => $this->image,
            'short_description' => $this->short_description,
            'body_html' => $this->body_html,
            'faqs' => $this->faqs,
            'seo' => [
                'title' => $this->seo_title ?: $this->name,
                'description' => $this->seo_description ?: $this->short_description,
                'og_image' => $this->og_image ?: $this->image,
            ],
        ];
    }
}
