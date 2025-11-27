<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;
    protected $table = "rates";

    protected $fillable = [
        'user_id',
        'pol',
        'pod',
        'container_type',
        'container_20',
        'container_40',
        'liner',
        'free_time',
        'valid_date',
        'notes',
    ];

    protected $casts = [
        'valid_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
