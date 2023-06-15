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
        Schema::create('users', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('profile_photo')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->bigInteger('phone_number')->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('is_disabled')->default(true);
            $table->string('country');
            $table->string('gender');
            $table->string('role_id')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
