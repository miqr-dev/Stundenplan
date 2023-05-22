<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedualMastersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
public function up()
{
  Schema::create('schedual_masters', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
    $table->integer('calendar_week')->index();
    $table->timestamps();
  });
}

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('schedual_masters');
  }
}
