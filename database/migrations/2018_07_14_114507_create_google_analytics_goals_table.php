<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAnalyticsGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_goals', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('version')->nullable();
            $table->string('kind', 50)->nullable();
            $table->string('selfLink', 50)->nullable();
            $table->string('goalId', 50)->nullable();
            $table->string('accountId', 50)->nullable();
            $table->string('webPropertyId', 50)->nullable();
            $table->string('internalWebPropertyId', 50)->nullable();
            $table->string('profileId', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('value', 50)->nullable();
            $table->string('type', 255)->nullable();
            $table->boolean('active', 255)->nullable();
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
        Schema::dropIfExists('google_analytics_goals');
    }
}
