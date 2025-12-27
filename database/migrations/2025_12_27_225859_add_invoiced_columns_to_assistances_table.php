<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoicedColumnsToAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('assistances', function (Blueprint $table) {
            $table->dateTime('invoiced_at')->nullable()->after('flight_date')->comment('Date à laquelle la facture a été générée');
            $table->unsignedBigInteger('invoiced_by')->nullable()->after('invoiced_at')->comment('Utilisateur qui a généré la facture');
        });
    }

    public function down(): void
    {
        Schema::table('assistances', function (Blueprint $table) {
            $table->dropColumn(['invoiced_at', 'invoiced_by']);
        });
    }
}
