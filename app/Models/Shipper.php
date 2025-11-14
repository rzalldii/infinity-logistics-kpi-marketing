<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipper extends Model
{
    use HasFactory;
    protected $table = "shippers";

    protected $fillable = [
        'user_id',
        'shipper_name',
        'city_name',
        'contact_person',
        'phone_number',
        'email_address',
        'input',
        'remarks',
    ];

    protected $casts = [
        'input' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
