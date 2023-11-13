<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatesTrackingEndpointTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_endpoint_target', function (Blueprint $table) {
            $table->id();
            $table->string('target');
            $table->string('num_of_results')->nullable(true);
            $table->timestamps();
            $table->index('target');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_endpoint_target');
    }
}
