<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $query = Tariff::with(['commodity:id,name,slug', 'season:id,name,code', 'packing:id,name'])
            ->when($request->commodity_id, fn ($q, $id) => $q->where('commodity_id', $id))
            ->when($request->season_id, fn ($q, $id) => $q->where('season_id', $id))
            ->orderByDesc('id');

        return $query->paginate($request->per_page ?? 30);
    }

    public function store(Request $request)
    {
        return Tariff::create($this->validated($request));
    }

    public function show(Tariff $tariff)
    {
        return $tariff->load(['commodity', 'season', 'packing', 'surcharges']);
    }

    public function update(Request $request, Tariff $tariff)
    {
        $tariff->update($this->validated($request));
        return $tariff;
    }

    public function destroy(Tariff $tariff)
    {
        $tariff->delete();
        return response()->json(['message' => 'Tariff deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'season_id' => 'nullable|exists:seasons,id',
            'commodity_id' => 'required|exists:commodities,id',
            'packing_id' => 'nullable|exists:packings,id',
            'chamber_type' => 'nullable|string|max:32',
            'rate' => 'required|numeric|min:0',
            'basis' => 'required|in:per_bag_per_month,per_mt_per_day,per_mt_per_month,flat',
            'min_charge' => 'numeric|min:0',
            'advance_percent' => 'numeric|between:0,100',
            'is_active' => 'boolean',
        ]);
    }
}
