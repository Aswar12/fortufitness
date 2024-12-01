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
        Schema::dropIfExists('financial_reports');

        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            // Tambahkan kolom report_type dengan enum
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly');

            $table->date('report_date');
            $table->decimal('total_revenue', 15, 2)->nullable(); // Ubah menjadi nullable
            $table->decimal('total_expenses', 15, 2)->nullable(); // Ubah menjadi nullable
            $table->decimal('net_income', 15, 2)->nullable(); // Ubah menjadi nullable
            $table->integer('total_memberships')->nullable(); // Ubah menjadi nullable
            $table->integer('new_memberships')->nullable(); // Ubah menjadi nullable
            $table->integer('cancelled_memberships')->nullable(); // Ubah menjadi nullable
            $table->integer('total_check_ins')->nullable(); // Ubah menjadi nullable
            $table->float('average_daily_check_ins')->nullable(); // Ubah menjadi nullable
            $table->string('top_membership_type')->nullable();
            $table->string('top_expense_category')->nullable();
            $table->decimal('total_pending_payments', 15, 2)->nullable(); // Ubah menjadi nullable
            $table->decimal('total_completed_payments', 15, 2)->nullable(); // Ubah menjadi nullable
            $table->decimal('total_failed_payments', 15, 2)->nullable(); // Ubah menjadi nullable
            $table->decimal('total_refunded_payments', 15, 2)->nullable(); // Ubah menjadi nullable
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
