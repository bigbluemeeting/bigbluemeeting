<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->text('short_description');
            $table->text('detail_description')->nullable();
            $table->integer('category_id');
            $table->integer('place_id');
            $table->text('address');
            $table->integer('pincode');
            $table->text('note')->nullable();
            $table->integer('visitor_count')->default(0);
            $table->integer('enquiry_count')->default(0);
            $table->string('email_id');
            $table->string('web_url')->nullable();
            $table->string('mobile_contact', 25);
            $table->string('landline_contact', 25)->nullable();
            $table->integer('profile_type_id');
            $table->text('contact_timing')->nullable();
            $table->text('profile_image_url')->nullable();
            $table->text('visting_card_image_url')->nullable();
            $table->text('search_tags');
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
        Schema::dropIfExists('profile');
    }
}
