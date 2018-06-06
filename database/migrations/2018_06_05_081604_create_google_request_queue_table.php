<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleRequestQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_request_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->string('command', 50)->nullable();
            $table->string('params', 250)->nullable();
            $table->smallInteger('status')->nullable();
            $table->text('messages')->nullable();
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
        Schema::dropIfExists('google_request_queue');
    }
}
