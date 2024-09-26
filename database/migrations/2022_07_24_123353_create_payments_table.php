<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->text('payment_id')->nullable();
            $table->text('payment_link_id')->nullable();
            $table->text('payment_link_reference_id')->nullable();
            $table->text('payment_link_status')->nullable();
            $table->text('signature')->nullable();
            $table->text('method')->nullable();
            $table->integer('amount')->nullable();
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bill')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
}
