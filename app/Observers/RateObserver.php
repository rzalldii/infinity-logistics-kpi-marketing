<?php

namespace App\Observers;
use App\Models\Rate;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class RateObserver
{
    public function created(Rate $rate)
    {
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => $rate->id,
            'event' => 'created',
            'user_id' => Auth::id(),
            'description' => $rate->pol . ' - ' . $rate->pod,
            'new_values' => $rate->toArray(),
        ]);
    }

    public function updated(Rate $rate)
    {
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => $rate->id,
            'event' => 'updated',
            'user_id' => Auth::id(),
            'description' => $rate->pol . ' - ' . $rate->pod,
            'old_values' => $rate->getOriginal(),
            'new_values' => $rate->getAttributes(),
        ]);
    }

    public function deleted(Rate $rate)
    {
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => $rate->id,
            'event' => 'deleted',
            'user_id' => Auth::id(),
            'description' => $rate->pol . ' - ' . $rate->pod,
            'old_values' => $rate->toArray(),
        ]);
    }
}
