<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('maximum_people')->unsigned();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('start')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('meeting_description')->nullable();
            $table->string('welcome_message')->nullable();
            $table->string('meeting_record')->nullable();
            $table->text('attendee_password')->nullable();
            $table->tinyInteger('mute_on_join')->default(0);
            $table->tinyInteger('require_moderator_approval')->default(0);
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
        Schema::dropIfExists('rooms');
    }
}
