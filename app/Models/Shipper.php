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
        'shipper_type',
        'shipper_city',
        'shipper_address',
        'contact_person',
        'phone_number',
        'email_address',
        'export',
        'import',
        'domestic',
        'commodity',
        'input_date',
    ];

    protected $casts = [
        'input_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
