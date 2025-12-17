<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;

class Activity extends Model
{
    use HasFactory;
    protected $table = "activities";
    use Prunable;

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonths(3));
    }

    protected $fillable = [
        'user_id',
        'concept_type',
        'shipper_id',
        'activity_type',
        'visit_date',
        'status',
        'status_detail',
        'prospect',
    ];

    protected $casts = [
        'visit_date'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }
}
