<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'notifications', function ( Blueprint $table ) {
            $table->string( 'owner_type' )->default("user")->nullable();
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
        Schema::table( 'notifications', function ( Blueprint $table ) {
            $table->dropColumn( "owner_type" );
        }
        );
    }
}
