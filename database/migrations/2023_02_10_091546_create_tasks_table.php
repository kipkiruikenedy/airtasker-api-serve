<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('title');
            $table->string('description');
            $table->integer('amount');
            $table->timestamp('deadline');
            $table->string('time');
            $table->string('status')->default("OPEN")->comment('ASSIGNED','REJECTED','COMPLETED');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tasker_id')->nullable();
            $table->unsignedBigInteger('category_id');

            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('tasker_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
         
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
        Schema::dropIfExists('tasks');
    }
};
