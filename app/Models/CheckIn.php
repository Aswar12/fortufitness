<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'check_in_date',
        'check_out_date',
        'check_in_method',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
