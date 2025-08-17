<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'qr_code',
        'qr_code_path',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function createdAssignments()
    {
        return $this->hasMany(Assignment::class, 'created_by');
    }

    public function uploadedMaterials()
    {
        return $this->hasMany(LearningMaterial::class, 'uploaded_by');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function walletTransactionsCreated()
    {
        return $this->hasMany(WalletTransaction::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function scannedAttendances()
    {
        return $this->hasMany(Attendance::class, 'scanned_by');
    }

    /**
     * Generate unique QR code for user
     */
    public function generateQrCode()
    {
        $qrCode = 'USER_' . $this->id . '_' . time() . '_' . strtoupper(substr(md5($this->email), 0, 8));
        $this->update(['qr_code' => $qrCode]);
        return $qrCode;
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
