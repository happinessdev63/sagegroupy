<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string("tagline")->default("");
            $table->string("city")->default("");
            $table->string("country")->default("");
            $table->mediumText("bio")->nullable();
            $table->string("company")->nullable();
            $table->string("company_description")->nullable();
            $table->string("phone")->nullable();
            $table->enum("role",['client','admin','freelancer','client_freelancer']);
            $table->rememberToken();
            $table->timestamps();
            $table->string('api_token')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
