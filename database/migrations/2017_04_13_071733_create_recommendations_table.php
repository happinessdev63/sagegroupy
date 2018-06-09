<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'recommendations', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer('from_user_id')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->text('snippet')->nullable();
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
        Schema::drop( 'recommendations' );
    }
}
