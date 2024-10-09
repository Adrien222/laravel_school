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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id', unsigned: true)->nullable();
            $table->integer('purchase_order_id', unsigned: true)->nullable();
            $table->float('total_amount');
            $table->float('amount_before_tax');
            $table->float('tax');
            $table->timestamp('send_at')->nullable();
            $table->timestamp('acquitted_at')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
