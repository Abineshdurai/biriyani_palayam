<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpsTable extends Migration
{
    public function up()
    {
        Schema::create('email_verification', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_verification');
    }
}
