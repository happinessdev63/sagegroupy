<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'general_references', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'title' )->nullable();
            $table->text( 'description' )->nullable();
            $table->string( 'status' )->default('active')->nullable();
            $table->string( 'type' )->nullable();
            $table->integer( 'user_id' )->nullable()->default( null );
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
        Schema::drop( 'general_references' );
    }
}
