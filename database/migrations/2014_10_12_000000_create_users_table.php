<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('city_id')->nullable();
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('position')->nullable(); 
            $table->string('abteilung')->nullable();
            $table->string('office')->nullable(); 
            $table->string('plz')->nullable(); 
            $table->string('bundesland')->nullable(); 
            $table->string('straße')->nullable(); 
            $table->string('ort')->nullable(); 
            $table->string('tel')->nullable();
            $table->string('email_privat')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
