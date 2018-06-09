<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'general_references', function ( Blueprint $table ) {
            $table->integer( 'client_id' )->nullable();
            $table->text( 'review' )->nullable();
            $table->text( 'client_email' )->nullable();
            $table->text( 'client_message' )->nullable();
            $table->text( 'url' )->nullable();
            $table->text( 'url_description' )->nullable();
            $table->integer( 'rating' )->nullable();
            $table->integer( 'agency_id' )->nullable()->default( null );
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'general_references', function ( Blueprint $table ) {
            $table->dropColumn( "review","rating","client_id", "agency_id", "client_email", "client_message");
        }
        );
    }
}
