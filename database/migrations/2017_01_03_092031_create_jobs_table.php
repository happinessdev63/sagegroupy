<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('job_id');
            $table->text('description');
            $table->text('payment_terms');
            $table->string('status');
            $table->string('type');
            $table->integer('freelancer_id')->nullable()->default(null);
            $table->integer('client_id')->nullable()->default( null );
            $table->integer('agency_id')->nullable()->default( null );
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
        Schema::drop('jobs');
    }
}
