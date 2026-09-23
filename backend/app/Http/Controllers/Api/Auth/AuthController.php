<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:160',
            'email' => 'required|email|max:160|unique:users,email',
            'phone' => 'required|string|max:32',
            'password' => 'required|string|min:8|confirmed',
            'company' => 'nullable|string|max:160',
            'gstin' => 'nullable|string|max:32',
        ]);

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'is_active' => true,
            ]);
            $user->assignRole('client');

            $customer = Customer::create([
                'user_id' => $user->id,
                'name' => $data['company'] ?? $data['name'],
                'type' => $data['company'] ? 'company' : 'individual',
                'phone' => $data['phone'],
                'email' => $data['email'],
                'gstin' => $data['gstin'] ?? null,
                'status' => 'active',
            ]);

            $token = $user->createToken('client', ['portal'])->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $this->userPayload($user),
                'customer' => ['code' => $customer->code, 'name' => $customer->name],
            ], 201);
        });
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($data, false)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        $user = $request->user();

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages(['email' => 'Account is disabled.']);
        }

        $abilities = $user->hasAnyRole(['super-admin', 'branch-manager', 'operations-supervisor', 'gate-clerk', 'accounts', 'cms-editor', 'maintenance'])
            ? ['admin'] : ['portal'];

        $token = $user->createToken($user->email, $abilities)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->userPayload($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request)
    {
        return $this->userPayload($request->user());
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }
}
