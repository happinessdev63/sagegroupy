<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'views', function ( Blueprint $table ) {
            $table->string( 'source' )->default( "sagegroupy.com" )->nullable();
            $table->string( 'view_type' )->default( "view" )->nullable();
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
        Schema::table( 'views', function ( Blueprint $table ) {
            $table->dropColumn( "source" );
            $table->dropColumn( "view_type" );
        }
        );
    }
}
