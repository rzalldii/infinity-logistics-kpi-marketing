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
            'description' => strtoupper($rate->pol) . ' - ' . strtoupper($rate->pod),
        ]);
    }

    public function updated(Rate $rate)
    {
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => $rate->id,
            'event' => 'updated',
            'user_id' => Auth::id(),
            'description' => strtoupper($rate->pol) . ' - ' . strtoupper($rate->pod),
        ]);
    }

    public function deleted(Rate $rate)
    {
        Audit::create([
            'auditable_type' => 'Rate',
            'auditable_id' => $rate->id,
            'event' => 'deleted',
            'user_id' => Auth::id(),
            'description' => strtoupper($rate->pol) . ' - ' . strtoupper($rate->pod),
        ]);
    }
}
