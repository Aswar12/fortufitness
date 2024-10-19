<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelledAtToMembershipsTable extends Migration
{
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('cancelled_at');
        });
    }
}
