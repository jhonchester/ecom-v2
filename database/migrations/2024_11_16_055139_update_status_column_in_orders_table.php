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
    Schema::table('orders', function (Blueprint $table) {
        $table->enum('status', ['new', 'processing', 'claimed', 'cancelled'])->default('new')->change();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        // Revert changes if necessary, for example, changing back to a string.
        $table->string('status')->change();
    });
}

};
