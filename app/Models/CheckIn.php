<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = [
        'user_id',
        'check_in_date',
        'check_out_date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
