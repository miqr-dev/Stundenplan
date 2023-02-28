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
      $table->unsignedBigInteger('grid_id');
      $table->string('name');
      $table->date('start_date');
      $table->date('end_date');
      $table->string('color');
      $table->string('type')->nullable();
      $table->string('lbrn')->nullable();
      $table->mediumText('description')->nullable();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->softDeletes();
      $table->foreign('city_id')->references('id')->on('cities');
      $table->foreign('template_id')->references('id')->on('templates');
      $table->foreign('grid_id')->references('id')->on('grids');
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
