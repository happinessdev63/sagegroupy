<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'links', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'type' )->default( "reference" )->nullable();
            $table->string( 'status' )->default( "active" )->nullable();
            $table->string( 'url' )->default( "" )->nullable();
            $table->string( 'title' )->default( "" )->nullable();
            $table->text( 'description' )->nullable();
            $table->integer( 'user_id' )->nullable()->default( null );
            $table->integer( 'agency_id' )->nullable()->default( null );
            $table->index( 'user_id' );
            $table->index( 'agency_id' );
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
        Schema::drop( 'links' );
    }
}
