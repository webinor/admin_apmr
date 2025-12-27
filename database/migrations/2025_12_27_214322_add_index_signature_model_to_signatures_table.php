<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexSignatureModelToSignaturesTable extends Migration
{
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->index(
                ['model_id', 'model_type'],
                'idx_assistances_signature'
            );
        });
    }

    public function down()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropIndex('idx_assistances_signature');
        });
    }
}