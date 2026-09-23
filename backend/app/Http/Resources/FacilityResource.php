<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->uid,
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'body_html' => $this->body_html,
            'address' => [
                'line1' => $this->address_line1,
                'line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'pincode' => $this->pincode,
            ],
            'location' => [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ],
            'contact' => [
                'phone' => $this->phone,
                'email' => $this->email,
                'whatsapp' => $this->whatsapp,
            ],
            'opening_hours' => $this->opening_hours,
            'capacity_mt' => (float) $this->total_capacity_mt,
            'hero_image' => $this->hero_image,
            'gstin' => $this->gstin,
            'seo' => [
                'title' => $this->seo_title ?: $this->name,
                'description' => $this->seo_description ?: $this->description,
                'og_image' => $this->og_image ?: $this->hero_image,
            ],
            'chambers_count' => $this->whenLoaded('chambers', fn () => $this->chambers->count()),
            'chambers' => $this->whenLoaded('chambers', fn () => $this->chambers->map(fn ($c) => [
                'code' => $c->code,
                'name' => $c->name,
                'type' => $c->type,
                'capacity_mt' => (float) $c->net_capacity_mt,
                'temp_min_c' => (float) $c->temp_min_c,
                'temp_max_c' => (float) $c->temp_max_c,
            ])),
            'certifications' => $this->whenLoaded('certifications', fn () => $this->certifications->pluck('name')),
        ];
    }
}
