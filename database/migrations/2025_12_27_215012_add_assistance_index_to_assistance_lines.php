<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssistanceIndexToAssistanceLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up(): void
    {
        Schema::table('assistance_lines', function (Blueprint $table) {
            $table->index('assistance_id', 'idx_assistance_lines_assistance');
        });
    }

    public function down(): void
    {
        Schema::table('assistance_lines', function (Blueprint $table) {
            $table->dropIndex('idx_assistance_lines_assistance');
        });
    }
}
