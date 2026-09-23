<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Banner;
use App\Models\Facility;
use App\Models\Commodity;

class MetaController extends Controller
{
    public function faqs(string $group = 'general')
    {
        return Faq::published()->where('group', $group)->orderBy('sort_order')->get(['question', 'answer', 'group']);
    }

    public function testimonials()
    {
        return Testimonial::published()->orderBy('sort_order')->get();
    }

    public function banners(string $placement)
    {
        return Banner::where('placement', $placement)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return [
            'slug' => $page->slug,
            'title' => $page->title,
            'body_html' => $page->body_html,
            'seo' => [
                'title' => $page->seo_title ?: $page->title,
                'description' => $page->seo_description,
                'og_image' => $page->og_image,
            ],
        ];
    }

    public function sitemap()
    {
        return [
            'facilities' => Facility::published()->get(['slug', 'updated_at'])->map(fn ($f) => [
                'loc' => '/facilities/'.$f->slug,
                'lastmod' => $f->updated_at?->toIso8601String(),
            ]),
            'commodities' => Commodity::published()->get(['slug', 'updated_at'])->map(fn ($c) => [
                'loc' => '/products/'.$c->slug,
                'lastmod' => $c->updated_at?->toIso8601String(),
            ]),
        ];
    }
}
