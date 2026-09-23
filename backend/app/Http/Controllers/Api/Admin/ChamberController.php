<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chamber;
use Illuminate\Http\Request;

class ChamberController extends Controller
{
    public function index(Request $request)
    {
        $query = Chamber::with(['facility:id,name,code', 'block:id,name,code'])
            ->when($request->facility_id, fn ($q, $id) => $q->where('facility_id', $id))
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s));

        return $query->orderBy('facility_id')->orderBy('code')->paginate($request->per_page ?? 30);
    }

    public function store(Request $request)
    {
        return Chamber::create($this->validated($request));
    }

    public function show(Chamber $chamber)
    {
        return $chamber->load(['facility', 'block', 'locations']);
    }

    public function update(Request $request, Chamber $chamber)
    {
        $chamber->update($this->validated($request, $chamber->id));
        return $chamber;
    }

    public function destroy(Chamber $chamber)
    {
        $chamber->delete();
        return response()->json(['message' => 'Chamber deleted.']);
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'block_id' => 'nullable|exists:blocks,id',
            'code' => 'required|string|max:32',
            'name' => 'required|string|max:160',
            'type' => 'required|in:cold_room,freezer,ca,ripening,blast,ambient',
            'temp_min_c' => 'nullable|numeric',
            'temp_max_c' => 'nullable|numeric',
            'humidity_min_pct' => 'nullable|numeric',
            'humidity_max_pct' => 'nullable|numeric',
            'gross_capacity_mt' => 'required|numeric|min:0',
            'net_capacity_mt' => 'required|numeric|min:0',
            'refrigeration_unit' => 'nullable|string|max:160',
            'status' => 'required|in:active,maintenance,inactive',
            'notes' => 'nullable|string',
        ]);
    }
}
