<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('user_id');
          $table->string('name');
          $table->double('saved_price');
          $table->boolean('favourite');
          $table->enum('type_general', ['type1', 'type2', 'type3']);
          $table->enum('type_specific', ['type1', 'type2', 'type3']);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('products');
    }
}
