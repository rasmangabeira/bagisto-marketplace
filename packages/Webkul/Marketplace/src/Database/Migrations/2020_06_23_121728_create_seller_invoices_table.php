<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('grand_total', 12, 4)->default(0);
            $table->decimal('base_grand_total', 12, 4)->default(0);
            $table->string('comment')->nullable();
            $table->string('transaction_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')
                    ->on('seller_orders')->onDelete('cascade');
            
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')
                    ->on('sellers')->onDelete('cascade');
            
           
            
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
        Schema::dropIfExists('seller_invoices');
    }
}
