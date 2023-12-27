<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicinesTable2 extends Migration
{
    public function up()
    {
        Schema::create('medicines_2', function (Blueprint $table) {
        $table->increments('id');
        $table->string('scientific_name');
            $table->string('name');
            $table->string('category');
            $table->string('brand');
            $table->integer('available_quantity');
            $table->date('expiry_date');
            $table->decimal('price', 8, 2);
            $table->text('description');
          $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('medicines_2');
    }
}
