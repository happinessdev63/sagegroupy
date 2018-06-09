<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgencyFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'agencies', function ( Blueprint $table ) {
            $table->text( 'company_description' );
            $table->text( 'location' );
            $table->string( 'city' );
            $table->string( 'country' );
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
        Schema::table( 'agencies', function ( Blueprint $table ) {
            $table->dropColumn( "company_description" );
            $table->dropColumn( "description" );
            $table->dropColumn( "location" );
            $table->dropColumn( "city" );
            $table->dropColumn( "country" );
        }
        );
    }
}
