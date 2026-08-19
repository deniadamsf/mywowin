<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('order_items', function (Blueprint $table) {
        // $table->unsignedBigInteger('bundling_id')->nullable()->after('product_id');
        $table->foreign('bundling_id')->references('id_bundling')->on('bundlings')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropForeign(['bundling_id']);
        $table->dropColumn('bundling_id');
    });
}

};
