<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'report_type', // Kolom baru
        'total_active_memberships', // Kolom baru
        'average_revenue_per_membership', // Kolom baru
        'average_expense_per_membership', // Kolom baru
        'peak_check_ins_day', // Kolom baru
        'peak_check_ins_date', // Kolom baru
        'total_pending_payments_count', // Kolom baru
        'total_completed_payments_count', // Kolom baru
        'total_failed_payments_count', // Kolom baru
        'total_refunded_payments_count', // Kolom baru
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_revenue' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_income' => 'decimal:2',
        'average_daily_check_ins' => 'float',
        'total_pending_payments' => 'decimal:2',
        'total_completed_payments' => 'decimal:2',
        'total_failed_payments' => 'decimal:2',
        'total_refunded_payments' => 'decimal:2',
        'report_type' => 'string', // Kolom baru
        'total_active_memberships' => 'integer', // Kolom baru
        'average_revenue_per_membership' => 'decimal:2', // Kolom baru
        'average_expense_per_membership' => 'decimal:2', // Kolom baru
        'peak_check_ins_day' => 'integer', // Kolom baru
        'peak_check_ins_date' => 'date', // Kolom baru
        'total_pending_payments_count' => 'integer', // Kolom baru
        'total_completed_payments_count' => 'integer', // Kolom baru
        'total_failed_payments_count' => 'integer', // Kolom baru
        'total_refunded_payments_count' => 'integer', // Kolom baru
    ];
}