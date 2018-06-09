<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'invites', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'job_id' )->unsigned()->index();
            $table->foreign( 'job_id' )->references( 'id' )->on( 'jobs' )->onDelete( 'cascade' );
            $table->integer( 'client_id' )->unsigned()->index();
            $table->foreign( 'client_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->integer( 'freelancer_id' )->unsigned()->index()->nullable();
            $table->foreign( 'freelancer_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->integer( 'agency_id' )->unsigned()->index()->nullable();
            $table->foreign( 'agency_id' )->references( 'id' )->on( 'agencies' )->onDelete( 'cascade' );
            $table->string( 'status')->default('new')->nullable();
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
        Schema::drop( 'invites' );
    }
}
