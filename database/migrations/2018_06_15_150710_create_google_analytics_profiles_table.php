<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAnalyticsProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('version')->nullable();
            $table->string('profileId', 50)->nullable();
            $table->string('accountId', 50)->nullable();
            $table->string('webPropertyId', 50)->nullable();
            $table->string('kind', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('selfLink', 50)->nullable();
            $table->string('internalWebPropertyId', 50)->nullable();
            $table->string('currency', 50)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('websiteUrl', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->boolean('eCommerceTracking');
            $table->boolean('enhancedECommerceTracking');
            $table->boolean('botFilteringEnabled');
            $table->boolean('starred');
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
        Schema::dropIfExists('google_analytics_profiles');
    }
}
