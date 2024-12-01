<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'report_date',
        'total_revenue',
        'total_expenses',
        'net_income',
        'total_memberships',
        'new_memberships',
        'cancelled_memberships',
        'total_check_ins',
        'average_daily_check_ins',
        'top_membership_type',
        'top_expense_category',
        'total_pending_payments',
        'total_completed_payments',
        'total_failed_payments',
        'total_refunded_payments',
    ];

    protected $casts = [
        'report_type' => 'string',
        'report_date' => 'date',
        'total_revenue' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_income' => 'decimal:2',
        'total_memberships' => 'integer',
        'new_memberships' => 'integer',
        'cancelled_memberships' => 'integer',
        'total_check_ins' => 'integer',
        'average_daily_check_ins' => 'float',
        'total_pending_payments' => 'decimal:2',
        'total_completed_payments' => 'decimal:2',
        'total_failed_payments' => 'decimal:2',
        'total_refunded_payments' => 'decimal:2',
    ];
}
