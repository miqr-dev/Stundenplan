<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConflictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('conflicts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('teacher_id');
        $table->unsignedBigInteger('first_course_id');
        $table->unsignedBigInteger('second_course_id');
        $table->date('date');
        $table->time('start_time');
        $table->time('end_time');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('teacher_id')->references('id')->on('teachers');
        $table->foreign('first_course_id')->references('id')->on('courses');
        $table->foreign('second_course_id')->references('id')->on('courses');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conflicts');
    }
}
