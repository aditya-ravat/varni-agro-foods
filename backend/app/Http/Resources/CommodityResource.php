<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommodityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->uid,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->whenLoaded('category', fn () => [
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ]),
            'hsn_code' => $this->hsn_code,
            'gst_percent' => (float) $this->gst_percent,
            'recommended_temp_c' => [
                'min' => $this->recommended_temp_min_c,
                'max' => $this->recommended_temp_max_c,
            ],
            'recommended_humidity_pct' => [
                'min' => $this->recommended_humidity_min,
                'max' => $this->recommended_humidity_max,
            ],
            'shelf_life_days' => $this->shelf_life_days,
            'image' => $this->image,
            'icon' => $this->icon,
            'short_description' => $this->short_description,
            'body_html' => $this->body_html,
            'faqs' => $this->faqs,
            'is_featured' => (bool) $this->is_featured,
            'seo' => [
                'title' => $this->seo_title ?: "{$this->name} Cold Storage",
                'description' => $this->seo_description,
                'og_image' => $this->og_image ?: $this->image,
            ],
        ];
    }
}
