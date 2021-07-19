<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->morphs('otpable');
            $table->enum('type', ['email', 'call', 'sms', 'ussd', 'telegram', 'whatsapp']);
            $table->string('code')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['otpable_type', 'otpable_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_codes');
    }
}
