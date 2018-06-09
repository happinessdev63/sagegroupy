<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobPublicFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'jobs', function ( Blueprint $table ) {
            $table->boolean("public")->default( false );
            $table->boolean("public_files")->default( false );
            $table->boolean("invite_client")->default( false );
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
        Schema::table( 'jobs', function ( Blueprint $table ) {
            $table->dropColumn( "public" );
            $table->dropColumn( "public_files" );
            $table->dropColumn( "invite_client" );
        }
        );
    }
}
