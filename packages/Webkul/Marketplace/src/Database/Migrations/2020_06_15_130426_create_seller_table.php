<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shop_title')->nullable();
            $table->string('url')->nullable()->unique();
            $table->string('tax_vat')->nullable();
            $table->string('phone')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->text('about_shop')->nullable();
            
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('skype')->nullable();
            $table->string('linked_in')->nullable();
            $table->string('pinterest')->nullable();
            
            $table->text('return_policy')->nullable();
            $table->text('shipping_policy')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            
            $table->decimal('commission_percentage', 12, 4)->nullable();
            $table->boolean('bo_status')->default(0);

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
        Schema::dropIfExists('sellers');
    }
}
