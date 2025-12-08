<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;
    protected $table = "activities";

    protected $fillable = [
        'user_id',
        'report_date',
        'concept_type',
        'shipper_id',
        'activity_type',
        'visit_date',
        'status',
        'status_detail',
        'prospect',
    ];

    protected $casts = [
        'report_date' => 'date',
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
