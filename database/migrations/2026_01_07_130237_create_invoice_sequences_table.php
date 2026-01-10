<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_sequences', function (Blueprint $table) {
    $table->id();
    //$table->string('prefix', 10);   // AF, RW, etc.
    $table->unsignedInteger('last_number')->default(0);
    $table->timestamps();

    //$table->unique('prefix');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_sequences');
    }
}
