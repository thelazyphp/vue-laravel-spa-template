<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->string('transaction')->default('sell');
            $table->string('category');
            $table->string('type')->nullable();
            $table->string('source')->index();
            $table->string('url')->unique();
            $table->text('images')->nullable();
            $table->string('title')->nullable();
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('rooms')->nullable();
            $table->unsignedTinyInteger('floor')->nullable();
            $table->unsignedTinyInteger('floors')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->float('size_land')->nullable();
            $table->float('size_total')->nullable();
            $table->float('size_living')->nullable();
            $table->float('size_kitchen')->nullable();
            $table->string('roof')->nullable();
            $table->string('walls')->nullable();
            $table->string('balcony')->nullable();
            $table->string('bathroom')->nullable();
            $table->unsignedInteger('price_amount')->nullable();
            $table->string('price_currency')->default('USD');
            $table->unsignedInteger('price_sq_m_amount')->nullable();
            $table->string('price_sq_m_currency')->default('USD');
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_items');
    }
}
