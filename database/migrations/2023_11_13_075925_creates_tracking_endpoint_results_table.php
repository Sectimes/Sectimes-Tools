<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatesTrackingEndpointResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_endpoint_results', function (Blueprint $table) {
            $table->id();
            $table->string('target')->index(); // Indexing the foreign key for better performance
            $table->string('endpoint', 500);
            $table->string('status');
            $table->string('tag');
            $table->string('attribute');
            $table->timestamps();

            $table->foreign('target')->references('target')->on('tracking_endpoint_target')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_endpoint_results');
    }
}
