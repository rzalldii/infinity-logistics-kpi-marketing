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
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function setShipperNameAttribute($value)
    {
        $this->attributes['shipper_name'] = strtoupper($value);
    }

    public function setShipperCityAttribute($value)
    {
        $this->attributes['shipper_city'] = strtoupper($value);
    }

    public function setContactPersonAttribute($value)
    {
        $this->attributes['contact_person'] = strtoupper($value);
    }

    public function setExportAttribute($value)
    {
        $this->attributes['export'] = strtoupper($value);
    }

    public function setImportAttribute($value)
    {
        $this->attributes['import'] = strtoupper($value);
    }

    public function setDomesticAttribute($value)
    {
        $this->attributes['domestic'] = strtoupper($value);
    }

    public function setCommodityAttribute($value)
    {
        $this->attributes['commodity'] = strtoupper($value);
    }
}
