<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeiertagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('feiertags', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bundesland_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->string('year');
        $table->date('date');
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
        Schema::dropIfExists('feiertags');
    }
}
