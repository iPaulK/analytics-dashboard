<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGaPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ga_permissions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('account_id', 50)->nullable();
            $table->primary(['user_id', 'account_id']);

            $table->index('user_id', 'IDX_PERMISSIONS_USER_ID');
            $table->index('account_id', 'IDX_PERMISSIONS_ACCOUNT_ID');

            $table->foreign('user_id', 'FK_PERMISSIONS_USER_ID')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ga_permissions');
    }
}
