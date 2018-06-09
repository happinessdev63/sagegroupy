<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMediaPublicFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'mediables', function ( Blueprint $table ) {
            $table->boolean( "public" )->default( false );
            $table->boolean( "featured" )->default( false );
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
        Schema::table( 'mediables', function ( Blueprint $table ) {
            $table->dropColumn( "public" );
            $table->dropColumn( "featured" );
        }
        );
    }
}
