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
        Schema::create('task_payment_trackers', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string("user_id");
            $table->string("amount");
            $table->string("payment_status")->default(0)->comment("0=unpaid","1=paid");
            $table->string("transaction_code");
            $table->string("payment_method");
            $table->timestamp("payment_date");
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
        Schema::dropIfExists('task_payment_trackers');
    }
};
