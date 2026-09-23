<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommodityResource;
use App\Models\Commodity;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    public function index(Request $request)
    {
        $query = Commodity::with('category')
            ->when($request->q, fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->when($request->category_id, fn ($q, $id) => $q->where('category_id', $id))
            ->orderBy('sort_order')->orderBy('name');

        return CommodityResource::collection($query->paginate($request->per_page ?? 30));
    }

    public function store(Request $request)
    {
        return new CommodityResource(Commodity::create($this->validated($request)));
    }

    public function show(Commodity $commodity)
    {
        return new CommodityResource($commodity->load('category'));
    }

    public function update(Request $request, Commodity $commodity)
    {
        $commodity->update($this->validated($request, $commodity->id));
        return new CommodityResource($commodity);
    }

    public function destroy(Commodity $commodity)
    {
        $commodity->delete();
        return response()->json(['message' => 'Commodity deleted.']);
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'category_id' => 'nullable|exists:commodity_categories,id',
            'name' => 'required|string|max:160',
            'slug' => 'nullable|string|max:160|unique:commodities,slug,'.$id,
            'hsn_code' => 'nullable|string|max:32',
            'gst_percent' => 'nullable|numeric|between:0,28',
            'recommended_temp_min_c' => 'nullable|numeric',
            'recommended_temp_max_c' => 'nullable|numeric',
            'recommended_humidity_min' => 'nullable|numeric|between:0,100',
            'recommended_humidity_max' => 'nullable|numeric|between:0,100',
            'shelf_life_days' => 'nullable|integer|min:0',
            'image' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:64',
            'short_description' => 'nullable|string|max:500',
            'body_html' => 'nullable|string',
            'faqs' => 'nullable|array',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
    }
}
