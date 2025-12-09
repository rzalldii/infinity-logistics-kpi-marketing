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
            'new_values' => $activity->toArray(),
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
            'old_values' => $activity->getOriginal(),
            'new_values' => $activity->getChanges(),
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
            'old_values' => $activity->toArray(),
        ]);
    }
}
