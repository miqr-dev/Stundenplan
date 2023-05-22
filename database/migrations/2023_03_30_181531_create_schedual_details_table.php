<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedualDetailsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('schedual_details', function (Blueprint $table) {
      $table->id();
      $table->foreignId('schedual_master_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->unsignedBigInteger('grid_slot_id');
      $table->time('start_time');
      $table->time('end_time');
      $table->boolean('is_break')->default(false);
      $table->foreignId('subject_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('teacher_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('room_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->date('date')->nullable();
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
    Schema::dropIfExists('schedual_details');
  }
}
