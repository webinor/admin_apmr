<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingPeriodToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Période de facturation
            $table->date('start_date')->nullable()->after('invoice_number')->comment('Début de la période facturée');
            $table->date('end_date')->nullable()->after('start_date')->comment('Fin de la période facturée');

            // Index pour filtrer rapidement par période et compagnie
            $table->index(['start_date', 'end_date'], 'idx_invoices_company_period');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_company_period');
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
}
