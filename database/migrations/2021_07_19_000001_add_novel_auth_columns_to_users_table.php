<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNovelAuthColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->after('id')->nullable();
            $table->string('phone')->after('email')->nullable();
            $table->dateTime('phone_verified_at')->after('email_verified_at')->nullable();
            $table->boolean('login_force_both')->after('phone_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('status', 'phone', 'phone_verified_at', 'login_force_both');
        });
    }
}
