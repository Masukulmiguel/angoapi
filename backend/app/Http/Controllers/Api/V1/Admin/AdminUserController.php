<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['subscriptions.plan', 'apiKeys']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        $user->load([
            'subscriptions.plan',
            'payments' => fn($q) => $q->latest()->limit(10),
            'apiKeys',
        ]);

        return new UserResource($user);
    }

    public function updateStatus(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,pending,suspended,rejected,blocked'],
        ]);

        $user->update(['status' => $validated['status']]);

        return response()->json([
            'user' => new UserResource($user->fresh()),
            'message' => 'User status updated.',
        ]);
    }
}
