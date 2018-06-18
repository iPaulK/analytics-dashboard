<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleTagmanagerAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_tagmanager_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('version')->nullable();
            $table->string('accountId', 50)->nullable();
            $table->string('path', 250)->nullable();
            $table->string('name', 250)->nullable();
            $table->boolean('shareData');
            $table->string('fingerprint', 250)->nullable();
            $table->string('tagManagerUrl', 50)->nullable();
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
        Schema::dropIfExists('google_tagmanager_accounts');
    }
}
