<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blood', function (Blueprint $table) {
            $table->increments('id');
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_mobile');
            $table->string('donor_address');
            $table->dateTime('last_bloodgiven');
            $table->boolean('is_enabled');
            $table->string('blood_group', 10);
            $table->integer('place_id');
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
        Schema::dropIfExists('blood');
    }
}
