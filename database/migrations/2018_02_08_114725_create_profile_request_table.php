<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_request', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->integer('place_id');
            $table->integer('category_id');
            $table->string('mobile1', 20);
            $table->string('mobile2', 20);
            $table->string('address');
            $table->text('image');
            $table->boolean('is_approved');
            $table->dateTime('approved_date');
            $table->text('note');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_request');
    }
}
