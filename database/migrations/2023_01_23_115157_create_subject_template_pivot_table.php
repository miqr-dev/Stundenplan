<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTemplatePivotTable extends Migration
{
  public function up()
  {
    Schema::create('subject_template', function (Blueprint $table) {
      $table->unsignedBigInteger('subject_id');
      $table->unsignedBigInteger('template_id');
      $table->unique(['subject_id', 'template_id']);
      $table->integer('soll')->nullable();
      $table->integer('ist')->default(0);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('subject_template_pivot');
  }
}
