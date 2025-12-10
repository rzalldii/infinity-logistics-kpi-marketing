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

    public function setPolAttribute($value)
    {
        $this->attributes['pol'] = strtoupper($value);
    }

    public function setPodAttribute($value)
    {
        $this->attributes['pod'] = strtoupper($value);
    }

    public function setLinerAttribute($value)
    {
        $this->attributes['liner'] = strtoupper($value);
    }

    public function setFreeTimeAttribute($value)
    {
        $this->attributes['free_time'] = strtoupper($value);
    }
}
