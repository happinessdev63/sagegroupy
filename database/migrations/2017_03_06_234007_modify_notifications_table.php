<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'notifications', function ( Blueprint $table ) {
            $table->integer( 'from_agency_id' )->unsigned()->nullable();
            $table->string( 'title' )->nullable();
            $table->integer("owner_id")->unsigned()->default(0);
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
            $table->dropColumn( "from_agency_id" );
            $table->dropColumn( "title" );
            $table->dropColumn( "owner_id" );
        }
        );
    }
}
