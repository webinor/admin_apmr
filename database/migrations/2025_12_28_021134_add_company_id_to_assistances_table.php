<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToAssistancesTable extends Migration
{
    
public function up()
{
    Schema::table('assistances', function (Blueprint $table) {
        $table->foreignId('company_id')
              ->nullable()
              ->after('ground_agent_id')
              ->index();
    });
}

public function down()
{
    Schema::table('assistances', function (Blueprint $table) {
        $table->dropColumn('company_id');
    });
}
}
