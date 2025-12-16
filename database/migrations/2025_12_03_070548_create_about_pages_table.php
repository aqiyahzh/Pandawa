<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();

            $table->string('main_title')->nullable();
            $table->text('main_description')->nullable();

            $table->string('event_1_title')->nullable();
            $table->string('event_1_desc')->nullable();
            $table->string('event_2_title')->nullable();
            $table->string('event_2_desc')->nullable();
            $table->string('event_3_title')->nullable();
            $table->string('event_3_desc')->nullable();

            $table->string('panel1_image')->nullable();
            $table->text('panel1_text')->nullable();

            $table->string('panel2_image')->nullable();
            $table->text('panel2_text')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
