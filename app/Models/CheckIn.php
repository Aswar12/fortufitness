<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'check_in_time',
        'check_out_time',
        'check_in_method',
        'created_at'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
