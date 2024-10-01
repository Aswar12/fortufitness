<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'membership_type_id',
        'start_date',
        'end_date',
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
}
