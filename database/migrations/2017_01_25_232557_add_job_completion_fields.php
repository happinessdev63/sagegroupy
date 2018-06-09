<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobCompletionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'jobs', function ( Blueprint $table ) {
            $table->timestamp("completed_at")->nullable();
            $table->text("end_reason");
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
            $table->dropColumn( "completed_at" );
            $table->dropColumn( "end_reason" );
        }
        );
    }
}
