<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $facilities = Facility::published()
            ->when($request->city, fn ($q) => $q->where('city', $request->city))
            ->orderBy('sort_order')
            ->get();

        return FacilityResource::collection($facilities);
    }

    public function show(string $slug)
    {
        $facility = Facility::published()
            ->with(['chambers' => fn ($q) => $q->where('status', 'active'), 'certifications'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new FacilityResource($facility);
    }
}
