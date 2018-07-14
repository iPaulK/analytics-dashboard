<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAnalyticsProfileFilterLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_profile_filter_links', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('version')->nullable();
            $table->string('kind', 50)->nullable();
            $table->string('selfLink', 50)->nullable();
            $table->string('profileFilterLinkId', 50)->nullable();
            $table->string('accountId', 50)->nullable();
            $table->string('webPropertyId', 50)->nullable();
            $table->string('profileId', 50)->nullable();
            $table->string('filterId', 50)->nullable();
            $table->integer('rank')->nullable();
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
        Schema::dropIfExists('google_analytics_profile_filter_links');
    }
}
