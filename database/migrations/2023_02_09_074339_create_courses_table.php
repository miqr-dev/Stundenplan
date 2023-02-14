<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('courses', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('city_id');
      $table->unsignedBigInteger('template_id');
      $table->string('name');
      $table->string('type');
      $table->string('lbrn');
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('city_id')->references('id')->on('cities');
      $table->foreign('template_id')->references('id')->on('templates');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('courses');
  }
}
