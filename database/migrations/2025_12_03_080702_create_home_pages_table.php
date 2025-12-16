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
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();

            // HERO SECTION
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();

            // STATS SECTION
            $table->string('stats_title1')->nullable();
            $table->string('stats_value1')->nullable();
            $table->string('stats_title2')->nullable();
            $table->string('stats_value2')->nullable();
            $table->string('stats_title3')->nullable();
            $table->string('stats_value3')->nullable();
            $table->string('stats_title4')->nullable();
            $table->string('stats_value4')->nullable();

            // ABOUT SECTION
            $table->string('about_title')->nullable();
            $table->text('about_description')->nullable();

            $table->string('about_card1_title')->nullable();
            $table->text('about_card1_text')->nullable();
            $table->string('about_card2_title')->nullable();
            $table->text('about_card2_text')->nullable();
            $table->string('about_card3_title')->nullable();
            $table->text('about_card3_text')->nullable();

            $table->string('about_button_text')->nullable();
            $table->string('about_button_link')->nullable();

            // BEST SELLER SECTION
            $table->string('bestseller_title')->nullable();
            $table->text('bestseller_description')->nullable();

            // GALLERY SECTION
            $table->string('gallery_title')->nullable();
            $table->text('gallery_description')->nullable();

            // FAQ SECTION
            $table->string('faq1_question')->nullable();
            $table->text('faq1_answer')->nullable();
            $table->string('faq2_question')->nullable();
            $table->text('faq2_answer')->nullable();
            $table->string('faq3_question')->nullable();
            $table->text('faq3_answer')->nullable();
            $table->string('faq4_question')->nullable();
            $table->text('faq4_answer')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
