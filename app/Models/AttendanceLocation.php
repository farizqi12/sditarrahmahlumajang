<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius_meter',
        'qrcode_path',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'location_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'attendance_location_role');
    }
}