<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('last_login_at')->nullable();
            $table->integer('login_streak')->default(0);
            $table->integer('total_points')->default(0);
            $table->integer('points_today')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
            $table->dropColumn('login_streak');
            $table->dropColumn('total_points');
            $table->dropColumn('points_today');
        });
    }
    
};
