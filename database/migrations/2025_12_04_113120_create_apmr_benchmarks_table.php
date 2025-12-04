<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApmrBenchmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apmr_benchmarks', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // download-single | download-all
            $table->float('recap_creation_time')->nullable();
            $table->float('download_only_time')->nullable();
            $table->float('generation_individual_files_time')->nullable();
            $table->float('zip_time')->nullable();
            $table->float('total_time')->nullable();
            $table->json('extra')->nullable(); // infos complémentaires (nombre de missions, agent, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apmr_benchmarks');
    }
}
