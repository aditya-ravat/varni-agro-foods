<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Subscriber;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function quote(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:160',
            'phone' => 'required|string|max:32',
            'email' => 'nullable|email',
            'company' => 'nullable|string|max:160',
            'city' => 'nullable|string|max:120',
            'commodity_id' => 'nullable|exists:commodities,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'estimated_qty' => 'nullable|numeric|min:0',
            'estimated_qty_unit' => 'nullable|string|max:32',
            'required_from' => 'nullable|date',
            'message' => 'nullable|string|max:2000',
            'utm_source' => 'nullable|string|max:120',
            'utm_medium' => 'nullable|string|max:120',
            'utm_campaign' => 'nullable|string|max:120',
        ]);

        $data['source'] = 'website';
        $data['status'] = 'new';

        $lead = Lead::create($data);

        return response()->json([
            'message' => 'Thank you. Our team will contact you shortly.',
            'reference' => 'LD-'.str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT),
        ], 201);
    }

    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:160',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:32',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($data);

        return response()->json(['message' => 'Message received. We will get back to you soon.'], 201);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:160',
            'source' => 'nullable|string|max:80',
        ]);

        Subscriber::firstOrCreate(['email' => $data['email']], $data + ['is_active' => true]);

        return response()->json(['message' => 'Subscribed.'], 201);
    }
}
