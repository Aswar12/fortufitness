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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('membership_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamps();
        });

        if (Schema::hasTable('membership_types')) {
            Schema::table('memberships', function (Blueprint $table) {
                $table->foreign('membership_type_id')->constrained('membership_types');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
