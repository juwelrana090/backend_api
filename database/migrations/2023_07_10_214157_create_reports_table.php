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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10);
            $table->string('buyer', 255);
            $table->string('receipt_id', 20);
            $table->string('items', 20);
            $table->string('buyer_email', 50);
            $table->string('buyer_ip', 20);
            $table->text('note');
            $table->string('city', 20);
            $table->string('phone', 20);
            $table->string('hash_key', 255);
            $table->date('entry_at');
            $table->unsignedBigInteger('entry_by');
            $table->foreign('entry_by')->references('id')->on('users');
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
        Schema::dropIfExists('reports');
    }
};
