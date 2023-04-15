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
        Schema::create('client_pay_tasks', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('tasker_id');
            $table->unsignedBigInteger('client_id');
            $table->decimal('amount', 16, 8)->default('0.00');
            $table->enum('status', ['pending', 'paid', 'failed']);
            $table->string('stripe_token');

            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('tasker_id')->references('id')->on('users');

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
        Schema::dropIfExists('client_pay_tasks');
    }
};
