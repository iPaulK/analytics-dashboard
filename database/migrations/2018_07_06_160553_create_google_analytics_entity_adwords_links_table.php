<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAnalyticsEntityAdwordsLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_analytics_entity_adwords_links', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('version')->nullable();
            $table->string('entityAdWordsLinkId', 50)->nullable();
            $table->string('webPropertyId', 50)->nullable();
            $table->string('kind', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('selfLink', 50)->nullable();
            $table->text('adWordsAccounts');
            $table->text('profileIds');
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
        Schema::dropIfExists('google_analytics_entity_adwords_links');
    }
}
