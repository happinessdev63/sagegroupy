<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'views', function ( Blueprint $table ) {
            $table->increments( 'id' );
            /** The type of viewer, user or anon */
            $table->string( 'type' )->default("user")->nullable();
            $table->integer( 'viewer_id' )->nullable()->default( null );
            $table->integer( 'job_id' )->nullable()->default( null );
            $table->integer( 'user_id' )->nullable()->default( null );
            $table->integer( 'agency_id' )->nullable()->default( null );
            $table->index('user_id');
            $table->index('job_id');
            $table->index('agency_id');
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'views' );
    }
}
