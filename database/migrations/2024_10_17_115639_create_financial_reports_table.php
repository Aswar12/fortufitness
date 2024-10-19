<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->decimal('total_revenue', 10, 2);
            $table->decimal('total_expenses', 10, 2);
            $table->decimal('net_income', 10, 2);
            $table->integer('total_memberships');
            $table->integer('new_memberships');
            $table->integer('cancelled_memberships');
            $table->integer('total_check_ins');
            $table->float('average_daily_check_ins');
            $table->string('top_membership_type');
            $table->string('top_expense_category');
            $table->decimal('total_pending_payments', 10, 2);
            $table->decimal('total_completed_payments', 10, 2);
            $table->decimal('total_failed_payments', 10, 2);
            $table->decimal('total_refunded_payments', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
