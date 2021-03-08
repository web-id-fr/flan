<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('filter_name');
            $table->timestamps();
        });

        Schema::create('filter_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('filter_id');
            $table->string('name');
            $table->text('content')->nullable();
            $table->timestamps();
            $table->foreign('filter_id')->references('id')->on('filters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filter_fields', function (Blueprint $table) {
            $table->dropForeign(['filter_id']);
        });

        Schema::dropIfExists('filter_fields');

        Schema::dropIfExists('filters');
    }
}
