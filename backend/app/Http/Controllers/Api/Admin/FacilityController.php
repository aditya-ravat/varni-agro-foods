<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::query()
            ->when($request->q, fn ($q, $term) => $q->where(fn ($qq) => $qq
                ->where('name', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")))
            ->orderBy('sort_order')
            ->orderBy('name');

        return FacilityResource::collection($query->paginate($request->per_page ?? 20));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $facility = Facility::create($data);
        return new FacilityResource($facility);
    }

    public function show(Facility $facility)
    {
        return new FacilityResource($facility->load(['chambers', 'certifications']));
    }

    public function update(Request $request, Facility $facility)
    {
        $facility->update($this->validated($request, $facility->id));
        return new FacilityResource($facility);
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return response()->json(['message' => 'Facility deleted.']);
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:32|unique:facilities,code,'.$id,
            'name' => 'required|string|max:160',
            'slug' => 'nullable|string|max:160|unique:facilities,slug,'.$id,
            'tagline' => 'nullable|string|max:200',
            'description' => 'nullable|string|max:2000',
            'body_html' => 'nullable|string',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:80',
            'pincode' => 'nullable|string|max:16',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'gstin' => 'nullable|string|max:32',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:160',
            'whatsapp' => 'nullable|string|max:32',
            'opening_hours' => 'nullable|array',
            'total_capacity_mt' => 'nullable|numeric|min:0',
            'manager_id' => 'nullable|exists:users,id',
            'hero_image' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
    }
}
