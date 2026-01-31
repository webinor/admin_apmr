<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeBillingAddressNullableInCompaniesTable extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->string('billing_address')->nullable()->change();
            $table->string('alias')->nullable()->change();
            $table->string('post_box')->nullable()->change();
            $table->string('uni')->nullable()->change();
            $table->string('rc')->nullable()->change();

        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            
            $table->string('billing_address')->nullable(false)->change();
            $table->string('alias')->nullable(false)->change();
            $table->string('post_box')->nullable(false)->change();
            $table->string('uni')->nullable(false)->change();
            $table->string('rc')->nullable(false)->change();

        });
    }
}
