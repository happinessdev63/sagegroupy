<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->integer('from_user_id')->nullable();
            $table->integer('agency_id')->nullable();
            $table->integer('job_id')->nullable();
            $table->text('message')->nullable();
            $table->enum('status',['unread','read','saved','archived','deleted'])->default("unread")->nullable();
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
        Schema::drop('notifications');
    }
}
