<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        return Lead::with(['commodity:id,name,slug', 'facility:id,name,slug'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->q, fn ($q, $term) => $q->where(fn ($qq) => $qq
                ->where('name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->orderByDesc('id')
            ->paginate($request->per_page ?? 25);
    }

    public function show(Lead $lead)
    {
        return $lead->load(['commodity', 'facility']);
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status' => 'in:new,contacted,qualified,quoted,won,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);
        $lead->update($data);
        return $lead;
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return response()->json(['message' => 'Lead deleted.']);
    }
}
