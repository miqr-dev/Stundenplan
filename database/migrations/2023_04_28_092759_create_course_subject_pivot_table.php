<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSubjectPivotTable extends Migration
{
    public function up()
    {
        Schema::create('course_subject', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('template_id'); // include template_id
            $table->integer('soll')->nullable();
            $table->integer('ist')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');

            // Unique constraint for course_id, subject_id, and template_id
            $table->unique(['course_id', 'subject_id', 'template_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_subject');
    }
}