<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
            $table->string('channel_name')->nullable();
            $table->boolean('is_guest')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_company_name')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_title')->nullable();
            $table->string('shipping_description')->nullable();
            $table->string('coupon_code')->nullable();
            $table->boolean('is_gift')->default(0);
            
            $table->string('status')->nullable();
            $table->string('state')->nullable();
            
            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('seller_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_seller_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_commission', 12, 4)->default(0)->nullable();
            $table->decimal('commission', 12, 4)->default(0)->nullable();
            $table->decimal('commission_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('grand_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_amount', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_discount_amount', 12, 4)->default(0)->nullable();
            $table->integer('total_item_count')->nullable();
            $table->integer('total_qty_ordered')->nullable();
            $table->decimal('discount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_invoiced', 12, 4)->default(0)->nullable();
            
            $table->decimal('seller_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_seller_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('total_paid',12,4)->default(0)->nullable();
            
            $table->decimal('grand_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('discount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_shipping_refunded', 12, 4)->default(0)->nullable();
            
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            
            $table->timestamp('created_at')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_orders');
    }
}
