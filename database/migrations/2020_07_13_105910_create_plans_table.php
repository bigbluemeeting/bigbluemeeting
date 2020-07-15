<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('participants_total');
            $table->unsignedInteger('participants_per_meeting');
            $table->unsignedInteger('moderators_per_meeting');
            $table->unsignedInteger('webcams');
            $table->enum('billing_frequency_interval',['week','month','year']);
            $table->integer('billing_frequency_interval_count')->default(1);
            $table->unsignedInteger('minutes')->default(0);
            $table->string('strip_plan_id')->default(NULL);
            $table->enum('plan_type',['shared','dedicated','cluster'])->default('shared');
            $table->tinyInteger('enable')->default(1);
            $table->string('recording_Size');
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
        Schema::dropIfExists('plans');
    }
}
