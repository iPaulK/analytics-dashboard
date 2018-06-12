<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAnalyticsWebpropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_webproperties', function (Blueprint $table) {
            $table->increments('id');

            $table->smallInteger('version')->nullable();
            $table->string('webpropertyId', 50)->nullable();
            
            $table->string('kind', 50)->nullable();
            $table->string('selfLink', 50)->nullable();
            $table->string('accountId', 50)->nullable();
            $table->string('internalWebPropertyId', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('websiteUrl', 50)->nullable();
            $table->string('level', 50)->nullable();
            $table->string('profileCount', 50)->nullable();
            $table->string('industryVertical', 50)->nullable();
            $table->string('defaultProfileId', 50)->nullable();
            $table->text('permissions');
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
        Schema::dropIfExists('google_analytics_webproperties');
    }
}
