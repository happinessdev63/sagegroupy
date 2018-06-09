<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailLog extends Migration
{
    public function up()
    {
        Schema::create( 'mail_logs', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->boolean( 'system' )->default(false);
            $table->string( 'type' )->nullable();
            $table->integer( 'to_user_id' )->nullable();
            $table->integer( 'from_user_id' )->nullable();
            $table->integer( 'agency_id' )->nullable();
            $table->integer( 'job_id' )->nullable();
            $table->enum( 'status', [ 'sent','bounced','clicked','opened' ] )->default( "sent" )->nullable();
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
        Schema::drop( 'mail_logs' );
    }
}
