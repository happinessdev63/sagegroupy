<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_user', function (Blueprint $table) {
            $table->integer('skill_id')->unsigned()->index();
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->integer('skillable_id')->unsigned()->index();
            $table->string("skillable_type");
            $table->primary(['skill_id', 'skillable_id']);
            $table->enum('level', ['Entry','Junior','Intermediate','Senior','Expert'])->default('Junior');
            $table->integer('experience')->default(1);
            $table->float('rate')->default(0)->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('skill_user');
    }
}
