<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $fillable = ['bank_name', 'account_name', 'account_number', 'is_active'];

    public static function getActiveAccounts()
    {
        return self::where('is_active', true)->get();
    }
}
