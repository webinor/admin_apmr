<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToInvoicesTable extends Migration
{
   public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');

            // Si tu veux la rendre obligatoire
            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
           $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
}
