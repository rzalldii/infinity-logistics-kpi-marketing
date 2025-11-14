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
        'container',
        'container_20',
        'container_40',
        'liner',
        'valid',
        'notes',
    ];

    protected $casts = [
        'container_20' => 'decimal:2',
        'container_40' => 'decimal:2',
        'valid' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
