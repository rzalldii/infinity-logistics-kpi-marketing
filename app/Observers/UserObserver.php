<?php

namespace App\Observers;
use App\Models\User;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function created(User $user)
    {
        Audit::create([
            'auditable_type' => 'User',
            'auditable_id' => $user->id,
            'event' => 'created',
            'user_id' => Auth::id(),
            'description' => strtoupper($user->name) . ' - ' . $user->role,
            'new_values' => $user->toArray(),
        ]);
    }

    public function updated(User $user)
    {
        Audit::create([
            'auditable_type' => 'User',
            'auditable_id' => $user->id,
            'event' => 'updated',
            'user_id' => Auth::id(),
            'description' => strtoupper($user->name) . ' - ' . $user->role,
            'old_values' => $user->getOriginal(),
            'new_values' => $user->getAttributes(),
        ]);
    }

    public function deleted(User $user)
    {
        Audit::create([
            'auditable_type' => 'User',
            'auditable_id' => $user->id,
            'event' => 'deleted',
            'user_id' => Auth::id(),
            'description' => strtoupper($user->name) . ' - ' . $user->role,
            'old_values' => $user->toArray(),
        ]);
    }
}
