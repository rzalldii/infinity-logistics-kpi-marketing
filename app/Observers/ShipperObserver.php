<?php

namespace App\Observers;
use App\Models\Shipper;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class ShipperObserver
{
    public function created(Shipper $shipper)
    {
        Audit::create([
            'auditable_type' => 'Shipper',
            'auditable_id' => $shipper->id,
            'event' => 'created',
            'user_id' => Auth::id(),
            'description' => $shipper->shipper_name,
            'new_values' => $shipper->toArray(),
        ]);
    }

    public function updated(Shipper $shipper)
    {
        Audit::create([
            'auditable_type' => 'Shipper',
            'auditable_id' => $shipper->id,
            'event' => 'updated',
            'user_id' => Auth::id(),
            'description' => $shipper->shipper_name,
            'old_values' => $shipper->getOriginal(),
            'new_values' => $shipper->getAttributes(),
        ]);
    }

    public function deleted(Shipper $shipper)
    {
        Audit::create([
            'auditable_type' => 'Shipper',
            'auditable_id' => $shipper->id,
            'event' => 'deleted',
            'user_id' => Auth::id(),
            'description' => $shipper->shipper_name,
            'old_values' => $shipper->toArray(),
        ]);
    }
}
