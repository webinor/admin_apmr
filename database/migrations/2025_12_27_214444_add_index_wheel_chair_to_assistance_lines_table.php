<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexWheelChairToAssistanceLinesTable extends Migration
{
    public function up()
    {
        Schema::table('assistance_lines', function (Blueprint $table) {
            $table->index('wheel_chair_id', 'idx_assistance_lines_wheel_chair');
        });
    }

    public function down()
    {
        Schema::table('assistance_lines', function (Blueprint $table) {
            $table->dropIndex('idx_assistance_lines_wheel_chair');
        });
    }
}