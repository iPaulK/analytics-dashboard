<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAnalyticsEntityCustomMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_entity_custom_metrics', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('version')->nullable();
            $table->string('kind', 50)->nullable();
            $table->string('selfLink', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('min_value', 50)->nullable();
            $table->string('max_value', 50)->nullable();
            $table->integer('index')->nullable();
            $table->string('scope', 255)->nullable();
            $table->boolean('active', 255)->nullable();
            $table->string('customMetricId', 50)->nullable();
            $table->string('accountId', 50)->nullable();
            $table->string('webPropertyId', 50)->nullable();
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
        Schema::dropIfExists('google_analytics_entity_custom_metrics');
    }
}
