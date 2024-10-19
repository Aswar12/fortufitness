<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Membership extends Model
{
    protected $dates = ['start_date', 'end_date'];
    protected $casts = [
        'end_date' => 'datetime',
    ];
    use HasFactory;
    protected $fillable = [
        'user_id',
        'membership_type_id',
        'start_date',
        'end_date',
        'cancelled_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }
    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now());
    }
    public function isActive()
    {
        return $this->status === 'active' && $this->end_date > now();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    } // Di model Membership
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
}
