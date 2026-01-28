<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;
    protected $table = "activities";

    protected $fillable = [
        'parent_id',
        'sequence',    
        'user_id',
        'shipper_id',
        'activity_type',
        'visit_date',
        'status_type',
        'volume_20',
        'volume_40',
        'other_volume',
        'profit',
        'remarks',
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

    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }
}
