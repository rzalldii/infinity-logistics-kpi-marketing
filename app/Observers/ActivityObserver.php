<?php

namespace App\Observers;
use App\Models\Activity;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function created(Activity $activity)
    {
        Audit::create([
            'auditable_type' => 'Activity',
            'auditable_id' => $activity->id,
            'event' => 'created',
            'user_id' => Auth::id(),
            'description' => $activity->concept_type . ' - ' . $activity->activity_type,
        ]);
    }

    public function updated(Activity $activity)
    {
        Audit::create([
            'auditable_type' => 'Activity',
            'auditable_id' => $activity->id,
            'event' => 'updated',
            'user_id' => Auth::id(),
            'description' => $activity->concept_type . ' - ' . $activity->activity_type,
        ]);
    }

    public function deleted(Activity $activity)
    {
        Audit::create([
            'auditable_type' => 'Activity',
            'auditable_id' => $activity->id,
            'event' => 'deleted',
            'user_id' => Auth::id(),
            'description' => $activity->concept_type . ' - ' . $activity->activity_type,
        ]);
    }
}
