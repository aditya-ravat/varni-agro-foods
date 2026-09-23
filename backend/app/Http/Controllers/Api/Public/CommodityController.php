<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommodityResource;
use App\Models\Commodity;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    public function index(Request $request)
    {
        $commodities = Commodity::published()
            ->when($request->boolean('featured'), fn ($q) => $q->featured())
            ->when($request->category, fn ($q, $cat) => $q->whereHas('category', fn ($qq) => $qq->where('slug', $cat)))
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return CommodityResource::collection($commodities);
    }

    public function show(string $slug)
    {
        $commodity = Commodity::published()->with('category')->where('slug', $slug)->firstOrFail();
        return new CommodityResource($commodity);
    }
}
