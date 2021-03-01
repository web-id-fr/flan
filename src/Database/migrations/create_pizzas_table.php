<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePizzasTable extends Migration
{
    public function up(): void
    {
        Schema::create('pizzas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('price')->nullable();
            $table->boolean('active')->default(0);
            $table->timestamps();

            $table->unsignedBigInteger('feed_mode_id');
            $table->foreign('feed_mode_id')->references('id')->on('feed_modes')->onDelete('cascade');
        });
    }
}
