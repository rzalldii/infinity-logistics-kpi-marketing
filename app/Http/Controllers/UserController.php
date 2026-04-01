<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name', 'asc')->get();
        if ($request->ajax()) {
            return response()->json($users);
        }
        return view('users.index', compact('users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'role' => 'required|in:SUPER ADMIN,ADMIN,MARKETING,GUEST',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function edit($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user->makeHidden(['password']));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:5',
            'role' => 'required|in:SUPER ADMIN,ADMIN,MARKETING,GUEST',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return response()->json($user, 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return response()->json(null, 422);
        }
        $user->delete();
        return response()->json(null, 204);
    }
}
